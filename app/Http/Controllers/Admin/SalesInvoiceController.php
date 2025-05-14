<?php

namespace App\Http\Controllers\Admin;

use App\Models\Vendor;
use App\Models\Product;
use App\Models\Customer;
use App\Models\SalesInvoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\VendorResource;
use App\Http\Resources\ProductResource;
use App\Http\Controllers\BaseController;
use App\Http\Resources\CustomerResource;
use App\Http\Resources\SalesInvoiceResource;
use App\Models\SalesInvoicePayment;

class SalesInvoiceController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->handleException(function () {
            $salesInvoices = SalesInvoiceResource::collection(SalesInvoice::all());
            // dd($salesInvoices);
            return view('back.sales-invoices.index', compact('salesInvoices'));
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
            $customers = CustomerResource::collection(Customer::all());
            $products = ProductResource::collection(Product::all());

            return view('back.sales-invoices.create', compact('vendors', 'customers', 'products'));
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
        $data = $request->validate([
            'customer_id' => 'required|integer|exists:customers,id',
            'vendor_id' => 'required|integer|exists:vendors,id',
            'reference_number' => 'required|string|max:255',
            'issue_date' => 'required|date',
            "due_date" => "required|date|after_or_equal:issue_date",
            'items' => 'required|array',
            'items.*.item' => 'required|integer',
            'items.*.qty' => 'required|integer',
            'items.*.price' => 'required|numeric',
            'items.*.discount' => 'nullable|numeric',
            'items.*.description' => 'nullable|string',
        ]);

        $data['invoice_number'] = getInvoiceNo();
        $data['updated_by'] = $data['created_by'] = auth()->user()->id;

        $SalesInvoice = SalesInvoice::create($data);

        $data['gross_amount'] = 0;
        $totalDiscount = 0;
        $data['net_amount'] = 0;

        foreach ($data['items'] as $item) {
            $data['product_id'] = $item['item'];
            $data['qty'] = $item['qty'];
            $data['price'] = $item['price'];
            $data['discount'] = $item['discount'];
            $data['description'] = $item['description'];

            $SalesInvoice->salesInvoiceDetails()->create($data);

            $data['gross_amount'] += $item['qty'] * $item['price'];
            $totalDiscount += $item['discount'];
            $data['net_amount'] += ($item['qty'] * $item['price']) - $item['discount'];
        }

        $data['discount'] = $totalDiscount;

        $SalesInvoice->update($data);

        return redirect()->route('sales-invoices.index')->with('success', 'Sales Invoice created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SalesInvoice  $salesInvoice
     * @return \Illuminate\Http\Response
     */
    public function show(SalesInvoice $salesInvoice)
    {
        return $this->handleException(function () use ($salesInvoice) {
            return view('back.sales-invoices.show', compact('salesInvoice'));
        });
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SalesInvoice  $salesInvoice
     * @return \Illuminate\Http\Response
     */
    public function edit(SalesInvoice $salesInvoice)
    {
        return $this->handleException(function () use ($salesInvoice) {

            $vendors = VendorResource::collection(Vendor::all());
            $customers = CustomerResource::collection(Customer::all());
            $products = ProductResource::collection(Product::all());

            return view('back.sales-invoices.edit', compact('salesInvoice', 'vendors', 'customers', 'products'));
        });
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SalesInvoice  $salesInvoice
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SalesInvoice $salesInvoice)
    {
        $data = $request->validate([
            'customer_id' => 'required|integer|exists:customers,id',
            'vendor_id' => 'required|integer|exists:vendors,id',
            'reference_number' => 'required|string|max:255',
            'issue_date' => 'required|date',
            "due_date" => "required|date|after_or_equal:issue_date",
            'items' => 'required|array',
            'items.*.item' => 'required|integer',
            'items.*.qty' => 'required|integer',
            'items.*.price' => 'required|numeric',
            'items.*.discount' => 'nullable|numeric',
            'items.*.description' => 'nullable|string',
        ]);


        $data['updated_by'] = auth()->user()->id;

        $salesInvoice->update($data);

        $data['gross_amount'] = 0;
        $totalDiscount = 0;
        $data['net_amount'] = 0;

        $salesInvoice->salesInvoiceDetails()->delete();

        foreach ($data['items'] as $item) {
            $data['product_id'] = $item['item'];
            $data['qty'] = $item['qty'];
            $data['price'] = $item['price'];
            $data['discount'] = $item['discount'];
            $data['description'] = $item['description'];

            $salesInvoice->salesInvoiceDetails()->create($data);

            $data['gross_amount'] += $item['qty'] * $item['price'];
            $totalDiscount += $item['discount'];
            $data['net_amount'] += ($item['qty'] * $item['price']) - $item['discount'];
        }

        $data['discount'] = $totalDiscount;

        $salesInvoice->update($data);

        return redirect()->route('sales-invoices.index')->with('success', 'Sales Invoice updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SalesInvoice  $salesInvoice
     * @return \Illuminate\Http\Response
     */
    public function destroy(SalesInvoice $salesInvoice)
    {
        $salesInvoice->delete();

        return redirect()->route('sales-invoices.index')->with('success', 'Sales Invoice deleted successfully.');
    }

    // storePayment
    public function storePayment(Request $request, SalesInvoice $salesInvoice)
    {
        try {

            DB::beginTransaction();
            $data = $request->validate([
                'amount' => 'required|numeric',
                'payment_date' => 'required|date',
                'payment_method' => 'required|string',
                'payment_reference' => 'nullable|string',
                'description' => 'nullable|string',
            ]);

            // $data['sales_invoice_id'] = $salesInvoice->id;
            $data['created_by'] = auth()->user()->id;

            // dd($data);
            $salesInvoicePayment = $salesInvoice->salesInvoicePayments()->create($data);

            $salesInvoice->paid_amount += $data['amount'];

            if ($salesInvoice->paid_amount >= $salesInvoice->net_amount) {
                $salesInvoice->status = 'Paid';
            } else {
                $salesInvoice->status = 'Partially Paid';
            }

            if ($salesInvoice->paid_amount > $salesInvoice->net_amount) {

                $salesInvoicePayment->salesInvoiceCreditNotes()->create([
                    'amount' => $salesInvoice->paid_amount - $salesInvoice->net_amount,
                    'credit_date' => $data['payment_date'],
                    'reference_number' => $salesInvoice->reference_number,
                    'description' => $data['description'],
                    'sales_invoice_id' => $salesInvoice->id,
                    'created_by' => auth()->user()->id,
                    'updated_by' => auth()->user()->id,
                ]);
            }

            $salesInvoice->update();

            DB::commit();
            return redirect()->back()->with('success', 'Payment added successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function destroyPayment(SalesInvoice $salesInvoice, SalesInvoicePayment $salesInvoicePayment)
    {
        try {
            DB::beginTransaction();

            $salesInvoicePayment->delete();

            $salesInvoicePayment->deleted_by = auth()->user()->id;
            $salesInvoicePayment->update();

            if ($salesInvoicePayment->salesInvoiceCreditNotes()->exists()) {
                $salesInvoicePayment->salesInvoiceCreditNotes()->delete();
            }

            $salesInvoice->paid_amount -= $salesInvoicePayment->amount;

            if ($salesInvoice->paid_amount >= $salesInvoice->net_amount) {
                $salesInvoice->status = 'Paid';
            } else if ($salesInvoice->paid_amount == 0) {
                $salesInvoice->status = 'Draft';
            } else {
                $salesInvoice->status = 'Partially Paid';
            }

            $salesInvoice->update();
            DB::commit();


            return redirect()->back()->with('success', 'Payment deleted successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
