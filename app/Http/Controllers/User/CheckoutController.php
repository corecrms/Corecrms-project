<?php

namespace App\Http\Controllers\User;

use App\Models\Sale;
use App\Models\Unit;
use App\Models\Product;
use App\Models\AddToCart;
use App\Models\ProductItem;
use App\Models\SaleInvoice;
use Illuminate\Support\Str;
use App\Models\SalesPayment;
use Illuminate\Http\Request;
use App\Models\ProductWarehouse;
use Illuminate\Support\Facades\DB;
use App\Models\SaleShippingAddress;
use App\Http\Controllers\Controller;

class CheckoutController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        if(auth()->user()->hasRole(['Admin','Cashier','Manager'])){
            return redirect()->back()->with('error', 'Only customer can place order.');
        }

        $countries = [
            'United States', 'Canada', 'Brazil', 'United Kingdom', 'France', 'Germany', 'Italy', 'Spain',
            'Russia', 'China', 'India', 'Japan', 'South Korea', 'Australia', 'New Zealand', 'Mexico',
            'Argentina', 'South Africa', 'Egypt', 'Saudi Arabia', 'Pakistan'
        ];

        return view('user.checkout.index', compact('countries'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'contact_no' => 'required',
            'address' => 'required',
            'city' => 'required',
            'country' => 'required',
            'zip_code' => 'required',
            'state' => 'required',
            'payment_method' => 'required',
            'products' => 'required',
        ]);

        $data = $request->all();
        $data['status'] = 'Pending';
        $data['payment_status'] = "pending";
        $data['payment_method'] = $request->payment_method;
        // dd($data);

        // Convert the array to a collection
        $productsCollection = collect($data['products']);

        // Group products by warehouse_id
        $groupedProducts = $productsCollection->groupBy('warehouse_id');

        // dd($groupedProducts);

        // Convert the collection back to an array if needed
        $groupedProductsArray = $groupedProducts->toArray();

        foreach ($groupedProductsArray as $warehouse_id => $products) {
            // dd($warehouse_id);
            // dd($products);

            try {
                DB::beginTransaction();

                $uuid = Str::uuid();
                $reference = substr($uuid, 0, 6);
                $reference = 'Order#' . $reference;

                $sale = new Sale();
                $sale->reference = $reference;
                $sale->date = date('Y-m-d');
                $sale->customer_id = $data['customer_id'];
                $sale->ntn = $request->ntn_no ?? null;
                $sale->amount_recieved = $request->amount_recieved ?? 0;
                $sale->amount_due = $request->grand_total;
                $sale->amount_pay = $request->grand_total;
                $sale->grand_total = $request->grand_total;
                $sale->notes = $request->notes;
                $sale->status = $data['status'];
                $sale->payment_status = $data['payment_status'];
                $sale->payment_method = $data['payment_method'];
                $sale->warehouse_id = $warehouse_id;
                $sale->save();

                foreach ($products as $itemData) {
                    $product = Product::find($itemData['product_id']);
                    $productItem = new ProductItem();
                    $productItem->sale_id = $sale->id;
                    $productItem->product_id = $itemData['product_id'];
                    $productItem->quantity = $itemData['quantity'];
                    $productItem->price = $itemData['price'];
                    $productItem->discount = 0;
                    $productItem->order_tax = 0;
                    $productItem->sub_total = $itemData['price'] * $itemData['quantity'];
                    $productItem->sale_unit = $product->sale_unit ?? null;
                    $productItem->save();

                    // Rest of your code...
                    $product = Product::with('unit', 'sale_units')->find($itemData['product_id']);
                    $warehouse_product = ProductWarehouse::where('product_id', $itemData['product_id'])->where('warehouse_id', $warehouse_id)->first();
                    $productQuantity = $warehouse_product->quantity;
                    $finalStock = 0;
                    if ($product->product_type != 'service') {
                        $convertedUnit = Unit::find($product->sale_unit);
                        // dd($convertedUnit);
                        if ($product->product_unit != $convertedUnit->id) {
                            if ($product->unit->parent_id == 0) {
                                $expression = $productQuantity . $product->unit->operator . $convertedUnit->operator_value;
                                $convertedStock = eval("return $expression;");
                                $stock = $convertedStock - $itemData['quantity'];
                                $secondExp = $stock . $convertedUnit->operator . $convertedUnit->operator_value;
                                $finalStock = eval("return $secondExp;");
                            }
                        } else {
                            $finalStock = $productQuantity - $itemData['quantity'];
                        }
                    } else {
                        $finalStock = $productQuantity - $itemData['quantity'];
                    }
                    $warehouse_product->update(['quantity' => $finalStock]);



                }

                // Rest of your code...
                $uuid = Str::uuid();
                $invoice = substr($uuid, 0, 6);
                $invoice_id = 'INV-' . $invoice;

                $invoice =  new SaleInvoice();
                $invoice->invoice_id = $reference;
                $invoice->sale_id = $sale->id;
                $invoice->user_id = auth()->user()->id;
                $invoice->save();

                $data['sale_id'] = $sale->id;
                SaleShippingAddress::create($data);

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                // return redirect()->back()->with('error', 'Something went wrong, please try again.');
                return response()->json(['error' => 'Error occurred: ' . $e->getMessage()], 500);
            }
        }

        AddToCart::where('customer_id', auth()->id())->delete();
        return response()->json(['success' => 'Order placed successfully!', 'status' => 200]);
        // return redirect()->route('user.checkout.success');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
