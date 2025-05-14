<?php

namespace App\Http\Controllers\Admin;

use App\Models\Vendor;
use App\Models\Account;
use Illuminate\Http\Request;
use App\Models\AdminCreditCard;
use App\Models\PurchaseInvoice;
use App\Models\PurchasePayment;
use Illuminate\Support\Facades\Log;
use App\Models\PurchaseInvoicePayment;
use App\Http\Controllers\BaseController;
use App\Jobs\SendPurchaseInvoicePayment;
use App\Http\Resources\PurchaseInvoiceResource;
use App\Http\Resources\PurchaseInvoicePaymentResource;

// use App\Services\PurchasePaymentService;

class PurchasePaymentController extends BaseController
{
    // protected $purchasePaymentService;

    // public function __construct(PurchasePaymentService $purchasePaymentService)
    // {
    //     $this->purchasePaymentService = $purchasePaymentService;
    // }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    function __construct()
    {
        $this->middleware('permission:purchase-payment-list|purchase-payment-create|purchase-payment-edit|purchase-payment-delete|purchase-payment-show
          ', ['only' => ['index', 'show']]);
        $this->middleware('permission:purchase-payment-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:purchase-payment-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:purchase-payment-delete', ['only' => ['destroy']]);
        $this->middleware('permission:purchase-payment-show', ['only' => ['show']]);
    }


    public function index()
    {

        if (auth()->user()->hasRole(['Cashier', 'Manager'])) {
            $purchaseInvoicePayments = PurchaseInvoicePaymentResource::collection(PurchaseInvoicePayment::whereHas('purchaseInvoice', function ($q) {
                $q->whereHas('purchase', function ($q) {
                    $q->where('warehouse_id', auth()->user()->warehouse_id);
                });
            })->get());

            $vendors = Vendor::with('purchases', 'purchases.invoice')->get();

            return view('back.purchase-payments.index', compact('purchaseInvoicePayments', 'vendors'));
        }
        else
        {
            if (session()->has('selected_warehouse_id') && auth()->user()->hasRole('Admin')) {
                $warehouseId = session()->get('selected_warehouse_id');

                $purchaseInvoicePayments = PurchaseInvoicePayment::with('purchasePayment', 'purchaseInvoice')->whereHas('purchaseInvoice', function ($q) use ($warehouseId) {
                    $q->whereHas('purchase', function ($q) use ($warehouseId) {
                        $q->where('warehouse_id', $warehouseId);
                    });
                })->get();
            }
            else
            {
                $purchaseInvoicePayments = PurchaseInvoicePayment::with('purchasePayment', 'purchaseInvoice')->get();
            }
        }

        // $purchaseInvoicePayments = PurchaseInvoicePayment::with('purchasePayment', 'purchaseInvoice')->get();
        $vendors = Vendor::with('purchases', 'purchases.invoice')->get();

        return view('back.purchase-payments.index', compact('purchaseInvoicePayments', 'vendors'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // $purchaseInvoices = PurchaseInvoiceResource::collection(PurchaseInvoice::where('status', '!=', 'paid')->get());
        $purchaseInvoicePayments = PurchaseInvoicePayment::with('purchasePayment', 'purchaseInvoice')->get();
        // $purchaseInvoices = PurchaseInvoice::all();
        $vendors = Vendor::with('purchases', 'purchases.invoice')->get();
        $cards = AdminCreditCard::all();
        $accounts = Account::all();

        return view('back.purchase-payments.create', compact('purchaseInvoicePayments', 'vendors', 'accounts', 'cards'));
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
            'account_id' => 'nullable|integer|exists:accounts,id',
            'items' => 'required|array',
            'items.*.pay_amount' => 'required|numeric',
            'items.*.invoice_id' => 'required|integer|exists:purchase_invoices,id',
            'payment_date' => 'required|date',
            'total_pay' => 'required|numeric',
            'status' => 'required',
            'note' => 'nullable|string',
            'payment_method' => 'required|string',
            'card_id' => 'nullable',
        ]);


        $data['created_by'] = $data['updated_by'] = auth()->user()->id;
        $data['reciept_no'] = $data['receipt_no'] ?? '';

        $invoicePayments = PurchasePayment::create($data);
        $purchaseInvoices = $request->input('items');

        foreach ($purchaseInvoices as $purchaseInvoice) {

            if ($purchaseInvoice['invoice_id'] != null) {

                $invoice = PurchaseInvoice::with('purchase')->findOrFail($purchaseInvoice['invoice_id']);
                $invoice->purchase->amount_recieved += $purchaseInvoice['pay_amount'];
                $invoice->purchase->amount_due -= $purchaseInvoice['pay_amount'];
                if ($invoice->purchase->amount_due == 0) {
                    $invoice->purchase->payment_status = 'paid';
                }
                $invoice->purchase->save();

                $purchaseInvoice['purchase_invoice_id'] = $purchaseInvoice['invoice_id'];
                $purchaseInvoice['purchase_payment_id'] = $invoicePayments->id;
                $purchaseInvoice['paid_amount'] = $purchaseInvoice['pay_amount'];

                PurchaseInvoicePayment::create($purchaseInvoice);
            }
        }

        $paymentInvoice = PurchasePayment::with('invoicePayment.purchaseInvoice.purchase','vendor')->findOrFail($invoicePayments->id);
        $purchase = $paymentInvoice->invoicePayment[0]->purchaseInvoice->purchase;
        $totalDue = $paymentInvoice->vendor->totalDueAmount();


        try {

            $job = new SendPurchaseInvoicePayment($purchase,$paymentInvoice->vendor->user->email, $paymentInvoice, getLogo(), $totalDue);
            dispatch($job);
            Log::info('Email sent to: ' . $paymentInvoice->vendor->user->email);
        } catch (\Exception $e) {
            Log::error('Error sending email: ' . $e->getMessage());
        }

        return redirect()->route('purchase-payments.index')
            ->with('success', 'Purchase Payment created successfully.');
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

        $purchaseInvoicePayment = PurchaseInvoicePayment::findOrFail($id);
        // dd($purchaseInvoicePayment->purchasePayment);
        // $purchaseInvoicePayments = PurchaseInvoicePayment::with('purchasePayment', 'purchaseInvoice')->get();
        $vendors = Vendor::with('purchases', 'purchases.invoice')->get();
        $accounts = Account::all();
        $cards = AdminCreditCard::all();


        return view('back.purchase-payments.edit', compact('purchaseInvoicePayment',  'vendors', 'accounts','cards'));
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
        $data = $request->validate([
            'account_id' => 'nullable|integer|exists:accounts,id',
            'paid_amount' => 'required',
            'payment_date' => 'required',
            // 'cheque_no' => 'required|string',
            // 'receipt_no' => 'required|string',
            'note' => 'nullable|string',
        ]);

        $data['updated_by'] = auth()->user()->id;
        $data['reciept_no'] = $data['receipt_no'] ?? '';
        $purchaseInvoicePayment = PurchaseInvoicePayment::findOrFail($id);
        $purchaseInvoicePayment->purchaseInvoice->purchase->amount_recieved -= $purchaseInvoicePayment->paid_amount;
        $purchaseInvoicePayment->purchaseInvoice->purchase->amount_due += $purchaseInvoicePayment->paid_amount;
        $purchaseInvoicePayment->purchaseInvoice->purchase->save();
        $purchaseInvoicePayment->update($data);

        $purchaseInvoicePayment->purchaseInvoice->purchase->amount_recieved += $data['paid_amount'];
        $purchaseInvoicePayment->purchaseInvoice->purchase->amount_due -= $data['paid_amount'];
        $purchaseInvoicePayment->purchaseInvoice->purchase->save();
        $purchaseInvoicePayment->purchaseInvoice->purchase->payment_status = $purchaseInvoicePayment->purchaseInvoice->purchase->amount_due == 0 ? 'paid' : 'partial';
        $purchaseInvoicePayment->purchaseInvoice->purchase->save();
        $purchaseInvoicePayment->purchasePayment->update($data);
        return redirect()->route('purchase-payments.index')
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
        // $purchaseInvoicePayment = PurchaseInvoicePayment::findOrFail($id);
        // $result = $this->purchasePaymentService->destroyPayment($purchaseInvoicePayment);

        // if ($result['success']) {
        //     return redirect()->back()->with('success', $result['message']);
        // } else {
        //     return redirect()->back()->with('error', $result['message']);
        // }

        $purchaseInvoicePayment = PurchaseInvoicePayment::findOrFail($id);
        $purchaseInvoicePayment->purchaseInvoice->purchase->amount_recieved -= $purchaseInvoicePayment->paid_amount;
        $purchaseInvoicePayment->purchaseInvoice->purchase->amount_due += $purchaseInvoicePayment->paid_amount;
        if ($purchaseInvoicePayment->purchaseInvoice->purchase->amount_due > 0) {
            $purchaseInvoicePayment->purchaseInvoice->purchase->payment_status = 'partial';
        }
        $purchaseInvoicePayment->purchaseInvoice->purchase->save();
        $purchaseInvoicePayment->delete();
        return redirect()->route('purchase-payments.index')
            ->with('success', 'Invoice Payment deleted successfully.');
    }


    public function multipleDelete(Request $req)
    {

        foreach ($req->ids as $key => $id) {
            $purchaseInvoicePayment = PurchaseInvoicePayment::findOrFail($id);
            $purchaseInvoicePayment->purchaseInvoice->purchase->amount_recieved -= $purchaseInvoicePayment->paid_amount;
            $purchaseInvoicePayment->purchaseInvoice->purchase->amount_due += $purchaseInvoicePayment->paid_amount;
            if ($purchaseInvoicePayment->purchaseInvoice->purchase->amount_due > 0) {
                $purchaseInvoicePayment->purchaseInvoice->purchase->payment_status = 'partial';
            }
            $purchaseInvoicePayment->purchaseInvoice->purchase->save();
            $purchaseInvoicePayment->delete();
        }

        return response()->json(['status' => 200, 'message' => 'Sale Payment Deleted Successfully!']);
    }
}
