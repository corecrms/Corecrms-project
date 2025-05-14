<?php

namespace App\Http\Controllers\Admin;

use App\Models\Vendor;
// use App\Http\Resources\PurchaseInvoicePaymentResource;
// use App\Http\Resources\PurchaseInvoiceResource;
use App\Models\Account;
use App\Models\Purchase;
use Illuminate\Http\Request;
// use App\Services\PurchasePaymentService;
use App\Models\AdminCreditCard;
use App\Models\PurchaseInvoice;
use App\Models\NonPurchasePayment;
use Illuminate\Support\Facades\Log;
use App\Jobs\SendNonPurchaseInvoiceJob;
use App\Http\Controllers\BaseController;
use App\Models\NonPurchaseInvoicePayment;

class NonPurchaseInvoicePaymentController extends BaseController
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
    public function index()
    {
        // $purchaseInvoicePayments = PurchaseInvoicePaymentResource::collection(PurchaseInvoicePayment::all());
        $NonPurchaseInvoicePayments = NonPurchasePayment::with('vendor', 'account')->get();
        // dd($NonPurchaseInvoicePayments);
        $vendors = Vendor::with('purchases', 'purchases.invoice')->get();

        return view('back.non-purchase-payments.index', compact('NonPurchaseInvoicePayments', 'vendors'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    //  function __construct()
    //  {
    //      $this->middleware('permission:non-invoice-payment-list|non-invoice-payment-create|non-invoice-payment-edit|non-invoice-payment-delete|non-invoice-payment-show
    //       ', ['only' => ['index', 'show']]);
    //      $this->middleware('permission:non-invoice-payment-create', ['only' => ['create', 'store']]);
    //      $this->middleware('permission:non-invoice-payment-edit', ['only' => ['edit', 'update']]);
    //      $this->middleware('permission:non-invoice-payment-delete', ['only' => ['destroy']]);
    //      $this->middleware('permission:non-invoice-payment-show', ['only' => ['show']]);
    //  }

    public function create()
    {
        $vendors = Vendor::with('purchases', 'purchases.invoice')->get();
        $accounts = Account::all();
        $cards = AdminCreditCard::all();

        return view('back.non-purchase-payments.create', compact('vendors', 'accounts', 'cards'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'vendor_id' => 'required|integer|exists:vendors,id',
            'account_id' => 'nullable|integer|exists:accounts,id',
            'payment_type' => 'required',
            'amount_pay' => 'required|numeric',
            'payment_date' => 'required|date',
            'cheque_no' => 'nullable|string',
            'receipt_no' => 'nullable|string',
            'note' => 'nullable|string',
            'status' => 'required',
        ]);

        $data = $request->all();

        $data['created_by'] = $data['updated_by'] = auth()->user()->id;
        $data['reciept_no'] = $data['receipt_no'];

        $amount_pay = $data['amount_pay'];

        $purchases = Purchase::where('vendor_id', $data['vendor_id'])
            ->whereIn('payment_status', ['pending', 'partial'])
            ->get();

        if($purchases->count() > 0){
            foreach ($purchases as $purchase) {
                // Calculate the amount to apply to this sale
                $amount_to_apply = min($amount_pay, $purchase->amount_due);
    
                // Update the sale's amount_received and amount_due
                $purchase->amount_recieved += $amount_to_apply;
                $purchase->amount_due -= $amount_to_apply;
    
                // Determine the new payment status
                if ($purchase->amount_due == 0) {
                    $purchase->payment_status = 'paid';
                } elseif ($purchase->amount_recieved > 0) {
                    $purchase->payment_status = 'partial';
                } else {
                    $purchase->payment_status = 'pending';
                }
    
                // Save the sale
                $purchase->save();
    
                // Subtract the applied amount from the total amount to pay
                $amount_pay -= $amount_to_apply;
    
                // If no more amount to pay, break the loop
                if ($amount_pay <= 0) {
                    break;
                }
            }
        }

        NonPurchasePayment::create($data);
        $vendor = Vendor::find($data['vendor_id']);
        try {
            $job = new SendNonPurchaseInvoiceJob( $vendor, $data['amount_pay'], $data['payment_date']);
            dispatch($job);
            Log::info('Email sent to: ' . $vendor->user->email);
        } catch (\Exception $e) {
            Log::error('Error sending email: ' . $e->getMessage());
        }

        return redirect()->route('non-purchase-payments.index')
            ->with('success', 'Puchase Payment created successfully.');
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
        $nonPurchaseInvoicePayment = NonPurchasePayment::findOrFail($id);
        $vendors = Vendor::with('purchases', 'purchases.invoice')->get();
        $cards = AdminCreditCard::all();
        $accounts = Account::all();

        return view('back.non-purchase-payments.edit', compact('nonPurchaseInvoicePayment',  'vendors', 'accounts', 'cards'));
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
        $data = $request->validate([
            'account_id' => 'nullable|integer|exists:accounts,id',
            'payment_type' => 'required',
            'amount_pay' => 'required|numeric',
            'payment_date' => 'required|date',
            'cheque_no' => 'nullable|string',
            'receipt_no' => 'nullable|string',
            'note' => 'nullable|string',
            'status' => 'required',
        ]);

        $data['updated_by'] = auth()->user()->id;
        $data['reciept_no'] = $data['receipt_no'];

        $nonPurchaseInvoicePayment = NonPurchasePayment::findOrFail($id);
        $nonPurchaseInvoicePayment->update($data);

        return redirect()->route('non-purchase-payments.index')
            ->with('success', 'Non-Purchase Payment updated successfully.');
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

        $nonPurchaseInvoicePayment = NonPurchasePayment::findOrFail($id);
        $nonPurchaseInvoicePayment->delete();

        return redirect()->route('non-purchase-payments.index')
            ->with('success', 'Non-Purchase Payment deleted successfully.');
    }



    public function multipleDelete(Request $req)
    {

        foreach ($req->ids as $key => $id) {
            $nonPurchaseInvoicePayment = NonPurchasePayment::findOrFail($id);
            $nonPurchaseInvoicePayment->delete();
        }

        return response()->json(['status' => 200, 'message' => 'Non-Purchase Payment Deleted Successfully!']);
    }
}
