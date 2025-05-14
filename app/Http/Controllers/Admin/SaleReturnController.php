<?php

namespace App\Http\Controllers\Admin;

use App\Models\Sale;
use App\Models\Unit;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Warehouse;
use App\Models\SaleReturn;
use App\Models\ProductItem;
use Illuminate\Http\Request;
use App\Models\SaleReturnItem;
use App\Models\ProductWarehouse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\BaseController;
use App\Models\CreditActivity;

class SaleReturnController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $returns = SaleReturn::with('sales')->get();
        $returns = SaleReturn::all();
        if(auth()->user()->hasRole(['Cashier','Manager'])){

            $returns = SaleReturn::whereHas('sales', function ($query) {
                $query->where('warehouse_id', auth()->user()->warehouse_id);
            })->get();
            $returns->load('sales');
            $customers = Customer::all();
            $warehouses = Warehouse::all();
            // dd($returns->sales);
            return view('back.sale-return.index', compact('returns','customers','warehouses'));
        }

        if (session()->has('selected_warehouse_id') && auth()->user()->hasRole('Admin')) {
            $warehouseId = session()->get('selected_warehouse_id');
            $returns = SaleReturn::whereHas('sales', function ($query) use ($warehouseId) {
                $query->where('warehouse_id', $warehouseId);
            })->get();
        } 


        $returns->load('sales');
        $customers = Customer::all();
        $warehouses = Warehouse::all();
        // dd($returns->sales);
        return view('back.sale-return.index', compact('returns','customers','warehouses'));
    }

    public function filterSalereturns(Request $req){
        $query = SaleReturn::with('sales');

        $filters = $req->all();

        if(isset($filters['date'])){
            $query->where('date', $filters['date']);
        }

        if(isset($filters['reference'])){
            $query->where('reference', $filters['reference']);
        }

        if(isset($filters['customer_id']) && $filters['customer_id'] > 0){
            $customer = $filters['customer_id'];
            $query->whereHas('sales', function ($query) use ($customer){
                $query->where('customer_id', $customer);
            });

        }

        if(isset($filters['warehouse_id'])  && $filters['warehouse_id'] > 0){
            // $query->where('warehouse_id', $filters['warehouse_id']);
            $warehouse = $filters['warehouse_id'];
            $query->whereHas('sales', function ($query) use ($warehouse){
                $query->where('warehouse_id', $warehouse);
            });
        }

        if(isset($filters['status'])  && $filters['status'] > 0 ){
            $query->where('status', $filters['status']);
        }
        if(isset($filters['payment_status'])  && $filters['payment_status'] > 0){
            $query->where('payment_status', $filters['payment_status']);
        }



        $returns = $query->get();
        $customers = Customer::all();
        $warehouses = Warehouse::all();

        return view('back.sale-return.index', compact('returns','customers','warehouses'));


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
        // return $request->sale_id;
        try {

            DB::beginTransaction();

            // Generate a unique reference for the sale
            $reference = substr(uniqid(), 0, 5);
            // append 'SAL-' to the reference
            $reference = 'RT-' . $reference;
            // Create new Sale entry
            $sale = new SaleReturn();
            $sale->reference = $reference;
            $sale->date = $request->date;
            $sale->order_tax = $request->order_tax;
            $sale->discount = $request->discount;
            $sale->shipping = $request->shipping;
            $sale->status = $request->status;
            $sale->payment_status = "Unpaid";
            $sale->amount_paid = "0.00";
            $sale->amount_due = $request->grand_total;
            $sale->details = $request->details;
            $sale->grand_total = $request->grand_total;
            $sale->sale_id = $request->sale_id;
            $sale->created_by = auth()->user()->id;
            $sale->updated_by = auth()->user()->id;
            $sale->save();

            $productPrice = 0;
            // Iterate over each product item
            foreach ($request->return_items as $itemData) {
                $sale_unit = ProductItem::where('sale_id', $request->sale_id)->where('product_id', $itemData['id'])->first();
                // return $unit ?? '43';
                $productItem = new SaleReturnItem();
                $productItem->sale_return_id = $sale->id;
                $productItem->product_id = $itemData['id'];
                $productItem->return_quantity = $itemData['quantityReturn'];
                $productItem->price = $itemData['price'];
                $productItem->subtotal = $itemData['subtotal'];
                $productItem->sale_unit = $sale_unit->sale_unit;
                $productItem->save();

                $product = Product::with('unit', 'sale_units')->find($itemData['id']);
                $productPrice += $itemData['subtotal'];
                $sales = Sale::find($request->sale_id);

                $warehouse_product = ProductWarehouse::where('product_id',$itemData['id'])->where('warehouse_id',$sales->warehouse_id)->first();
                // dd($warehouse_product);
                $item = ProductItem::where('sale_id', $request->sale_id)->where('product_id', $itemData['id'])->first();
                // // $product_unit = $product->product_unit;

                // $product->quantity += $itemData['quantityReturn'];
                // $product->save();
                $finalStock = 0;
                if ($product->product_type != 'service') {
                    // return $finalStock;
                    if ($product->product_unit != $item->sale_unit) {
                        $sale_unit = Unit::find($item->sale_unit);
                        if ($sale_unit->parent_id != 0) {
                            $expression = $warehouse_product->quantity . $product->unit->operator . $sale_unit->operator_value;
                            $convertedStock = eval("return $expression;");
                            $stock = $convertedStock + $itemData['quantityReturn'];
                            $secondExp = $stock . $sale_unit->operator . $sale_unit->operator_value;
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

            $customer = Customer::find($sales->customer_id);
            $customer->balance += $productPrice;
            $customer->save();

            CreditActivity::create([
                'customer_id' => $customer->id,
                'action' => 'Modified',
                'credit_balance' => $customer->balance,
                'added_deducted' => $productPrice,
                'comment' => "Balance returned to wallet due to sale return for #{$sales->reference}",
            ]);


            DB::commit();
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
        // $sale = Sale::with('productItems.product','productItems.sale_units','warehouse','customer')->find($id);
        $sale = Sale::find($id);
        $sale->load('productItems', 'warehouse', 'customer');
        // dd($sale);

        return view('back.sale-return.create', compact('sale'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // $sale = Sale::with('productItems.product','warehouse','customer')->find($id);
        $sale_return = SaleReturn::find($id);
        $sale_return->load('return_items', 'sales');
        // dd($sale_return);
        return view('back.sale-return.edit', compact('sale_return'));
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
        // return $request->all();
        try {

            DB::beginTransaction();

            // Update Sale entry
            $sale = SaleReturn::find($id);
            $sale->date = $request->date;
            $sale->order_tax = $request->order_tax;
            $sale->discount = $request->discount;
            $sale->shipping = $request->shipping;
            $sale->status = $request->status;
            // $sale->payment_status = "unpaid";
            // $sale->amount_paid = "0.00";
            $sale->amount_due = $request->grand_total;
            $sale->details = $request->details;
            $sale->grand_total = $request->grand_total;
            $sale->updated_by = auth()->user()->id;
            $sale->save();

            // Iterate over each product item
            foreach ($request->return_items as $itemData) {

                $productItem = SaleReturnItem::where('sale_return_id', $id)->where('product_id', $itemData['id'])->first();
                $productItem->load('product');
                // return $productItem->product->unit;
                $sales = Sale::find($sale->sale_id);
                $warehouse_product = ProductWarehouse::where('product_id',$itemData['id'])->where('warehouse_id',$sales->warehouse_id)->first();
                // dd($warehouse_product);
                $finalStock = $productItem->product->quantity;
                if ($productItem->return_quantity != $itemData['quantityReturn']) {
                    $quantityDeference = $productItem->return_quantity - $itemData['quantityReturn'];
                    $sale_unit = Unit::find($productItem->sale_unit);

                    if ($productItem->product->product_type != 'service') {
                        if ($productItem->product->unit->id != $productItem->sale_unit) {
                            if ($productItem->return_quantity > $itemData['quantityReturn']) {
                                if ($sale_unit->parent_id != 0) {
                                    $expression = $warehouse_product->quantity . $productItem->product->unit->operator . $sale_unit->operator_value;
                                    $convertedStock = eval("return $expression;");
                                    $stock = $convertedStock - $quantityDeference;
                                    $secondExp = $stock . $sale_unit->operator . $sale_unit->operator_value;
                                    $finalStock = eval("return $secondExp;");
                                }
                            } else {
                                if ($sale_unit->parent_id != 0) {
                                    $expression = $warehouse_product->quantity . $productItem->product->unit->operator . $sale_unit->operator_value;
                                    $convertedStock = eval("return $expression;");
                                    $stock = $convertedStock - $quantityDeference;
                                    $secondExp = $stock . $sale_unit->operator . $sale_unit->operator_value;
                                    $finalStock = eval("return $secondExp;");
                                }
                            }
                        } else {
                            if ($productItem->return_quantity > $itemData['quantityReturn']) {
                                $finalStock = $warehouse_product->quantity - $quantityDeference;
                            } else {
                                $finalStock = $warehouse_product->quantity - $quantityDeference;
                            }
                        }
                    } else {
                        if ($productItem->return_quantity > $itemData['quantityReturn']) {
                            $finalStock = $warehouse_product->quantity - $quantityDeference;
                        } else {
                            $finalStock = $warehouse_product->quantity - $quantityDeference;
                        }
                    }

                }
                else {
                    // if quantity not updated
                }

                $productItem->return_quantity = $itemData['quantityReturn'];
                $productItem->price = $itemData['price'];
                $productItem->subtotal = $itemData['subtotal'];
                $productItem->save();
                $warehouse_product->update(['quantity' => "$finalStock"]);
            }


            DB::commit();
            return response()->json(['message' => 'Update Return successfully created'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error occurred: ' . $e->getMessage()], 500);
        }
    }


    public function detail($id){
        $sale_return = SaleReturn::find($id);
        $sale = Sale::find($sale_return->sale_id);
        $sale->load('productItems.product.unit', 'productItems.product.sale_units','customer');
        $sale_return->load('return_items.product.unit','return_items.product.sale_units');
        // dd($sale_return->sales->customer);
        return view('back.sale-return.detail',compact('sale_return','sale'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $sale_return = SaleReturn::find($id);
        if ($sale_return) {
            $return_items = SaleReturnItem::where('sale_return_id', $id)->get();
            $return_items->load('product','sale_units');
            foreach ($return_items as $return) {
                // dd($return['return_quantity']);
                $product = Product::find($return->product->id);
                $sales = Sale::find($sale_return->sale_id);
                $warehouse_product = ProductWarehouse::where('product_id',$return->product->id)->where('warehouse_id',$sales->warehouse_id)->first();
                // dd($warehouse_product);

                $finalStock = 0;
                if($return->product->product_type != 'service'){
                    if($product->product_unit != $return['sale_unit']){
                            $expression = $warehouse_product->quantity . $product->unit->operator . $return->sale_units->operator_value;
                            $convertedStock = eval("return $expression;");
                            $stock = $convertedStock - $return['return_quantity'];
                            $secondExp = $stock . $return->sale_units->operator . $return->sale_units->operator_value;
                            $finalStock = eval("return $secondExp;");
                    }
                    else
                    {

                        $finalStock = $warehouse_product->quantity - $return->return_quantity;
                    }
                }
                else
                {
                    $finalStock = $warehouse_product->quantity - $return->return_quantity;
                }

                $warehouse_product->update(['quantity' => "$finalStock"]);
                $return->delete();
            }

            $sale_return->delete();
            return redirect()->back()->with('success', 'Sale return has been deleted successfully!');
        }
    }

    public function deleteSaleReturn(Request $req)
    {
        // return $req->all();
        foreach ($req->ids as $key => $id) {
            # code...
            $sale_return = SaleReturn::find($id);
            if ($sale_return) {
                $return_items = SaleReturnItem::where('sale_return_id', $id)->get();
                $return_items->load('product','sale_units');
                foreach ($return_items as $return) {
                    // dd($return['return_quantity']);
                    $product = Product::find($return->product->id);
                    $sales = Sale::find($sale_return->sale_id);
                    $warehouse_product = ProductWarehouse::where('product_id',$return->product->id)->where('warehouse_id',$sales->warehouse_id)->first();
                    // dd($warehouse_product);

                    $finalStock = 0;
                    if($return->product->product_type != 'service'){
                        if($product->product_unit != $return['sale_unit']){
                                $expression = $warehouse_product->quantity . $product->unit->operator . $return->sale_units->operator_value;
                                $convertedStock = eval("return $expression;");
                                $stock = $convertedStock - $return['return_quantity'];
                                $secondExp = $stock . $return->sale_units->operator . $return->sale_units->operator_value;
                                $finalStock = eval("return $secondExp;");
                        }
                        else
                        {

                            $finalStock = $warehouse_product->quantity - $return->return_quantity;
                        }
                    }
                    else
                    {
                        $finalStock = $warehouse_product->quantity - $return->return_quantity;
                    }

                    $warehouse_product->update(['quantity' => "$finalStock"]);
                    $return->delete();
                }

                $sale_return->delete();
            }
        }
        return response()->json(['status' => 200,'message' => 'Sale Return Deleted Successfully!']);

    }
}
