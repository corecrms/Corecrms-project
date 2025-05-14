<?php

namespace App\Http\Controllers\Admin;

use App\Models\Unit;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Warehouse;
use App\Models\ManualReturn;
use Illuminate\Http\Request;
use App\Models\ManualReturnItem;
use App\Models\ProductWarehouse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\ManualPurchaseReturn;
use App\Models\ManualPurchaseReturnItem;
use App\Models\PurchaseReturn;
use App\Models\Vendor;

class ManualPurchaseReturnController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $returns = ManualPurchaseReturn::all();
        $returns->load('vendor', 'warehouse');
        $warehouses = Warehouse::all();
        $vendors = Vendor::all();
        return view('back.manual-purchase-return.index', compact('returns', 'warehouses', 'vendors'));
    }

    public function filterReturns(Request $req)
    {
        $query = ManualPurchaseReturn::with('warehouse', 'vendor');

        $filters = $req->all();

        if (isset($filters['date'])) {
            $query->where('date', $filters['date']);
        }

        if (isset($filters['reference'])) {
            $query->where('reference', $filters['reference']);
        }

        if (isset($filters['vendor_id']) && $filters['vendor_id'] > 0) {
            $vendor = $filters['vendor_id'];
            $query->where('vendor_id', $vendor);
        }

        if (isset($filters['warehouse_id'])  && $filters['warehouse_id'] > 0) {
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
        $vendors = Vendor::all();
        $warehouses = Warehouse::all();

        return view('back.manual-purchase-return.index', compact('returns', 'vendors', 'warehouses'));
    }


    public function create_sale_return()
    {
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $vendors = Vendor::all();
        $warehouse = Warehouse::all();
        $units = Unit::all();
        return view('back.manual-purchase-return.manual-return', compact('vendors', 'warehouse', 'units'));
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
            $manual_return = new ManualPurchaseReturn();
            $manual_return->reference = $reference;
            $manual_return->date = $request->date;
            $manual_return->vendor_id = $request->vendor_id;
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

                $productItem = new ManualPurchaseReturnItem();
                $productItem->manual_purchase_return_id = $manual_return->id;
                $productItem->product_id = $itemData['id'];
                $productItem->return_quantity = $itemData['quantityReturn'];
                $productItem->price = $itemData['price'];
                $productItem->subtotal = $itemData['subtotal'];
                $productItem->return_unit = $itemData['return_unit'];
                $productItem->save();

                $product = Product::with('unit', 'sale_units')->find($itemData['id']);
                $warehouse_product = ProductWarehouse::where('product_id', $itemData['id'])->where('warehouse_id', $request->warehouse_id)->first();

                $finalStock = 0;
                if ($product->product_type != 'service') {

                    if ($product->product_unit != $itemData['return_unit']) {
                        $return_unit = Unit::find($itemData['return_unit']);
                        if ($return_unit->parent_id != 0) {
                            $expression = $warehouse_product->quantity . $product->unit->operator . $return_unit->operator_value;
                            $convertedStock = eval("return $expression;");
                            $stock = $convertedStock - $itemData['quantityReturn'];
                            $secondExp = $stock . $return_unit->operator . $return_unit->operator_value;
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
        $purchase_return = ManualPurchaseReturn::find($id);
        $purchase_return->load('return_items', 'vendor', 'warehouse');
        // dd($sale_return);
        return view('back.manual-purchase-return.show', compact('purchase_return'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $purchase_return = ManualPurchaseReturn::find($id);
        $purchase_return->load('return_items');
        return view('back.manual-purchase-return.edit', compact('purchase_return'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function update(Request $request, $id)
    // {
    //     // dd($request->all());

    //     try {

    //         DB::beginTransaction();

    //         // Update Sale entry
    //         $manual_return = ManualPurchaseReturn::find($id);
    //         $manual_return->date = $request->date;
    //         $manual_return->order_tax = $request->order_tax;
    //         $manual_return->discount = $request->discount;
    //         $manual_return->shipping = $request->shipping;
    //         $manual_return->status = $request->status;
    //         $manual_return->amount_due = $request->grand_total;
    //         $manual_return->details = $request->details;
    //         $manual_return->grand_total = $request->grand_total;
    //         $manual_return->save();

    //         // Iterate over each product item
    //         foreach ($request->return_items as $itemData) {

    //             $productItem = ManualPurchaseReturnItem::where('manual_purchase_return_id', $id)->where('product_id', $itemData['id'])->first();
    //             $productItem->load('product', 'return_units');

    //             $product = Product::find($productItem->product->id);
    //             $warehouse_product = ProductWarehouse::where('product_id', $itemData['id'])->where('warehouse_id', $manual_return->warehouse_id)->first();
    //             // dd($warehouse_product);
    //             if ($productItem->return_quantity != $itemData['quantityReturn']) {
    //                 // first remove the old quantity

    //                 if ($productItem->product->product_type != 'service') {
    //                     if ($product->product_unit != $productItem->return_unit) {

    //                         $expression = $warehouse_product->quantity . $product->unit->operator . $productItem->return_units->operator_value;
    //                         $convertedStock = eval("return $expression;");
    //                         $stock = $convertedStock + $productItem->return_quantity;
    //                         $secondExp = $stock . $productItem->return_units->operator . $productItem->return_units->operator_value;
    //                         $finalStock = eval("return $secondExp;");

    //                         $warehouse_product->quantity = "$finalStock";
    //                         $warehouse_product->save();
    //                     } else {
    //                         $warehouse_product->quantity += $productItem->return_quantity;
    //                         $warehouse_product->save();
    //                     }
    //                 } else {
    //                     $warehouse_product->quantity += $productItem->return_quantity;
    //                     $warehouse_product->save();
    //                 }

    //                 // update quantity
    //                 $finalStock = 0;
    //                 if ($product->product_type != 'service') {
    //                     if ($product->product_unit != $productItem->return_unit) {
    //                         $return_unit = Unit::find($productItem->return_unit);
    //                         if ($return_unit->parent_id != 0) {
    //                             $expression = $warehouse_product->quantity . $product->unit->operator . $return_unit->operator_value;
    //                             $convertedStock = eval("return $expression;");
    //                             $stock = $convertedStock - $itemData['quantityReturn'];
    //                             $secondExp = $stock . $return_unit->operator . $return_unit->operator_value;
    //                             $finalStock = eval("return $secondExp;");
    //                         }
    //                     } else {
    //                         $finalStock =  $warehouse_product->quantity - $itemData['quantityReturn'];
    //                     }
    //                 } else {
    //                     $finalStock =  $warehouse_product->quantity - $itemData['quantityReturn'];
    //                 }

    //                 $warehouse_product->update(['quantity' => "$finalStock"]);
    //             }

    //             $productItem->return_quantity = $itemData['quantityReturn'];
    //             $productItem->price = $itemData['price'];
    //             $productItem->subtotal = $itemData['subtotal'];
    //             $productItem->save();
    //         }


    //         DB::commit();
    //         return response()->json(['message' => 'Update Return successfully created'], 200);
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return response()->json(['message' => 'Error occurred: ' . $e->getMessage()], 500);
    //     }
    // }

    public function update(Request $request, $id)
    {
        // dd($request->all());
        try {
            DB::beginTransaction();

            // Update Sale entry
            $manual_return = ManualPurchaseReturn::find($id);
            $manual_return->date = $request->date;
            $manual_return->order_tax = $request->order_tax;
            $manual_return->discount = $request->discount;
            $manual_return->shipping = $request->shipping;
            $manual_return->status = $request->status;
            $manual_return->amount_due = $request->grand_total;
            $manual_return->details = $request->details;
            $manual_return->grand_total = $request->grand_total;
            $manual_return->save();

            // Iterate over each product item
            foreach ($request->return_items as $itemData) {
                $productItem = ManualPurchaseReturnItem::where('manual_purchase_return_id', $id)
                    ->where('product_id', $itemData['id'])->first();
                $productItem->load('product', 'return_units');

                $product = Product::find($productItem->product->id);
                $warehouse_product = ProductWarehouse::where('product_id', $itemData['id'])
                    ->where('warehouse_id', $manual_return->warehouse_id)->first();

                // Check if the warehouse has enough stock before proceeding
                $availableStock = $warehouse_product->quantity;
                $requiredStock = $itemData['quantityReturn'] - $productItem->return_quantity;

                if ($requiredStock > 0 && $availableStock < $requiredStock) {
                    // Not enough stock available, rollback and return error
                    DB::rollBack();
                    return response()->json(['message' => 'Error: Not enough stock available for product ID ' . $productItem->product->sku], 400);
                }

                if ($productItem->return_quantity != $itemData['quantityReturn']) {
                    // Remove the old quantity first
                    if ($productItem->product->product_type != 'service') {
                        if ($product->product_unit != $productItem->return_unit) {
                            $expression = $warehouse_product->quantity . $product->unit->operator . $productItem->return_units->operator_value;
                            $convertedStock = eval("return $expression;");
                            $stock = $convertedStock + $productItem->return_quantity;
                            $secondExp = $stock . $productItem->return_units->operator . $productItem->return_units->operator_value;
                            $finalStock = eval("return $secondExp;");
                            $warehouse_product->quantity = "$finalStock";
                            $warehouse_product->save();
                        } else {
                            $warehouse_product->quantity += $productItem->return_quantity;
                            $warehouse_product->save();
                        }
                    } else {
                        $warehouse_product->quantity += $productItem->return_quantity;
                        $warehouse_product->save();
                    }

                    // Update quantity
                    $finalStock = 0;
                    if ($product->product_type != 'service') {
                        if ($product->product_unit != $productItem->return_unit) {
                            $return_unit = Unit::find($productItem->return_unit);
                            if ($return_unit->parent_id != 0) {
                                $expression = $warehouse_product->quantity . $product->unit->operator . $return_unit->operator_value;
                                $convertedStock = eval("return $expression;");
                                $stock = $convertedStock - $itemData['quantityReturn'];
                                $secondExp = $stock . $return_unit->operator . $return_unit->operator_value;
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
        // dd($id);

        $sale_return = ManualPurchaseReturn::find($id);
        if ($sale_return) {
            $return_items = ManualPurchaseReturnItem::where('manual_purchase_return_id', $id)->get();
            $return_items->load('product', 'return_units');
            foreach ($return_items as $return) {
                $product = Product::find($return->product->id);
                $warehouse_product = ProductWarehouse::where('product_id', $return->product->id)->where('warehouse_id', $sale_return->warehouse_id)->first();
                // dd($warehouse_product);
                if ($return->product->product_type != 'service') {
                    if ($product->product_unit != $return['return_unit']) {
                        $expression = $warehouse_product->quantity . $product->unit->operator . $return->return_units->operator_value;
                        $convertedStock = eval("return $expression;");
                        $stock = $convertedStock + $return['return_quantity'];
                        $secondExp = $stock . $return->return_units->operator . $return->return_units->operator_value;
                        $finalStock = eval("return $secondExp;");
                    } else {
                        $finalStock = $warehouse_product->quantity + $return['return_quantity'];
                    }
                    
                } else {

                    $finalStock = $warehouse_product->quantity + $return['return_quantity'];
                }
                $warehouse_product->update(['quantity' => "$finalStock"]);
                $return->delete();
            }

            $sale_return->delete();
            return redirect()->back()->with('success', 'Purchase return has been deleted successfully!');
        }
    }


    public function deleteReturns(Request $req)
    {

        foreach ($req->ids as $key => $id) {
            # code...
            $sale_return = ManualPurchaseReturn::find($id);
            if ($sale_return) {
                $return_items = ManualPurchaseReturnItem::where('manual_purchase_return_id', $id)->get();
                $return_items->load('product', 'return_units');
                foreach ($return_items as $return) {
                    $product = Product::find($return->product->id);
                    $warehouse_product = ProductWarehouse::where('product_id', $return->product->id)->where('warehouse_id', $sale_return->warehouse_id)->first();

                    if ($return->product->product_type != 'service') {
                        if ($product->product_unit != $return['return_unit']) {
                            $expression = $warehouse_product->quantity . $product->unit->operator . $return->return_units->operator_value;
                            $convertedStock = eval("return $expression;");
                            $stock = $convertedStock + $return['return_quantity'];
                            $secondExp = $stock . $return->return_units->operator . $return->return_units->operator_value;
                            $finalStock = eval("return $secondExp;");
                        } else {
                            $finalStock = $warehouse_product->quantity + $return['return_quantity'];
                        }
                    } else {
                        $finalStock = $warehouse_product->quantity + $return['return_quantity'];
                    }
                    $warehouse_product->update(['quantity' => "$finalStock"]);
                    $return->delete();
                }

                $sale_return->delete();
            }
        }

        return response()->json(['status' => 200, 'message' => 'Purchase Return Deleted Successfully!']);
    }
}
