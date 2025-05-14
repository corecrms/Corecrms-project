<?php

namespace App\Http\Controllers\Admin;

use Log;
use Carbon\Carbon;
use App\Models\Product;
use App\Models\Inventory;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\ProductInventory;
use App\Models\ProductWarehouse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\BaseController;

class InventoryController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    function __construct()
    {
        $this->middleware('permission:inventory-list|inventory-create|inventory-edit|inventory-delete|inventory-show
          ', ['only' => ['index', 'show']]);
        $this->middleware('permission:inventory-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:inventory-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:inventory-delete', ['only' => ['destroy']]);
        $this->middleware('permission:inventory-show', ['only' => ['show']]);
    }

    public function index()
    {

        if (auth()->user()->hasRole(['Manager'])) {
            $inventories = Inventory::with('warehouse.users')->with('product_inventory.products')
                ->whereHas('warehouse', function ($q) {
                    $q->where('warehouse_id', auth()->user()->warehouse_id);
                })->get();
        } else {
            if (session()->has('selected_warehouse_id') && auth()->user()->hasRole('Admin')) {
                $warehouseId = session()->get('selected_warehouse_id');
                $inventories = Inventory::with('warehouse.users')->with('product_inventory.products')
                    ->whereHas('warehouse', function ($q) use ($warehouseId) {
                        $q->where('warehouse_id', $warehouseId);
                    })->get();

            } else {
                $inventories = Inventory::with('warehouse.users')->with('product_inventory.products')->get();
            }

        }

        $warehouses = Warehouse::all();
        return view('back.inventory.index', compact('inventories', 'warehouses'));
    }

    public function filterInventory(Request $req)
    {

        $query = Inventory::with('warehouse.users')->with('product_inventory.products');

        $filters = $req->all();

        if (isset($filters['date'])) {
            $query->where('date', $filters['date']);
        }

        if (isset($filters['reference'])) {
            $query->where('reference', $filters['reference']);
        }

        if (isset($filters['warehouse_id'])  && $filters['warehouse_id'] > 0) {
            // $query->where('warehouse_id', $filters['warehouse_id']);
            $warehouse = $filters['warehouse_id'];
            $query->whereHas('warehouse', function ($query) use ($warehouse) {
                $query->where('warehouse_id', $warehouse);
            });
        }

        $inventories = $query->get();
        $warehouses = Warehouse::all();
        return view('back.inventory.index', compact('inventories', 'warehouses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $warehouse = Warehouse::with('users')->get();
        $products = Product::with('barcodes')->get();
        return view('back.inventory.create', compact('warehouse', 'products'));
    }

    public function checkingInventory(Request $req)
    {

        $product = Product::where('sku', $req->sku)->first();

        if ($product) {

            if ($req->select_type == 'subtraction') {
                if ($product->quantity < $req->quantity) {
                    return response()->json(['status' => 'Quantity exceeds']);
                }
            }
        }
    }

    public function stockCountIndex()
    {
        if (auth()->user()->hasRole(['Cashier', 'Manager'])) {
            $warehouses = Warehouse::with('products', 'users')
                ->where('id', auth()->user()->warehouse_id)
                ->get();
        }
        else
        {

            if (session()->has('selected_warehouse_id') && auth()->user()->hasRole('Admin')) {
                $warehouseId = session()->get('selected_warehouse_id');
                $warehouses = Warehouse::with('products', 'users')
                    ->where('id', $warehouseId)
                    ->get();
            }
            else
            {
                $warehouses = Warehouse::with('products', 'users')->get();
            }

        }

        return view('back.inventory.inventoryStockCount', compact('warehouses'));
    }

    public function filterStockCount($date)
    {

        if (auth()->user()->hasRole(['Cashier', 'Manager'])) {
            $warehouses = Warehouse::with('products', 'users')
                ->where('id', auth()->user()->warehouse_id)
                ->whereDate('created_at', $date)
                ->get();
        } else {
            $warehouses = Warehouse::with('products', 'users')->whereDate('created_at', $date)->get();
        }


        if ($warehouses->isEmpty()) {
            return response()->json(['message' => 'No warehouses found for the given date.', 'status' => 404]);
        }

        // Format the created_at date
        foreach ($warehouses as $warehouse) {
            $warehouse->created_at_formatted = Carbon::parse($warehouse->created_at)->format('d-F-Y');
        }

        return response()->json(['data' => $warehouses, 'status' => 200]);
    }

    public function downloadStockPdf($warehouse_id)
    {
        
        $warehouse = Warehouse::with('products.unit', 'users', 'productWarehouses')->find($warehouse_id);
        
        $pdf = Pdf::loadView('back.inventory.download-stock-pdf', ['warehouse' => $warehouse]);
        return $pdf->download($warehouse->users->name . ' Inventory Stock Count' . '.pdf');
    }


    public function deleteProductInventory($inventory_id, $product_id)
    {
        $productInventory = ProductInventory::where('inventory_id', $inventory_id)->where('product_id', $product_id)->first();
        if ($productInventory) {
            $updateProduct = Product::find($product_id);
            $inventory = Inventory::find($inventory_id);
            if ($productInventory->type == "addition") {
                $updateProduct->quantity -= $productInventory->qty;
            } else {
                $updateProduct->quantity += $productInventory->qty;
            }
            $updateProduct->save();
            $inventory->total_products -= 1;
            $inventory->save();
            $productInventory->delete();
            return response()->json(['status' => true]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    // public function store(Request $req)
    // {
    //     // dd($req->all());

    //     $req->validate([
    //         'date' => 'required',
    //         'warehouse_id' => 'required',
    //         'products' => 'required',
    //     ]);
    //     // // Generate a unique reference for the inventory
    //     $reference = substr(uniqid(), 0, 5);
    //     // // append 'SAL-' to the reference
    //     $reference = 'INV-' . $reference;
    //     $inventory = new Inventory();
    //     $inventory->date = $req->date;
    //     $inventory->warehouse_id = $req->warehouse_id;
    //     $inventory->reference = $reference;
    //     $inventory->total_products = count($req->products);
    //     $inventory->notes = $req->note;
    //     $inventory->created_by = auth()->id();
    //     $inventory->save();

    //     if ($req->products) {
    //         foreach ($req->products as $product) {

    //             $updateProduct = Product::find($product['product_id']);
    //             $warehouse_product = ProductWarehouse::where('product_id', $product['product_id'])->where('warehouse_id', $req->warehouse_id)->first();

    //             // Check if the product type is addition
    //             if ($product['type'] == 'addition') {
    //                 // Add the quantity to the existing quantity of the product
    //                 $warehouse_product->quantity += $product['quantity'];
    //             } elseif ($product['type'] == 'subtraction') {
    //                 // Check if the product has enough quantity for subtraction
    //                 if ($warehouse_product->quantity >= $product['quantity']) {
    //                     // Subtract the quantity from the existing quantity of the product
    //                     $warehouse_product->quantity -= $product['quantity'];
    //                 } else {
    //                     continue;
    //                 }
    //             }
    //             // Save the updated product
    //             $warehouse_product->save();

    //             $productInventory = new ProductInventory();
    //             $productInventory->inventory_id = $inventory->id;
    //             $productInventory->product_id = $product['product_id'];
    //             $productInventory->qty = $product['quantity'];
    //             $productInventory->type = $product['type'];
    //             $productInventory->save();
    //         }
    //     }

    //     return response()->json(['success' => 'Inventory created successfully!']);
    // }




    public function store(Request $req)
    {
        // dd($req->all());

        $req->validate([
            'date' => 'required',
            'warehouse_id' => 'required',
            'products' => 'required',
        ]);

        DB::beginTransaction();

        try {
            // Generate a unique reference for the inventory
            $reference = substr(uniqid(), 0, 5);
            // append 'INV-' to the reference
            $reference = 'INV-' . $reference;

            $inventory = new Inventory();
            $inventory->date = $req->date;
            $inventory->warehouse_id = $req->warehouse_id;
            $inventory->reference = $reference;
            $inventory->total_products = count($req->products);
            $inventory->notes = $req->note;
            $inventory->created_by = auth()->id();
            $inventory->save();

            if ($req->products) {
                foreach ($req->products as $product) {
                    $updateProduct = Product::find($product['product_id']);
                    $warehouse_product = ProductWarehouse::where('product_id', $product['product_id'])
                        ->where('warehouse_id', $req->warehouse_id)
                        ->first();

                    // Check if the product type is addition
                    if ($product['type'] == 'addition') {
                        // Add the quantity to the existing quantity of the product
                        $warehouse_product->quantity += $product['quantity'];
                    } elseif ($product['type'] == 'subtraction') {
                        // Check if the product has enough quantity for subtraction
                        if ($warehouse_product->quantity >= $product['quantity']) {
                            // Subtract the quantity from the existing quantity of the product
                            $warehouse_product->quantity -= $product['quantity'];
                        } else {
                            continue;
                        }
                    }
                    // Save the updated product
                    $warehouse_product->save();

                    $productInventory = new ProductInventory();
                    $productInventory->inventory_id = $inventory->id;
                    $productInventory->product_id = $product['product_id'];
                    $productInventory->qty = $product['quantity'];
                    $productInventory->type = $product['type'];
                    $productInventory->save();
                }
            }

            DB::commit();

            return response()->json(['success' => 'Inventory created successfully!']);
        } catch (\Exception $e) {
            DB::rollBack();

            // Log the error for debugging
            Log::error('Error creating inventory: ' . $e->getMessage());

            return response()->json(['error' => 'Failed to create inventory. Please try again later.'], 500);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Inventory  $inventory
     * @return \Illuminate\Http\Response
     */
    public function show(Inventory $inventory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Inventory  $inventory
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $warehouse = Warehouse::with('users')->get();
        $products = Product::with('barcodes')->get();
        $inventory = Inventory::with('warehouse.users', 'product_inventory.products.unit')->find($id);

        // dd($inventory);
        // return $inventory;

        return view('back.inventory.edit', compact('warehouse', 'products', 'inventory'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Inventory  $inventory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $req, $id)
    {
        $req->validate([
            'date' => 'required',
            'products' => 'required',
        ]);

        $inventory = Inventory::find($id);
        $inventory->date = $req->date;
        $inventory->total_products = count($req->products);
        $inventory->notes = $req->note;
        $inventory->updated_by = auth()->id();
        $inventory->save();
        if ($req->products) {

            foreach ($req->products as $product) {

                $updateProduct = Product::find($product['product_id']);
                $warehouse_product = ProductWarehouse::where('product_id', $product['product_id'])->where('warehouse_id', $inventory->warehouse_id)->first();


                $productInventory = ProductInventory::where('inventory_id', $id)->where('product_id', $product['product_id'])->first();
                if ($productInventory) {

                    $quantityDifference = abs($productInventory->qty - $product['quantity']);
                    if ($product['type'] == 'addition') {

                        if ($productInventory->type == 'addition') {

                            if ($productInventory->qty != $product['quantity']) {

                                if ($productInventory->qty > $product['quantity']) {
                                    $warehouse_product->quantity -= $quantityDifference;
                                } else {
                                    $warehouse_product->quantity += $quantityDifference;
                                }
                            }
                        } else {
                            $warehouse_product->quantity += $product['quantity'];
                        }
                    } else {
                        if ($productInventory->type == 'subtraction') {

                            if ($productInventory->qty != $product['quantity']) {

                                if ($productInventory->qty > $product['quantity']) {
                                    $warehouse_product->quantity += $quantityDifference;
                                } else {
                                    $warehouse_product->quantity -= $quantityDifference;
                                }
                            }
                        } else {
                            $warehouse_product->quantity -= $product['quantity'];
                        }
                    }

                    // Save the updated product inventory
                    $warehouse_product->save();


                    $productInventory->qty = $product['quantity'];
                    $productInventory->type = $product['type'];
                    $productInventory->save();
                } else {
                    if ($product['type'] == 'addition') {
                        $warehouse_product->quantity += $product['quantity'];
                    } elseif ($product['type'] == 'subtraction') {
                        if ($warehouse_product->quantity >= $product['quantity']) {
                            $warehouse_product->quantity -= $product['quantity'];
                        } else {
                            continue;
                        }
                    }
                    // Save the updated product
                    $warehouse_product->save();

                    $createInventory = new ProductInventory();
                    $createInventory->product_id = $product['product_id'];
                    $createInventory->inventory_id = $id;
                    $createInventory->qty = $product['quantity'];
                    $createInventory->type = $product['type'];
                    $createInventory->save();
                }
            }
        }

        return response()->json(['success' => 'Inventory updated successfully!']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Inventory  $inventory
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $inventory = Inventory::find($id);

        if ($inventory) {
            $inventory_products = ProductInventory::where('inventory_id', $id)->get();

            if ($inventory_products) {
                foreach ($inventory_products as $product) {
                    // return $product;
                    $updateProduct = Product::find($product->product_id);
                    $warehouse_product = ProductWarehouse::where('product_id', $product->product_id)->where('warehouse_id', $inventory->warehouse_id)->first();

                    // Check if the product type is addition
                    if ($product->type == 'addition') {
                        // substract the quantity to the existing quantity of the product
                        $warehouse_product->quantity -= $product->qty;
                    } elseif ($product->type == 'subtraction') {
                        // Add the quantity to the existing quantity of the product
                        $warehouse_product->quantity += $product->qty;
                    }
                    // Save the updated product
                    $warehouse_product->save();

                    $product->delete();
                }
            }
            $inventory->delete();
            return redirect()->back()->with('success', 'Record has been deleted successfully!');
        }
    }

    public function deleteInventories(Request $req)
    {

        if (!empty($req->ids) && is_array($req->ids)) {
            // dd($req->all());
            foreach ($req->ids as $id) {
                $inventory = Inventory::find($id);

                if ($inventory) {
                    $inventory_products = ProductInventory::where('inventory_id', $id)->get();

                    if ($inventory_products) {
                        foreach ($inventory_products as $product) {
                            // return $product;
                            $updateProduct = Product::find($product->product_id);
                            $warehouse_product = ProductWarehouse::where('product_id', $product->product_id)->where('warehouse_id', $inventory->warehouse_id)->first();

                            // Check if the product type is addition
                            if ($product->type == 'addition') {
                                // substract the quantity to the existing quantity of the product
                                $warehouse_product->quantity -= $product->qty;
                            } elseif ($product->type == 'subtraction') {
                                // Add the quantity to the existing quantity of the product
                                $warehouse_product->quantity += $product->qty;
                            }
                            // Save the updated product
                            $warehouse_product->save();

                            $product->delete();
                        }
                    }
                    $inventory->delete();
                }
            }
            return response()->json(['status' => 200, 'message' => 'Inventory deleted successfully!']);
        }
    }



    public function stockCountOfAllProductsIndex()
    {
        if (auth()->user()->hasRole(['Cashier', 'Manager'])) {
            $products = Product::with('category', 'unit', 'images', 'product_warehouses')
                ->whereHas('product_warehouses', function ($q) {
                    $q->where('warehouse_id', auth()->user()->warehouse_id);
                })->get();

            return view('back.inventory.stock-count-by-product', compact('products'));
        }
        else {
            
            $selected_warehouse_id = session('selected_warehouse_id');
            if ($selected_warehouse_id) {
                $products = Product::with('category', 'unit', 'images', 'product_warehouses')->whereHas('product_warehouses', function ($query) use ($selected_warehouse_id) {
                    $query->where('warehouse_id', $selected_warehouse_id);
                })->get();
            } else {
                $products = Product::with('category', 'unit', 'images', 'product_warehouses')->get();
                // dd($products);
            }

            return view('back.inventory.stock-count-by-product', compact('products'));
        }
    }
}
