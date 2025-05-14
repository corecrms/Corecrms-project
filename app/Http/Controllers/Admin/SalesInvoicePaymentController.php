<?php

namespace App\Http\Controllers\Admin;

use App\Models\Account;
use App\Models\Customer;
use App\Models\SaleInvoice;
use App\Models\SalesInvoice;
use App\Models\SalesPayment;
use Illuminate\Http\Request;
use App\Jobs\SendPaymentInvoice;
use App\Models\SalesInvoicePayment;
use Illuminate\Support\Facades\Log;
use App\Services\SalesPaymentService;
use App\Http\Controllers\BaseController;
use App\Http\Resources\SalesInvoiceResource;
use App\Http\Resources\SalesInvoicePaymentResource;

class SalesInvoicePaymentController extends BaseController
{


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $salesInvoicePayments = SalesInvoicePaymentResource::collection(SalesInvoicePayment::all());

        if (auth()->user()->hasRole(['Cashier', 'Manager'])) {
            $salesInvoicePayments = SalesInvoicePayment::whereHas('saleInvoice', function ($q) {
                $q->whereHas('sale', function ($q) {
                    $q->where('warehouse_id', auth()->user()->warehouse_id);
                });
            })->get();

            $customers = Customer::with('sales', 'sales.invoice')->get();
            return view('back.sales-payments.index', compact('salesInvoicePayments', 'customers'));
        } else {
            if (session()->has('selected_warehouse_id') && auth()->user()->hasRole('Admin')) {
                $warehouseId = session()->get('selected_warehouse_id');

                $salesInvoicePayments = SalesInvoicePayment::with('salesPayment', 'saleInvoice')->whereHas('saleInvoice', function ($q) use ($warehouseId) {
                    $q->whereHas('sale', function ($q) use ($warehouseId) {
                        $q->where('warehouse_id', $warehouseId);
                    });
                })->get();
            } else {
                $salesInvoicePayments = SalesInvoicePayment::with('salesPayment', 'saleInvoice')->get();
            }
        }


        $customers = Customer::with('sales', 'sales.invoice')->get();
        return view('back.sales-payments.index', compact('salesInvoicePayments', 'customers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // $salesInvoices = SalesInvoiceResource::collection(SalesInvoice::where('status', '!=', 'paid')->get());
        $salesInvoicePayments = SalesInvoicePayment::with('salesPayment', 'saleInvoice')->get();
        // dd($salesInvoicePayments);
        $customers = Customer::with('sales', 'sales.invoice', 'user.savedCards')->get();
        // dd($customers);
        $accounts = Account::all();
        return view('back.sales-payments.create', compact('salesInvoicePayments', 'customers', 'accounts'));
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

        $request->validate([
            'customer_id' => 'required|integer|exists:customers,id',
            'account_id' => 'nullable|integer|exists:accounts,id',
            'items' => 'required|array',
            'items.*.pay_amount' => 'required|numeric',
            'items.*.invoice_id' => 'required|integer|exists:sale_invoices,id',
            'payment_date' => 'required|date',
            'total_pay' => 'required|numeric',
            'status' => 'required',
            'note' => 'nullable|string',
        ], [
            'items.required' => 'Please select invoice.',
            'items.*.pay_amount.required' => 'The pay amount field is required.',
            'items.*.invoice_id.required' => 'Please select invoice.',
        ]);

        $data = $request->all();

        $data['created_by'] = $data['updated_by'] = auth()->user()->id;
        $data['reciept_no'] = $data['receipt_no'] ?? null;
        // dd($data);

        $invoicePayments = SalesPayment::create($data);
        $saleInvoices = $request->input('items');

        foreach ($saleInvoices as $saleInvoice) {

            if ($saleInvoice['invoice_id'] != null) {
                $invoice = SaleInvoice::with('sale')->findOrFail($saleInvoice['invoice_id']);
                $invoice->sale->amount_recieved += $saleInvoice['pay_amount'];
                $invoice->sale->amount_due -= $saleInvoice['pay_amount'];
                if ($invoice->sale->amount_due == 0) {
                    $invoice->sale->payment_status = 'paid';
                }
                $invoice->sale->save();
                $saleInvoice['sale_invoice_id'] = $saleInvoice['invoice_id'];
                $saleInvoice['sales_payment_id'] = $invoicePayments->id;
                $saleInvoice['paid_amount'] = $saleInvoice['pay_amount'];

                SalesInvoicePayment::create($saleInvoice);
            }
        }
        // $paymentInvoice = SalesPayment::with('inoicePayment.saleInvoice.sale')->findOrFail($invoicePayments->id);
        $paymentInvoice = SalesPayment::with('invoicePayment.saleInvoice.sale','customer')->findOrFail($invoicePayments->id);
        $sale = $paymentInvoice->invoicePayment[0]->saleInvoice->sale;
        $totalDue = $paymentInvoice->customer->totalDue();


        try {

            $job = new SendPaymentInvoice($sale, $paymentInvoice->customer->user->email, $paymentInvoice,getLogo(), $totalDue,);
            dispatch($job);
            Log::info('Email sent to: ' . $paymentInvoice->customer->user->email);
        } catch (\Exception $e) {
            Log::error('Error sending email: ' . $e->getMessage());
        }


        return redirect()->route('sales-payments.index')
            ->with('success', 'Invoice Payment created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(string $id)
    {
        // $salesInvoices = SalesInvoiceResource::collection(SalesInvoice::where('status', '!=', 'paid')->get());
        // $salesInvoicePayments = SalesInvoicePayment::with('salesPayment','saleInvoice')->get();
        // dd($salesInvoicePayments);
        $customers = Customer::with('sales', 'sales.invoice')->get();
        $accounts = Account::all();
        $salesInvoicePayment = SalesInvoicePayment::findOrFail($id);
        // dd($salesInvoicePayment->saleInvoice->sale->amount_due);
        return view('back.sales-payments.edit', compact('salesInvoicePayment', 'customers', 'accounts'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, string $id)
    {
        // dd($request->all());
        $request->validate([
            'account_id' => 'nullable|integer|exists:accounts,id',
            'paid_amount' => 'required',
            'payment_date' => 'required',
            'cheque_no' => 'nullable|string',
            'receipt_no' => 'nullable|string',
            'note' => 'nullable|string',
        ]);

        $data = $request->all();

        $data['updated_by'] = auth()->user()->id;
        $data['reciept_no'] = $data['receipt_no'];
        $salesInvoicePayment = SalesInvoicePayment::findOrFail($id);
        $salesInvoicePayment->saleInvoice->sale->amount_recieved -= $salesInvoicePayment->paid_amount;
        $salesInvoicePayment->saleInvoice->sale->amount_due += $salesInvoicePayment->paid_amount;
        $salesInvoicePayment->saleInvoice->sale->save();
        $salesInvoicePayment->update($data);

        $salesInvoicePayment->saleInvoice->sale->amount_recieved += $data['paid_amount'];
        $salesInvoicePayment->saleInvoice->sale->amount_due -= $data['paid_amount'];
        $salesInvoicePayment->saleInvoice->sale->save();
        $salesInvoicePayment->saleInvoice->sale->payment_status = $salesInvoicePayment->saleInvoice->sale->amount_due == 0 ? 'paid' : 'partial';
        $salesInvoicePayment->saleInvoice->sale->save();
        $salesInvoicePayment->salesPayment->update($data);
        return redirect()->route('sales-payments.index')
            ->with('success', 'Invoice Payment updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(string $id)
    {
        // $salesInvoicePayment = SalesInvoicePayment::findOrFail($id);
        // $result = $this->salesPaymentService->destroyPayment($salesInvoicePayment);

        // if ($result['success']) {
        //     return redirect()->back()->with('success', $result['message']);
        // } else {
        //     return redirect()->back()->with('error', $result['message']);
        // }

        $salesInvoicePayment = SalesInvoicePayment::findOrFail($id);
        $salesInvoicePayment->saleInvoice->sale->amount_recieved -= $salesInvoicePayment->paid_amount;
        $salesInvoicePayment->saleInvoice->sale->amount_due += $salesInvoicePayment->paid_amount;
        if ($salesInvoicePayment->saleInvoice->sale->amount_due > 0) {
            $salesInvoicePayment->saleInvoice->sale->payment_status = 'partial';
        }
        $salesInvoicePayment->saleInvoice->sale->save();
        $salesInvoicePayment->delete();
        return redirect()->route('sales-payments.index')
            ->with('success', 'Invoice Payment deleted successfully.');
    }


    public function multipleDelete(Request $req)
    {

        foreach ($req->ids as $key => $id) {
            $salesInvoicePayment = SalesInvoicePayment::findOrFail($id);
            $salesInvoicePayment->saleInvoice->sale->amount_recieved -= $salesInvoicePayment->paid_amount;
            $salesInvoicePayment->saleInvoice->sale->amount_due += $salesInvoicePayment->paid_amount;
            if ($salesInvoicePayment->saleInvoice->sale->amount_due > 0) {
                $salesInvoicePayment->saleInvoice->sale->payment_status = 'partial';
            }
            $salesInvoicePayment->saleInvoice->sale->save();
            $salesInvoicePayment->delete();
        }

        return response()->json(['status' => 200, 'message' => 'Sale Payment Deleted Successfully!']);
    }
}
