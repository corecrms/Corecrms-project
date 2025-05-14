<?php

namespace App\Http\Controllers\Admin;

use App\Models\SalesInvoice;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\SalesPaymentService;
use App\Models\SalesInvoiceCreditNotes;
use App\Http\Resources\SalesInvoiceResource;
use App\Http\Resources\SalesInvoiceCreditNotesResource;

class SalesDepositController extends Controller
{
    protected $salesPaymentService;

    public function __contruct(SalesPaymentService $salesPaymentService)
    {
        $this->salesPaymentService = $salesPaymentService;
    }

    public function index()
    {
        // $salesInvoicePayments = SalesInvoiceCreditNotesResource::collection(SalesInvoiceCreditNotes::all());

        if (auth()->user()->hasRole('Admin')) {
            $salesInvoiceCreditNotes = SalesInvoiceCreditNotesResource::collection(SalesInvoiceCreditNotes::all());
        } else {
            $salesInvoiceCreditNotes = SalesInvoiceCreditNotesResource::collection(auth()->user()->salesInvoiceCreditNotes);
        }

        return view('back.sales-deposits.index', compact('salesInvoiceCreditNotes'));
    }

    public function create()
    {
        if (auth()->user()->hasRole('Admin')) {
            $salesInvoices = SalesInvoiceResource::collection(SalesInvoice::all());
        } else {
            $salesInvoices = SalesInvoiceResource::collection(auth()->user()->salesInvoices()->get());
        }

        return view('back.sales-deposits.create', compact('salesInvoices'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'amount' => 'required|numeric',
            'payment_date' => 'required|date',
            'payment_method' => 'required|string',
            'payment_reference' => 'nullable|string',
            'description' => 'nullable|string',
            'sales_invoice_id' => 'required|integer|exists:sales_invoices,id'
        ]);

        $salesInvoice = SalesInvoice::find($data['sales_invoice_id']);

        $response = $this->salesPaymentService->storePayment($salesInvoice, $data);

        if (!$response['success']) {
            return redirect()->back()->with('error', $response['message']);
        }

        return redirect()->route('sales-deposits.index')->with('success', $response['message']);
    }

    public function destroy(SalesInvoiceCreditNotes $salesInvoiceCreditNotes)
    {
        $response = $this->salesPaymentService->destroyPayment($salesInvoiceCreditNotes);

        if (!$response['success']) {
            return redirect()->back()->with('error', $response['message']);
        }

        return redirect()->route('sales-deposits.index')->with('success', $response['message']);
    }
}
