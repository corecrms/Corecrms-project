<?php

namespace App\Http\Controllers\Admin;

use App\Models\Unit;
use App\Models\User;
use App\Models\Vendor;
use App\Models\Account;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use App\Models\PurchaseInvoice;
use App\Models\ProductWarehouse;
use Illuminate\Support\Facades\DB;
use App\Models\PurchaseProductItem;
use App\Http\Controllers\BaseController;
use App\Http\Resources\PurchaseResource;
use App\Http\Requests\PurchaseStoreRequest;
use App\Http\Requests\PurchaseUpdateRequest;

class PurchaseController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     function __construct()
     {
         $this->middleware('permission:purchase-list|purchase-create|purchase-edit|purchase-delete|sale-show
          ', ['only' => ['index', 'show']]);
         $this->middleware('permission:purchase-create', ['only' => ['create', 'store']]);
         $this->middleware('permission:purchase-edit', ['only' => ['edit', 'update']]);
         $this->middleware('permission:purchase-delete', ['only' => ['destroy']]);
         $this->middleware('permission:purchase-show', ['only' => ['show']]);
     }

    public function index(Request $req)
    {
        return $this->handleException(function () use ($req) {

            if ($req->filled('search')) {

                $searchTerm = $req->search;


                $purchases  = Purchase::search($searchTerm)->get();
                $users = User::search($searchTerm)->get();
                $users->load('vendor.purchases', 'warehouse.purchases');

                $users = $users ? $users : collect();

                $purchaseByVendor = collect();
                $purchaseByWarehouse = collect();
                foreach ($users as $user) {
                    if ($user->vendor) {
                        foreach ($user->vendor->purchases as $purchase) {
                            $purchaseByVendor->push($purchase);
                        }
                    }
                    if ($user->warehouse) {
                        foreach ($user->warehouse->purchases as $purchase) {
                            $purchaseByWarehouse->push($purchase);
                        }
                    }
                }

                $mergedPurchases = $purchases->merge($purchaseByVendor)->merge($purchaseByWarehouse)->unique('id');
                $purchases = $mergedPurchases->load('vendor', 'warehouse');
                // dd($sales);
                $suppliers = Vendor::all();
                $warehouses = Warehouse::all();
                return view('back.purchases.index', compact('purchases', 'suppliers', 'warehouses'));
            } else if (auth()->user()->hasRole(['Cashier', 'Manager'])) {
                // $purchases = PurchaseResource::collection(Purchase::where('warehouse', auth()->user()->warehouse_id)->get());
                $purchases = Purchase::where('warehouse_id', auth()->user()->warehouse_id)->get();
                $suppliers = Vendor::all();
                $warehouses = Warehouse::all();
                return view('back.purchases.index', compact('purchases', 'suppliers', 'warehouses'));
            }
            else
            {
                if (session()->has('selected_warehouse_id') && auth()->user()->hasRole('Admin')) {
                    $warehouseId = session()->get('selected_warehouse_id');
                    $purchases = PurchaseResource::collection(Purchase::where('warehouse_id', $warehouseId)->get());
                } else {
                    $purchases = PurchaseResource::collection(Purchase::all());
                }
                $suppliers = Vendor::all();
                $warehouses = Warehouse::all();
                return view('back.purchases.index', compact('purchases', 'suppliers', 'warehouses'));
            }
        });
    }

    public function filterPurchase(Request $req)
    {
        $query = Purchase::with('vendor', 'warehouse');

        $filters = $req->all();

        if (isset($filters['date'])) {
            $query->where('date', $filters['date']);
        }

        if (isset($filters['reference'])) {
            $query->where('reference', $filters['reference']);
        }

        if (isset($filters['vendor_id']) && $filters['vendor_id'] > 0) {
            $query->where('vendor_id', $filters['vendor_id']);
        }

        if (isset($filters['warehouse_id'])  && $filters['warehouse_id'] > 0) {
            $query->where('warehouse_id', $filters['warehouse_id']);
        }

        if (isset($filters['status'])  && $filters['status'] > 0) {
            $query->where('status', $filters['status']);
        }
        if (isset($filters['payment_status'])  && $filters['payment_status'] > 0) {
            $query->where('payment_status', $filters['payment_status']);
        }


        $purchases = $query->get();
        $suppliers = Vendor::all();
        $warehouses = Warehouse::all();

        return view('back.purchases.index', compact('purchases', 'suppliers', 'warehouses'));
    }

    public function purchaseSearch(Request $req)
    {
        if (!$req->query) {
            return response()->json(['success' => false]);
        }
        $searchTerm = $req->input('query');
        $purchases  = Purchase::search($searchTerm)->get();
        $users = User::search($searchTerm)->get();
        $users->load('vendor.purchases', 'warehouse.purchases');
        // return $users->customer->sales;
        $users = $users ? $users : collect();
        // return $users;
        $purchaseByVendor = collect();
        $purchaseByWarehouse = collect();
        foreach ($users as $user) {
            if ($user->vendor) {
                foreach ($user->vendor->purchases as $purchase) {
                    $purchaseByVendor->push($purchase);
                }
            }
            if ($user->warehouse) {
                foreach ($user->warehouse->purchases as $purchase) {
                    $purchaseByWarehouse->push($purchase);
                }
            }
        }

        $mergedPurchases = $purchases->merge($purchaseByVendor)->merge($purchaseByWarehouse)->unique('id');

        if ($mergedPurchases) {
            return response()->json(['success' => true, 'purchases' => $mergedPurchases]);
        } else {
            return response()->json(['success' => false]);
        }
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return $this->handleException(function () {

            $purchases = PurchaseResource::collection(Purchase::where('warehouse_id', auth()->user()->warehouse_id)->get());
            $vendors = Vendor::where('blacklist','!=',1)->get();

            $products = Product::all();
            $units = Unit::all();
            $warehouses  = Warehouse::all();
            $bank_accounts = Account::all();
            $payments = PaymentMethod::all();


            return view('back.purchases.create', compact('purchases', 'vendors', 'products', 'units', 'warehouses', 'bank_accounts', 'payments'));
        });
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PurchaseStoreRequest $request)
    {

        $data = $request->validated();
        // dd($request->all());
        // dd($data);
        try {
            DB::beginTransaction();

            // Generate a unique reference for the purchase
            $reference = substr(uniqid(), 0, 5);
            // append 'PUR-' to the reference
            $data['reference'] = 'PUR-' . $reference;
            $data['ntn'] = $data['ntn_no'] ?? null;
            $data['amount_recieved'] = $data['amount_received'];
            $data['warehouse_id'] = $request->warehouse_id;
            $data['bank_account'] = $request->bank_account;
            // $data['amount_due'] = $data['amount_due'];
            $data['created_by'] = $data['updated_by'] = auth()->id();
            $purchase = Purchase::create($data);
            if ($purchase->save()) {

                // Iterate over each product item
                foreach ($request->order_items as $itemData) {
                    $productItem = new PurchaseProductItem();
                    $productItem->purchase_id = $purchase->id;
                    $productItem->product_id = $itemData['id'];
                    $productItem->quantity = $itemData['quantity'];
                    $productItem->discount = $itemData['discount'];
                    $productItem->order_tax = $itemData['order_tax'];
                    $productItem->tax_type = $itemData['tax_type'];
                    $productItem->discount_type = $itemData['discount_type'] ?? null;
                    $productItem->price = $itemData['price'];
                    $productItem->sub_total = $itemData['subtotal'];
                    $productItem->stock = $itemData['stock'];
                    $productItem->purchase_unit = $itemData['purchase_unit'];
                    //bank_account
                    $productItem->save();


                    $product = Product::with('unit', 'purchase_unit')->find($itemData['id']);
                    $warehouse_product = ProductWarehouse::where('product_id', $itemData['id'])->where('warehouse_id', $request->warehouse_id)->first();
                    // dd($warehouse_product);

                    $finalStock = 0;
                    if ($product->product_type != 'service') {
                        $convertedUnit = Unit::find($itemData['purchase_unit']);
                        if ($product->product_unit != $itemData['purchase_unit']) {
                            if ($product->unit->parent_id == 0) {
                                $expression = $warehouse_product->quantity . $product->unit->operator . $convertedUnit->operator_value;
                                $convertedStock = eval("return $expression;");
                                $stock = $convertedStock + $itemData['quantity'];
                                $secondExp = $stock . $convertedUnit->operator . $convertedUnit->operator_value;
                                $finalStock = eval("return $secondExp;");
                            }
                        } else {
                            $finalStock = $warehouse_product->quantity + $itemData['quantity'];
                        }
                    } else {
                        $finalStock = $warehouse_product->quantity + $itemData['quantity'];
                    }

                    $warehouse_product->update(['quantity' => "$finalStock"]);
                }

                $invoice =  new PurchaseInvoice();
                $invoice->invoice_number = $request->invoice_id;
                $invoice->vendor_id = $purchase->vendor_id;
                $invoice->purchase_id = $purchase->id;

                $invoice->created_by = $invoice->updated_by = auth()->user()->id;
                $invoice->order_date = date('Y-m-d');
                $invoice->delivery_date = date('Y-m-d');
                $invoice->save();
            }


            DB::commit();
            return response()->json(['message' => 'Purchase successfully created', 'reference' => $reference], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error occurred: ' . $e->getMessage()], 500);
        }
        // $data['created_by'] = $data['updated_by'] = auth()->user()->id;

        // Purchase::create($data);

        return redirect()->route('purchases.index')
            ->with('success', 'Purchase created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $purchase = Purchase::find($id);
        $purchase->load('purchaseItems', 'vendor', 'warehouse', 'invoice.user');
        // dd($purchase);
        // foreach($sale->productItems as $product){
        // dd($product);
        // }
        return view('back.purchases.purchase-detail', compact('purchase'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function edit(Purchase $purchase)
    {
        return $this->handleException(function () use ($purchase) {

            // $purchase = new PurchaseResource($purchase);
            $purchase->load('purchaseItems.product.unit', 'purchaseItems.product.sale_units', 'vendor');
            $vendors = Vendor::where('blacklist','!=',1)->get();
            $products = Product::all();
            $units = Unit::all();
            $warehouses = Warehouse::all();
            $bank_accounts = Account::all();
            $payments = PaymentMethod::all();

            return view('back.purchases.edit', compact('purchase', 'vendors', 'products', 'units', 'warehouses', 'bank_accounts', 'payments'));
        });
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function update(PurchaseUpdateRequest $request, Purchase $purchase)
    {
        // return $request->order_items;

        $data = $request->validated();
        $data['ntn'] = $data['ntn_no'] ?? null;
        $data['amount_recieved'] = $data['amount_received'];
        $data['warehouse_id'] = $request->warehouse_id;
        $data['amount_due'] = $data['grand_total'] - $data['amount_received'];
        $data['bank_account'] = $request->bank_account;
        $data['updated_by'] = auth()->user()->id;

        $purchase->update($data);
        $productItem = PurchaseProductItem::where('purchase_id', $purchase->id)->get();
        $productItem->load('purchase_units', 'product.unit');

        // return $productItem;
        foreach ($productItem as $product) {
            $warehouse_product = ProductWarehouse::where('product_id', $product->product->id)->where('warehouse_id', $request->warehouse_id)->first();
            $finalStock = 0;
            if ($product->product->product_type != 'service') {
                if ($product->product->product_unit != $product->purchase_units->id) {
                    $expression = $warehouse_product->quantity . $product->product->unit->operator . $product->purchase_units->operator_value;
                    $convertedStock = eval("return $expression;");
                    $stock = $convertedStock - $product->quantity;
                    $secondExp = $stock . $product->purchase_units->operator . $product->purchase_units->operator_value;
                    $finalStock = eval("return $secondExp;");
                } else {
                    $finalStock = $warehouse_product->quantity - $product->quantity;
                    // return "$finalStock";
                }
            } else {
                $finalStock = $warehouse_product->quantity - $product->quantity;
            }
            // $productForUpdate = Product::find($product->product->id);
            // $productForUpdate->update(['quantity' => "$finalStock"]);
            $warehouse_product->update(['quantity' => $finalStock]);
            // return $productForUpdate;
            $product->delete();
        }

        //  // Iterate over each product item
        foreach ($request->order_items as $itemData) {

            PurchaseProductItem::create([
                'purchase_id' => $purchase->id,
                'product_id' => $itemData['id'],
                'quantity' => $itemData['quantity'],
                'discount' => $itemData['discount'],
                'order_tax' => $itemData['order_tax'],
                'tax_type' => $itemData['tax_type'],
                'discount_type' => $itemData['discount_type'] ?? null,
                'price' => $itemData['price'],
                'sub_total' => $itemData['subtotal'],
                'purchase_unit' => $itemData['purchase_unit'],
                'stock' => $itemData['stock'],
            ]);

            // Updating Stock
            $product = Product::with('unit', 'purchase_unit')->find($itemData['id']);
            $warehouse_product = ProductWarehouse::where('product_id', $itemData['id'])->where('warehouse_id', $request->warehouse_id)->first();
            $finalStock = 0;
            if ($product->product_type != 'service') {
                $convertedUnit = Unit::find($itemData['purchase_unit']);
                if ($product->product_unit != $itemData['purchase_unit']) {
                    if ($product->unit->parent_id == 0) {
                        $expression = $warehouse_product->quantity . $product->unit->operator . $convertedUnit->operator_value;
                        $convertedStock = eval("return $expression;");
                        $stock = $convertedStock + $itemData['quantity'];
                        $secondExp = $stock . $convertedUnit->operator . $convertedUnit->operator_value;
                        $finalStock = eval("return $secondExp;");
                    }
                } else {
                    $finalStock = $warehouse_product->quantity + $itemData['quantity'];
                }
            } else {
                $finalStock = $warehouse_product->quantity + $itemData['quantity'];
            }

            $warehouse_product->update(['quantity' => "$finalStock"]);
        }

        return response()->json(['message' => 'Purchase updated successfully'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $purchase = Purchase::find($id);
        if ($purchase) {

            // SaleInvoice::where('sale_id', $id)->first()->delete();
            $productItem = PurchaseProductItem::where('purchase_id', $id)->get();
            $productItem->load('purchase_units', 'product.unit');
            $invoice = PurchaseInvoice::where('purchase_id', $id)->first();
            foreach ($productItem as $product) {
                $warehouse_product = ProductWarehouse::where('product_id', $product->product->id)->where('warehouse_id', $purchase->warehouse_id)->first();

                $finalStock = 0;
                if ($product->product->product_type != 'service') {
                    if ($product->product->product_unit != $product->purchase_units->id) {
                        $expression = $warehouse_product->quantity . $product->product->unit->operator . $product->purchase_units->operator_value;
                        $convertedStock = eval("return $expression;");
                        $stock = $convertedStock - $product->quantity;
                        $secondExp = $stock . $product->purchase_units->operator . $product->purchase_units->operator_value;
                        $finalStock = eval("return $secondExp;");
                    } else {
                        $finalStock = $warehouse_product->quantity - $product->quantity;
                    }
                } else {
                    $finalStock = $warehouse_product->quantity - $product->quantity;
                }
                // $productForUpdate = Product::find($product->product->id);
                // $productForUpdate->update(['quantity' => "$finalStock"]);
                $warehouse_product->update(['quantity' => "$finalStock"]);
                $product->delete();
            }

            $invoice->delete();
            $purchase->delete();
            return redirect()->back()->with('success', 'Purchase Deleted Successfully!');
        }
    }
    public function deletePurchases(Request $req)
    {

        if (!empty($req->ids) && is_array($req->ids)) {
            // dd($req->all());
            foreach ($req->ids as $id) {
                $purchase = Purchase::find($id);
                if ($purchase) {

                    // SaleInvoice::where('sale_id', $id)->first()->delete();
                    $productItem = PurchaseProductItem::where('purchase_id', $id)->get();
                    $productItem->load('purchase_units', 'product.unit');
                    $invoice = PurchaseInvoice::where('purchase_id', $id)->first();
                    foreach ($productItem as $product) {
                        $warehouse_product = ProductWarehouse::where('product_id', $product->product->id)->where('warehouse_id', $purchase->warehouse_id)->first();

                        $finalStock = 0;
                        if ($product->product->product_type != 'service') {
                            if ($product->product->product_unit != $product->purchase_units->id) {
                                $expression = $warehouse_product->quantity . $product->product->unit->operator . $product->purchase_units->operator_value;
                                $convertedStock = eval("return $expression;");
                                $stock = $convertedStock - $product->quantity;
                                $secondExp = $stock . $product->purchase_units->operator . $product->purchase_units->operator_value;
                                $finalStock = eval("return $secondExp;");
                            } else {
                                $finalStock = $warehouse_product->quantity - $product->quantity;
                            }
                        } else {
                            $finalStock = $warehouse_product->quantity - $product->quantity;
                        }
                        // $productForUpdate = Product::find($product->product->id);
                        // $productForUpdate->update(['quantity' => "$finalStock"]);
                        $warehouse_product->update(['quantity' => "$finalStock"]);
                        $product->delete();
                    }

                    $invoice->delete();
                    $purchase->delete();
                }
            }
            return response()->json(['status' => 200, 'message' => 'Purchase Deleted Successfully!']);
        }
    }
}
