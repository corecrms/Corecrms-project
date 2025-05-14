<?php

namespace App\Http\Controllers\Admin;

use App\Models\Unit;
use App\Models\Product;
use App\Models\Purchase;
use Illuminate\Http\Request;
use App\Models\PurchaseReturn;
use App\Models\ProductWarehouse;
use App\Models\PurchaseReturnItem;
use Illuminate\Support\Facades\DB;
use App\Models\PurchaseProductItem;
use App\Http\Controllers\Controller;
use App\Models\Vendor;
use App\Models\Warehouse;

class PurchaseReturnController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */



    public function index()
    {
        if(auth()->user()->hasRole(['Cashier','Manager'])){
            $returns = PurchaseReturn::whereHas('purchase', function ($query) {
                $query->where('warehouse_id', auth()->user()->warehouse_id);
            })->get();
        }
        else{
            if (session()->has('selected_warehouse_id') && auth()->user()->hasRole('Admin')) {
                $warehouseId = session()->get('selected_warehouse_id');
                $returns = PurchaseReturn::whereHas('purchase', function ($query) use ($warehouseId) {
                    $query->where('warehouse_id', $warehouseId);
                })->get();
            } else {
                $returns = PurchaseReturn::all();
            }

        }
        $returns->load('purchase');
        $suppliers = Vendor::all();
        $warehouses = Warehouse::all();

        return view('back.purchase-return.index', compact('returns','suppliers','warehouses'));
    }


    public function filterPurchaseReturn(Request $req){
        $query = PurchaseReturn::with('purchase');

        $filters = $req->all();

        if(isset($filters['date'])){
            $query->where('date', $filters['date']);
        }

        if(isset($filters['reference'])){
            $query->where('reference', $filters['reference']);
        }

        if(isset($filters['vendor_id']) && $filters['vendor_id'] > 0){
            $vendor = $filters['vendor_id'];
            $query->whereHas('purchase', function ($query) use ($vendor){
                $query->where('vendor_id', $vendor);
            });

        }

        if(isset($filters['warehouse_id'])  && $filters['warehouse_id'] > 0){
            // $query->where('warehouse_id', $filters['warehouse_id']);
            $warehouse = $filters['warehouse_id'];
            $query->whereHas('purchase', function ($query) use ($warehouse){
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
        $suppliers = Vendor::all();
        $warehouses = Warehouse::all();

        return view('back.purchase-return.index', compact('returns','suppliers','warehouses'));



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
        // return $request;
        // try {

        //     DB::beginTransaction();

        //     // Generate a unique reference for the sale
        //     $reference = substr(uniqid(), 0, 5);
        //     // append 'SAL-' to the reference
        //     $reference = 'RT-' . $reference;
        //     // Create new Sale entry
        //     $purchase = new PurchaseReturn();
        //     $purchase->reference = $reference;
        //     $purchase->date = $request->date;
        //     $purchase->order_tax = $request->order_tax;
        //     $purchase->discount = $request->discount;
        //     $purchase->shipping = $request->shipping;
        //     $purchase->status = $request->status;
        //     $purchase->payment_status = "unpaid";
        //     $purchase->amount_paid = "0.00";
        //     $purchase->amount_due = $request->grand_total;
        //     $purchase->details = $request->details;
        //     $purchase->grand_total = $request->grand_total;
        //     $purchase->purchase_id = $request->purchase_id;
        //     $purchase->save();

        //     // Iterate over each product item
        //     foreach ($request->return_items as $itemData) {
        //         $purchase_unit = PurchaseProductItem::where('purchase_id', $request->purchase_id)->where('product_id', $itemData['id'])->first();
        //         $productItem = new PurchaseReturnItem();
        //         $productItem->purchase_return_id = $purchase->id;
        //         $productItem->product_id = $itemData['id'];
        //         $productItem->return_quantity = $itemData['quantityReturn'];
        //         $productItem->price = $itemData['price'];
        //         $productItem->subtotal = $itemData['subtotal'];
        //         $productItem->purchase_unit = $purchase_unit->purchase_unit ?? null;
        //         $productItem->save();
        //         // return $purchase_unit;

        //         $product = Product::with('unit', 'purchase_unit')->find($itemData['id']);
        //         $item = PurchaseReturnItem::where('purchase_return_id', $request->purchase_id)->where('product_id', $itemData['id'])->first();
        //         return $item;
        //         $finalStock = 0;
        //         if ($product->product_type != 'service') {

        //             if ($product->product_unit != $item->purchase_unit) {
        //                 $purchase_unit = Unit::find($item->purchase_unit);
        //                 if ($purchase_unit->parent_id != 0) {
        //                     $expression = $product->quantity . $product->unit->operator . $purchase_unit->operator_value;
        //                     $convertedStock = eval("return $expression;");
        //                     $stock = $convertedStock - $itemData['quantityReturn'];
        //                     $secondExp = $stock . $purchase_unit->operator . $purchase_unit->operator_value;
        //                     $finalStock = eval("return $secondExp;");
        //                 }
        //             } else {
        //                 $finalStock =  $product->quantity - $itemData['quantityReturn'];
        //             }
        //         } else {
        //             $finalStock =  $product->quantity - $itemData['quantityReturn'];
        //         }

        //         $product->update(['quantity' => "$finalStock"]);
        //     }


        //     DB::commit();
        //     return response()->json(['message' => 'Return successfully created', 'reference' => $reference], 200);
        // } catch (\Exception $e) {
        //     DB::rollBack();
        //     return response()->json(['message' => 'Error occurred: ' . $e->getMessage()], 500);
        // }

        try {

            DB::beginTransaction();

            // Generate a unique reference for the sale
            $reference = substr(uniqid(), 0, 5);
            // append 'SAL-' to the reference
            $reference = 'RT-' . $reference;
            // Create new Sale entry
            $purchase = new PurchaseReturn();
            $purchase->reference = $reference;
            $purchase->date = $request->date;
            $purchase->order_tax = $request->order_tax;
            $purchase->discount = $request->discount;
            $purchase->shipping = $request->shipping;
            $purchase->status = $request->status;
            $purchase->payment_status = "unpaid";
            $purchase->amount_paid = "0.00";
            $purchase->amount_due = $request->grand_total;
            $purchase->details = $request->details;
            $purchase->grand_total = $request->grand_total;
            $purchase->purchase_id = $request->purchase_id;
            $purchase->created_by = auth()->user()->id;
            $purchase->updated_by = auth()->user()->id;
            $purchase->save();

            // Iterate over each product item
            foreach ($request->return_items as $itemData) {
                $purchase_unit = PurchaseProductItem::where('purchase_id', $request->purchase_id)->where('product_id', $itemData['id'])->first();

                $productItem = new PurchaseReturnItem();
                $productItem->purchase_return_id = $purchase->id;
                $productItem->product_id = $itemData['id'];
                $productItem->return_quantity = $itemData['quantityReturn'];
                $productItem->price = $itemData['price'];
                $productItem->subtotal = $itemData['subtotal'];
                $productItem->purchase_unit = $purchase_unit->purchase_unit;
                $productItem->save();

                $product = Product::with('unit', 'purchase_unit')->find($itemData['id']);
                $item = PurchaseProductItem::where('purchase_id', $request->purchase_id)->where('product_id', $itemData['id'])->first();
                $purchases = Purchase::find($request->purchase_id);

                $warehouse_product = ProductWarehouse::where('product_id', $itemData['id'])->where('warehouse_id', $purchases->warehouse_id)->first();

                $finalStock = 0;
                if ($product->product_type != 'service') {

                    if ($product->product_unit != $item->purchase_unit) {
                        $purchase_unit = Unit::find($item->purchase_unit);
                        if ($purchase_unit->parent_id != 0) {
                            $expression = $warehouse_product->quantity . $product->unit->operator . $purchase_unit->operator_value;
                            $convertedStock = eval("return $expression;");
                            $stock = $convertedStock - $itemData['quantityReturn'];
                            $secondExp = $stock . $purchase_unit->operator . $purchase_unit->operator_value;
                            $finalStock = eval("return $secondExp;");
                        }
                    } else {
                        $finalStock =  $warehouse_product->quantity - $itemData['quantityReturn'];
                    }
                } else {
                    $finalStock =  $warehouse_product->quantity - $itemData['quantityReturn'];
                }

                $warehouse_product->update(['quantity' => "$finalStock"]);
            }


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
        $purchase = Purchase::find($id);
        $purchase->load('purchaseItems', 'vendor');

        return view('back.purchase-return.create', compact('purchase'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $purchase_return = PurchaseReturn::find($id);
        $purchase_return->load('return_items', 'purchase');
        // dd($sale_return);
        return view('back.purchase-return.edit', compact('purchase_return'));
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
            $purchase = PurchaseReturn::find($id);
            $purchase->date = $request->date;
            $purchase->order_tax = $request->order_tax;
            $purchase->discount = $request->discount;
            $purchase->shipping = $request->shipping;
            $purchase->status = $request->status;
            $purchase->amount_due = $request->grand_total;
            $purchase->details = $request->details;
            $purchase->grand_total = $request->grand_total;
            $purchase->updated_by = auth()->user()->id;
            $purchase->save();

            // Iterate over each product item
            foreach ($request->return_items as $itemData) {

                $productItem = PurchaseReturnItem::where('purchase_return_id', $id)->where('product_id', $itemData['id'])->first();
                $productItem->load('product', 'purchase_units');
                $product = Product::find($productItem->product->id);
                $purchases = Purchase::find($purchase->purchase_id);
                $warehouse_product = ProductWarehouse::where('product_id', $itemData['id'])->where('warehouse_id', $purchases->warehouse_id)->first();
                // dd($warehouse_product);

                if ($productItem->return_quantity != $itemData['quantityReturn']) {

                    // first remove quantity
                    if ($productItem->product->product_type != 'service') {
                        if ($product->product_unit != $productItem->purchase_unit) {
                            $expression = $warehouse_product->quantity . $product->unit->operator . $productItem->purchase_units->operator_value;
                            $convertedStock = eval("return $expression;");
                            $stock = $convertedStock + $productItem->return_quantity;
                            $secondExp = $stock . $productItem->purchase_units->operator . $productItem->purchase_units->operator_value;
                            $finalStock = eval("return $secondExp;");
                            $warehouse_product->quantity = $finalStock;
                            $warehouse_product->save();
                        } else {
                            $warehouse_product->quantity += $productItem->return_quantity;
                            $warehouse_product->save();
                        }
                    } else {
                        $warehouse_product->quantity += $productItem->return_quantity;
                        $warehouse_product->save();
                    }

                    // update quantity
                    $finalStock = 0;
                    if ($product->product_type != 'service') {

                        if ($product->product_unit != $productItem->purchase_unit) {
                            $purchase_unit = Unit::find($productItem->purchase_unit);
                            if ($purchase_unit->parent_id != 0) {
                                $expression = $warehouse_product->quantity . $product->unit->operator . $purchase_unit->operator_value;
                                $convertedStock = eval("return $expression;");
                                $stock = $convertedStock - $itemData['quantityReturn'];
                                $secondExp = $stock . $purchase_unit->operator . $purchase_unit->operator_value;
                                $finalStock = eval("return $secondExp;");
                            }
                        } else {
                            $finalStock =  $warehouse_product->quantity - $itemData['quantityReturn'];
                        }
                    } else {
                        $finalStock =  $warehouse_product->quantity - $itemData['quantityReturn'];
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
        $purchase_return = PurchaseReturn::find($id);
        if ($purchase_return) {
            $return_items = PurchaseReturnItem::where('purchase_return_id', $id)->get();
            $return_items->load('product', 'purchase_units');
            foreach ($return_items as $return) {

                $product = Product::find($return->product->id);
                $purchases = Purchase::find($purchase_return->purchase_id);
                $warehouse_product = ProductWarehouse::where('product_id', $return->product->id)->where('warehouse_id', $purchases->warehouse_id)->first();

                if ($return->product->product_type != 'service') {
                    if ($product->product_unit != $return['purchase_unit']) {
                        $expression = $warehouse_product->quantity . $product->unit->operator . $return->purchase_units->operator_value;
                        $convertedStock = eval("return $expression;");
                        $stock = $convertedStock + $return['return_quantity'];
                        $secondExp = $stock . $return->purchase_units->operator . $return->purchase_units->operator_value;
                        $finalStock = eval("return $secondExp;");
                        $warehouse_product->quantity = $finalStock;
                        $warehouse_product->save();
                    } else {
                        $warehouse_product->quantity += $return->return_quantity;
                        $warehouse_product->save();
                    }
                } else {
                    $warehouse_product->quantity += $return->return_quantity;
                    $warehouse_product->save();
                }

                $return->delete();
            }

            $purchase_return->delete();
            return redirect()->back()->with('success', 'Purchase return has been deleted successfully!');
        }
    }
    public function deletePurchaseReturns(Request $req)
    {
        if (!empty($req->ids) && is_array($req->ids)) {

            foreach ($req->ids as $id) {
                $purchase_return = PurchaseReturn::find($id);
                if ($purchase_return) {
                    $return_items = PurchaseReturnItem::where('purchase_return_id', $id)->get();
                    $return_items->load('product', 'purchase_units');
                    foreach ($return_items as $return) {
                        // dd($return['return_quantity']);
                        $product = Product::find($return->product->id);
                        $purchases = Purchase::find($purchase_return->purchase_id);
                        $warehouse_product = ProductWarehouse::where('product_id', $return->product->id)->where('warehouse_id', $purchases->warehouse_id)->first();

                        if ($return->product->product_type != 'service') {
                            if ($product->product_unit != $return['purchase_unit']) {
                                $expression = $warehouse_product->quantity . $product->unit->operator . $return->purchase_units->operator_value;
                                $convertedStock = eval("return $expression;");
                                $stock = $convertedStock + $return['return_quantity'];
                                $secondExp = $stock . $return->purchase_units->operator . $return->purchase_units->operator_value;
                                $finalStock = eval("return $secondExp;");
                                $warehouse_product->quantity = $finalStock;
                                $warehouse_product->save();
                            } else {
                                $warehouse_product->quantity += $return->return_quantity;
                                $warehouse_product->save();
                            }
                        } else {
                            $warehouse_product->quantity += $return->return_quantity;
                            $warehouse_product->save();
                        }

                        $return->delete();
                    }

                    $purchase_return->delete();
                }
            }
            return response()->json(['status' => 200, 'message' => 'Purchase return has been deleted successfully!']);
        }
    }

    public function detail($id)
    {
        $purchase_return = PurchaseReturn::find($id);
        $purchase_return->load('return_items.product.unit', 'return_items.product.purchase_unit');

        $purchase = Purchase::find($purchase_return->purchase_id);
        $purchase->load('purchaseItems', 'vendor', 'warehouse', 'invoice.user');

        return view('back.purchase-return.details', compact('purchase_return', 'purchase'));
    }
}
