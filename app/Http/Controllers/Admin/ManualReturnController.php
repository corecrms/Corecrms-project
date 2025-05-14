<?php

namespace App\Http\Controllers\Admin;

use App\Models\Unit;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Warehouse;
use App\Models\ManualReturn;
use Illuminate\Http\Request;
use App\Models\CreditActivity;
use App\Models\ManualReturnItem;
use App\Models\ProductWarehouse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Jobs\SendSaleReturnInvoiceJob;

class ManualReturnController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $returns = ManualReturn::all();
        $returns->load('customer', 'warehouse');
        $warehouses = Warehouse::all();
        $customers = Customer::all();
        return view('back.manual-sale-return.index', compact('returns', 'warehouses', 'customers'));
    }

    public function filterReturns(Request $req)
    {
        $query = ManualReturn::with('warehouse', 'customer');

        $filters = $req->all();

        if (isset($filters['date'])) {
            $query->where('date', $filters['date']);
        }

        if (isset($filters['reference'])) {
            $query->where('reference', $filters['reference']);
        }

        if (isset($filters['customer_id']) && $filters['customer_id'] > 0) {
            $customer = $filters['customer_id'];
            $query->where('customer_id', $customer);
        }

        if (isset($filters['warehouse_id'])  && $filters['warehouse_id'] > 0) {
            // $query->where('warehouse_id', $filters['warehouse_id']);
            $warehouse = $filters['warehouse_id'];
            $query->where('warehouse_id', $warehouse);
        }

        if (isset($filters['status'])  && $filters['status'] > 0) {
            $query->where('status', $filters['status']);
        }
        if (isset($filters['payment_status'])  && $filters['payment_status'] > 0) {
            $query->where('payment_status', $filters['payment_status']);
        }



        $returns = $query->get();
        $customers = Customer::all();
        $warehouses = Warehouse::all();

        return view('back.manual-sale-return.index', compact('returns', 'customers', 'warehouses'));
    }


    public function create_sale_return() {}


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $customers = Customer::all();
        $warehouse = Warehouse::all();
        $units = Unit::all();
        return view('back.manual-sale-return.manual-return', compact('customers', 'warehouse', 'units'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        try {

            DB::beginTransaction();

            // Generate a unique reference for the sale
            $reference = substr(uniqid(), 0, 5);
            // append 'SAL-' to the reference
            $reference = 'MRT-' . $reference;
            // Create new Sale entry
            $manual_return = new ManualReturn();
            $manual_return->reference = $reference;
            $manual_return->date = $request->date;
            $manual_return->customer_id = $request->customer_id;
            $manual_return->warehouse_id = $request->warehouse_id;
            $manual_return->order_tax = $request->order_tax;
            $manual_return->discount = $request->discount;
            $manual_return->shipping = $request->shipping;
            $manual_return->status = $request->status;
            $manual_return->payment_status = "Unpaid";
            $manual_return->amount_paid = "0.00";
            $manual_return->amount_due = $request->grand_total;
            $manual_return->details = $request->details;
            $manual_return->grand_total = $request->grand_total;
            $manual_return->created_by = auth()->user()->id;
            $manual_return->updated_by = auth()->user()->id;
            $manual_return->save();

            // Iterate over each product item
            foreach ($request->return_items as $itemData) {
                // $sale_unit = ProductItem::where('sale_id', $request->sale_id)->where('product_id', $itemData['id'])->first();
                // return $unit ?? '43';
                $productItem = new ManualReturnItem();
                $productItem->manual_return_id = $manual_return->id;
                $productItem->product_id = $itemData['id'];
                $productItem->return_quantity = $itemData['quantityReturn'];
                $productItem->price = $itemData['price'];
                $productItem->subtotal = $itemData['subtotal'];
                $productItem->return_unit = $itemData['return_unit'];
                $productItem->save();

                $product = Product::with('unit', 'sale_units')->find($itemData['id']);
                $warehouse_product = ProductWarehouse::where('product_id', $itemData['id'])->where('warehouse_id', $request->warehouse_id)->first();
                // dd($warehouse_product);
                // $item = ProductItem::where('sale_id', $request->sale_id)->where('product_id', $itemData['id'])->first();
                // // $product_unit = $product->product_unit;

                // $product->quantity += $itemData['quantityReturn'];
                // $product->save();
                $finalStock = 0;
                if ($product->product_type != 'service') {
                    // return $finalStock;
                    if ($product->product_unit != $itemData['return_unit']) {
                        $return_unit = Unit::find($itemData['return_unit']);
                        if ($return_unit->parent_id != 0) {
                            $expression = $warehouse_product->quantity . $product->unit->operator . $return_unit->operator_value;
                            $convertedStock = eval("return $expression;");
                            $stock = $convertedStock + $itemData['quantityReturn'];
                            $secondExp = $stock . $return_unit->operator . $return_unit->operator_value;
                            $finalStock = eval("return $secondExp;");
                        }
                    } else {
                        $finalStock =  $warehouse_product->quantity + $itemData['quantityReturn'];
                    }
                } else {
                    $finalStock =  $warehouse_product->quantity + $itemData['quantityReturn'];
                }

                $warehouse_product->update(['quantity' => "$finalStock"]);
            }

            $amount = $manual_return->grand_total;

            
            $customer = Customer::find($request->customer_id);
            $customer->balance += $request->grand_total;
            $customer->save();

            CreditActivity::create([
                'customer_id' => $customer->id,
                'action' => 'Modified',
                'credit_balance' => $customer->balance,
                'added_deducted' => $request->grand_total,
                'comment' => "Balance returned to wallet due to sale return",
            ]);

            DB::commit();


            try {

                $job = new SendSaleReturnInvoiceJob($manual_return, $customer->user->email);
                dispatch($job);
                Log::info('Email sent to: ' . $customer->user->email);
            } catch (\Exception $e) {
                Log::error('Error sending email: ' . $e->getMessage());
            }









            
            return response()->json(['message' => 'Return successfully created', 'reference' => $reference], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error occurred: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $sale_return = ManualReturn::find($id);
        $sale_return->load('return_items', 'customer', 'warehouse');
        // dd($sale_return);
        return view('back.manual-sale-return.show', compact('sale_return'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $sale_return = ManualReturn::find($id);
        $sale_return->load('return_items');
        return view('back.manual-sale-return.edit', compact('sale_return'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // dd($request->all());

        try {

            DB::beginTransaction();

            // Update Sale entry
            $manual_return = ManualReturn::find($id);
            $manual_return->date = $request->date;
            $manual_return->order_tax = $request->order_tax;
            $manual_return->discount = $request->discount;
            $manual_return->shipping = $request->shipping;
            $manual_return->status = $request->status;
            // $sale->payment_status = "unpaid";
            // $sale->amount_paid = "0.00";
            $manual_return->amount_due = $request->grand_total;
            $manual_return->details = $request->details;
            $manual_return->grand_total = $request->grand_total;
            $manual_return->save();

            // Iterate over each product item
            foreach ($request->return_items as $itemData) {

                $productItem = ManualReturnItem::where('manual_return_id', $id)->where('product_id', $itemData['id'])->first();
                $productItem->load('product', 'return_units');

                $product = Product::find($productItem->product->id);
                $warehouse_product = ProductWarehouse::where('product_id', $itemData['id'])->where('warehouse_id', $manual_return->warehouse_id)->first();
                // dd($warehouse_product);
                if ($productItem->return_quantity != $itemData['quantityReturn']) {
                    // first remove quantity

                    if ($productItem->product->product_type != 'service') {
                        if ($product->product_unit != $productItem->return_unit) {

                            $expression = $warehouse_product->quantity . $product->unit->operator . $productItem->return_units->operator_value;
                            $convertedStock = eval("return $expression;");
                            $stock = $convertedStock - $productItem->return_quantity;
                            $secondExp = $stock . $productItem->return_units->operator . $productItem->return_units->operator_value;
                            $finalStock = eval("return $secondExp;");

                            $warehouse_product->quantity = "$finalStock";
                            $warehouse_product->save();
                        } else {
                            $warehouse_product->quantity -= $productItem->return_quantity;
                            $warehouse_product->save();
                        }
                    } else {
                        $warehouse_product->quantity -= $productItem->return_quantity;
                        $warehouse_product->save();
                    }

                    // update quantity
                    $finalStock = 0;
                    if ($product->product_type != 'service') {
                        // return $finalStock;
                        if ($product->product_unit != $productItem->return_unit) {
                            $return_unit = Unit::find($productItem->return_unit);
                            if ($return_unit->parent_id != 0) {
                                $expression = $warehouse_product->quantity . $product->unit->operator . $return_unit->operator_value;
                                $convertedStock = eval("return $expression;");
                                $stock = $convertedStock + $itemData['quantityReturn'];
                                $secondExp = $stock . $return_unit->operator . $return_unit->operator_value;
                                $finalStock = eval("return $secondExp;");
                            }
                        } else {
                            $finalStock =  $warehouse_product->quantity + $itemData['quantityReturn'];
                        }
                    } else {
                        $finalStock =  $warehouse_product->quantity + $itemData['quantityReturn'];
                    }

                    $warehouse_product->update(['quantity' => "$finalStock"]);
                }

                $productItem->return_quantity = $itemData['quantityReturn'];
                $productItem->price = $itemData['price'];
                $productItem->subtotal = $itemData['subtotal'];
                $productItem->save();
            }


            DB::commit();
            return response()->json(['message' => 'Update Return successfully created'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error occurred: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $sale_return = ManualReturn::find($id);
        if ($sale_return) {
            $return_items = ManualReturnItem::where('manual_return_id', $id)->get();
            $return_items->load('product', 'return_units');
            foreach ($return_items as $return) {
                $product = Product::find($return->product->id);
                $warehouse_product = ProductWarehouse::where('product_id', $return->product->id)->where('warehouse_id', $sale_return->warehouse_id)->first();

                if ($return->product->product_type != 'service') {
                    if ($product->product_unit != $return['return_unit']) {
                        $expression = $warehouse_product->quantity . $product->unit->operator . $return->return_units->operator_value;
                        $convertedStock = eval("return $expression;");
                        $stock = $convertedStock - $return['return_quantity'];
                        $secondExp = $stock . $return->return_units->operator . $return->return_units->operator_value;
                        $finalStock = eval("return $secondExp;");
                    } else {
                        $finalStock = $warehouse_product->quantity - $return['return_quantity'];
                    }
                } else {
                    $finalStock = $warehouse_product->quantity - $return['return_quantity'];
                }
                $warehouse_product->update(['quantity' => "$finalStock"]);
                $return->delete();
            }

            $sale_return->delete();
            return redirect()->back()->with('success', 'Sale return has been deleted successfully!');
        }
    }


    public function deleteReturns(Request $req)
    {

        foreach ($req->ids as $key => $id) {
            # code...
            $sale_return = ManualReturn::find($id);
            if ($sale_return) {
                $return_items = ManualReturnItem::where('manual_return_id', $id)->get();
                $return_items->load('product', 'return_units');
                foreach ($return_items as $return) {
                    $product = Product::find($return->product->id);
                    $warehouse_product = ProductWarehouse::where('product_id', $return->product->id)->where('warehouse_id', $sale_return->warehouse_id)->first();

                    if ($return->product->product_type != 'service') {
                        if ($product->product_unit != $return['return_unit']) {
                            $expression = $warehouse_product->quantity . $product->unit->operator . $return->return_units->operator_value;
                            $convertedStock = eval("return $expression;");
                            $stock = $convertedStock - $return['return_quantity'];
                            $secondExp = $stock . $return->return_units->operator . $return->return_units->operator_value;
                            $finalStock = eval("return $secondExp;");
                        } else {
                            $finalStock = $warehouse_product->quantity - $return['return_quantity'];
                        }
                    } else {
                        $finalStock = $warehouse_product->quantity - $return['return_quantity'];
                    }
                    $warehouse_product->update(['quantity' => "$finalStock"]);
                    $return->delete();
                }

                $sale_return->delete();
            }
        }

        return response()->json(['status' => 200, 'message' => 'Sale Return Deleted Successfully!']);
    }
}
