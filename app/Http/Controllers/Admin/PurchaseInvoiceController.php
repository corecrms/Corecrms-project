<?php

namespace App\Http\Controllers\Admin;

use App\Models\PurchaseInvoice;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use App\Http\Resources\PurchaseInvoiceResource;
use App\Http\Resources\VendorResource;
use App\Http\Resources\CustomerResource;
use App\Http\Resources\ProductResource;
use App\Models\Vendor;
use App\Models\Customer;
use App\Models\Product;

class PurchaseInvoiceController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->handleException(function () {
            // $purchaseInvoices = PurchaseInvoiceResource::collection(PurchaseInvoice::all());
            // if(auth()->user()->hasRole(['Cashier', 'Manager'])){
            //     $purchaseInvoices = PurchaseInvoice::whereHas('purchase', function($q){

            //     })->get();
            // }
            // $purchaseInvoices = PurchaseInvoice::all();

            // dd($purchaseInvoices);
            return view('back.purchase-invoices.index', compact('purchaseInvoices'));
        });
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return $this->handleException(function () {

            $vendors = VendorResource::collection(Vendor::all());
            // $customers = CustomerResource::collection(Customer::all());
            $products = ProductResource::collection(Product::all());

            return view('back.purchase-invoices.create', compact('vendors', 'products'));
        });
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
        $data = $request->validate([
            'vendor_id' => 'required|integer|exists:vendors,id',
            'order_date' => 'required|date',
            "delivery_date" => "required|date|after_or_equal:order_date",
            'items' => 'required|array',
            'items.*.item' => 'required|integer',
            'items.*.qty' => 'required|integer',
            'items.*.price' => 'required|numeric',
            'items.*.description' => 'nullable|string',
        ]);

        $data['invoice_number'] = getInvoiceNo('#PUR');
        $data['updated_by'] = $data['created_by'] = auth()->user()->id;

        $PurchaseInvoice = PurchaseInvoice::create($data);
        $data['total'] = 0;


        foreach ($data['items'] as $item) {
            $data['product_id'] = $item['item'];
            $data['qty'] = $item['qty'];
            $data['price'] = $item['price'];
            $data['description'] = $item['description'];

            $PurchaseInvoice->purchaseInvoiceDetails()->create($data);
            $data['total'] += $item['qty'] * $item['price'];
        }

        $PurchaseInvoice->update($data);

        return redirect()->route('purchase-invoices.index')->with('success', 'Purchase Invoice created successfully.');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PurchaseInvoice  $purchaseInvoice
     * @return \Illuminate\Http\Response
     */
    public function show(PurchaseInvoice $purchaseInvoice)
    {
        return $this->handleException(function () use ($purchaseInvoice) {
            return view('back.purchase-invoices.show', compact('purchaseInvoice'));
        });
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PurchaseInvoice  $purchaseInvoice
     * @return \Illuminate\Http\Response
     */
    public function edit(PurchaseInvoice $purchaseInvoice)
    {
        return $this->handleException(function () use ($purchaseInvoice) {

            $vendors = VendorResource::collection(Vendor::all());
            $customers = CustomerResource::collection(Customer::all());
            $products = ProductResource::collection(Product::all());

            return view('back.purchase-invoices.edit', compact('purchaseInvoice', 'vendors', 'customers', 'products'));
        });
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PurchaseInvoice  $purchaseInvoice
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PurchaseInvoice $purchaseInvoice)
    {
        $data = $request->validate([
            'vendor_id' => 'required|integer|exists:vendors,id',
            'order_date' => 'required|date',
            "delivery_date" => "required|date|after_or_equal:order_date",
            'items' => 'required|array',
            'items.*.item' => 'required|integer',
            'items.*.qty' => 'required|integer',
            'items.*.price' => 'required|numeric',
            'items.*.description' => 'nullable|string',
        ]);


        $data['updated_by'] = auth()->user()->id;

        $purchaseInvoice->update($data);

        $purchaseInvoice->purchaseInvoiceDetails()->delete();
        $data['total'] = 0;

        foreach ($data['items'] as $item) {
            $data['product_id'] = $item['item'];
            $data['qty'] = $item['qty'];
            $data['price'] = $item['price'];
            // $data['discount'] = $item['discount'];
            $data['description'] = $item['description'];

            $purchaseInvoice->purchaseInvoiceDetails()->create($data);
            $data['total'] += $item['qty'] * $item['price'];

        }

        // $data['discount'] = $totalDiscount;

        $purchaseInvoice->update($data);

        return redirect()->route('purchase-invoices.index')->with('success', 'Purchase Invoice updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PurchaseInvoice  $purchaseInvoice
     * @return \Illuminate\Http\Response
     */
    public function destroy(PurchaseInvoice $purchaseInvoice)
    {
        $purchaseInvoice->delete();

        return redirect()->route('purchase-invoices.index')->with('success', 'Purchase Invoice deleted successfully.');

    }
}
