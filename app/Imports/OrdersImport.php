<?php

namespace App\Imports;

use App\Models\Sale;
use App\Models\Unit;
use App\Models\User;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Warehouse;
use App\Models\ProductItem;
use App\Models\SaleInvoice;
use Illuminate\Support\Str;
use App\Models\ProductWarehouse;
use Illuminate\Support\Facades\DB;
use App\Models\SaleShippingAddress;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;


class OrdersImport implements ToModel, WithStartRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */

    private $newOrdersCount = 0;
    // private $newOrders = [];

    public function model(array $row)
    {
        //  dd($row);
        //  $user = User::where('email', $row[2])->first();
        $product = Product::where('sku', $row[1])->first();
        $warehouse = User::where('name', $row[0])->first()->warehouse;
        // dd($warehouse);
        if (!$product || !$warehouse || $row[2] == 0) {
            return null;
        }

        $indexes = [2];
        foreach ($indexes as $index) {
            if (!isset($row[$index]) || empty($row[$index])) {
                return null;
            }
        }

        try {
            DB::beginTransaction();


            // $uuid = Str::uuid();
            // $reference = substr($uuid, 0, 6);
            // $reference = 'Order#' . $reference;
            $reference = mt_rand(10000000, 99999999);
            $sale = new Sale();
            $sale->reference = $reference;
            $sale->date = date('Y-m-d');
            $sale->customer_id = auth()->user()->customer->id;
            $sale->warehouse_id = $warehouse->id;
            $sale->status = 'Pending';
            $sale->payment_method = 'Cash on Delivery';
            $sale->payment_status = 'pending';
            $sale->grand_total = $product->sell_price * $row[2];
            $sale->amount_pay = $product->sell_price * $row[2];
            $sale->amount_due = $product->sell_price * $row[2];
            $sale->amount_recieved = 0;
            $sale->order_tax = 0.00;
            $sale->discount = 0.00;
            $sale->save();

            // $sale->products()->attach($product->id, ['quantity' => $row[2], 'price' => $product->sell_price]);
            $item = new ProductItem();
            $item->sale_id = $sale->id;
            $item->product_id = $product->id;
            $item->quantity = $row[2];
            $item->price = $product->sell_price;
            $item->sub_total = $product->sell_price * $row[2];
            $item->order_tax = 0.00;
            $item->discount = 0.00;
            $item->sale_unit = $product->sale_unit ?? null;
            $item->save();

            // decrement quantity
            // Rest of your code...
            $product = Product::with('unit', 'sale_units')->find($product->id);
            $warehouse_product = ProductWarehouse::where('product_id', $product->id)->where('warehouse_id', $warehouse->id)->first();
            $productQuantity = $warehouse_product->quantity;
            if($productQuantity < $row[2])
            {
                return null;
            }
            $finalStock = 0;
            if ($product->product_type != 'service') {
                $convertedUnit = Unit::find($product->sale_unit);
                // dd($convertedUnit);
                if ($product->product_unit != $convertedUnit->id) {
                    if ($product->unit->parent_id == 0) {
                        $expression = $productQuantity . $product->unit->operator . $convertedUnit->operator_value;
                        $convertedStock = eval("return $expression;");
                        $stock = $convertedStock - $row[2];
                        $secondExp = $stock . $convertedUnit->operator . $convertedUnit->operator_value;
                        $finalStock = eval("return $secondExp;");
                    }
                } else {
                    $finalStock = $productQuantity - $row[2];
                }
            } else {
                $finalStock = $productQuantity - $row[2];
            }
            $warehouse_product->update(['quantity' => $finalStock]);


            // $uuid = Str::uuid();
            // $invoice = substr($uuid, 0, 6);
            // $invoice_id = 'INV-' . $invoice;

            $invoice =  new SaleInvoice();
            $invoice->invoice_id = $reference;
            $invoice->sale_id = $sale->id;
            $invoice->user_id = auth()->user()->id;
            $invoice->save();

            $data['sale_id'] = $sale->id;
            $data['customer_id'] = auth()->user()->customer->id;
            $data['name'] = auth()->user()->name;
            $data['email'] = auth()->user()->email;
            $data['contact_no'] = auth()->user()->contact_no ?? "89898";
            $data['address'] = auth()->user()->address ?? 'Address';
            $data['city'] = auth()->user()->customer->city ?? "City";
            $data['country'] = auth()->user()->customer->country ?? 'Country';
            $data['state'] = auth()->user()->customer->state ?? 'State';
            $data['zip_code'] = auth()->user()->customer->state ?? '1234566';
            SaleShippingAddress::create($data);

            $this->newOrdersCount++;
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            // return redirect()->back()->with('error', 'Something went wrong, please try again.');
            // return response()->json(['error' => 'Error occurred: ' . $e->getMessage()], 500);
            dd($e->getMessage());
            // return redirect()->back()->with('error', 'Something went wrong, please try again.');
        }
    }

    public function newOrdersCount()
    {
        return $this->newOrdersCount;
    }

    public function startRow(): int
    {
        return 2;
    }
}
