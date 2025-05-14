<?php

namespace App\Http\Controllers\Admin;

use App\Models\Sale;
use App\Models\Account;
use App\Models\Customer;
use App\Models\SalesInvoice;
use Illuminate\Http\Request;
use App\Models\CreditActivity;
use App\Models\NonSalesPayment;
use App\Jobs\SendNonInvoicePayment;
use Illuminate\Support\Facades\Log;
use App\Services\SalesPaymentService;
use App\Models\NonSalesInvoicePayment;
use App\Http\Controllers\BaseController;
use App\Http\Resources\SalesInvoiceResource;
use App\Http\Resources\SalesInvoicePaymentResource;

class NonSalesInvoicePaymentController extends BaseController
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    function __construct()
    {
        $this->middleware('permission:non-invoice-payment-list|non-invoice-payment-create|non-invoice-payment-edit|non-invoice-payment-delete|non-invoice-payment-show
          ', ['only' => ['index', 'show']]);
        $this->middleware('permission:non-invoice-payment-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:non-invoice-payment-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:non-invoice-payment-delete', ['only' => ['destroy']]);
        $this->middleware('permission:non-invoice-payment-show', ['only' => ['show']]);
    }

    public function index()
    {


        $NonSalesInvoicePayments = NonSalesPayment::with('customer', 'account')->get();

        $customers = Customer::with('sales', 'sales.invoice')->get();
        return view('back.non-sales-payments.index', compact('NonSalesInvoicePayments', 'customers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // $salesInvoicePayments =NonSalesInvoicePayment::with('salesPayment','saleInvoice')->get();
        $customers = Customer::with('sales', 'sales.invoice')->get();
        $accounts = Account::all();
        return view('back.non-sales-payments.create', compact('customers', 'accounts'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    // public function store(Request $request)
    // {
    //     // dd($request->all());
    //     $data = $request->validate([
    //         'customer_id' => 'required|integer|exists:customers,id',
    //         'account_id' => 'nullable|integer|exists:accounts,id',
    //         'payment_type' => 'required',
    //         'amount_pay' => 'required|numeric',
    //         'payment_date' => 'required|date',
    //         'cheque_no' => 'nullable|string',
    //         'receipt_no' => 'nullable|string',
    //         'note' => 'nullable|string',
    //         'status' => 'required',
    //         'payment_method' => 'required'
    //     ]);

    //     $customer = Customer::findOrFail($data['customer_id']);

    //     if ($customer->outstanding_balance > 0)
    //     {
    //         $customer->outstanding_balance -= $data['amount_pay'];
    //         $customer->save();
    //     }
    //     else
    //     {
    //         $sales = Sale::where('customer_id', $data['customer_id'])
    //             ->whereIn('payment_status', ['pending', 'partial'])
    //             ->get();
    //         $amount_pay = $data['amount_pay'];


    //         $total_due = $sales->sum('amount_due') ?? '0';

    //         foreach ($sales as $sale) {
    //             // Calculate the amount to apply to this sale
    //             $amount_to_apply = min($amount_pay, $sale->amount_due);

    //             // Update the sale's amount_received and amount_due
    //             $sale->amount_recieved += $amount_to_apply;
    //             $sale->amount_due -= $amount_to_apply;

    //             // Determine the new payment status
    //             if ($sale->amount_due == 0) {
    //                 $sale->payment_status = 'paid';
    //             } elseif ($sale->amount_recieved > 0) {
    //                 $sale->payment_status = 'partial';
    //             } else {
    //                 $sale->payment_status = 'pending';
    //             }

    //             // Save the sale
    //             $sale->save();

    //             // Subtract the applied amount from the total amount to pay
    //             $amount_pay -= $amount_to_apply;

    //             // If no more amount to pay, break the loop
    //             if ($amount_pay <= 0) {
    //                 break;
    //             }
    //         }

    //         $customer = Customer::findOrFail($data['customer_id']);
    //         if ($amount_pay > 0) {
    //             $customer->balance += $amount_pay;
    //             $customer->save();

    //             CreditActivity::create([
    //                 'customer_id' => $data['customer_id'],
    //                 'action' => 'modified',
    //                 'credit_balance' => $customer->balance,
    //                 'added_deducted' => $amount_pay,
    //                 'comment' => 'non-invoice payment added',
    //             ]);
    //         }

    //         $data['created_by'] = $data['updated_by'] = auth()->user()->id;
    //         $data['reciept_no'] = $data['receipt_no'];
    //     }

    //     $invoicePayments = NonSalesPayment::create($data);






    //     return redirect()->route('non-sales-payments.index')
    //         ->with('success', 'Non-Invoice Payment created successfully.');
    // }

    public function store(Request $request)
    {
        $data = $request->validate([
            'customer_id' => 'required|integer|exists:customers,id',
            'account_id' => 'nullable|integer|exists:accounts,id',
            'payment_type' => 'required',
            'amount_pay' => 'required|numeric',
            'payment_date' => 'required|date',
            'cheque_no' => 'nullable|string',
            'receipt_no' => 'nullable|string',
            'note' => 'nullable|string',
            'status' => 'required',
            'payment_method' => 'required'
        ]);

        $customer = Customer::findOrFail($data['customer_id']);
        $amount_pay = $data['amount_pay'];

        // Step 1: Deduct from outstanding balance
        if ($customer->outstanding_balance > 0) {
            if ($amount_pay >= $customer->outstanding_balance) {
                // Pay off the entire outstanding balance
                $amount_pay -= $customer->outstanding_balance;
                $customer->outstanding_balance = 0;
            } else {
                // Partial payment, less than the outstanding balance
                $customer->outstanding_balance -= $amount_pay;
                $amount_pay = 0; // All amount applied to outstanding balance
            }
            $customer->save();
        }

        // Step 2: Apply remaining payment to sales if there's still an amount to pay
        if ($amount_pay > 0) {
            $sales = Sale::where('customer_id', $data['customer_id'])
                ->whereIn('payment_status', ['pending', 'partial'])
                ->get();

            foreach ($sales as $sale) {
                // Calculate the amount to apply to this sale
                $amount_to_apply = min($amount_pay, $sale->amount_due);

                // Update the sale's amount_received and amount_due
                $sale->amount_recieved += $amount_to_apply;
                $sale->amount_due -= $amount_to_apply;

                // Determine the new payment status
                if ($sale->amount_due == 0) {
                    $sale->payment_status = 'paid';
                } elseif ($sale->amount_recieved > 0) {
                    $sale->payment_status = 'partial';
                } else {
                    $sale->payment_status = 'pending';
                }

                // Save the sale
                $sale->save();

                // Subtract the applied amount from the total amount to pay
                $amount_pay -= $amount_to_apply;

                // If no more amount to pay, break the loop
                if ($amount_pay <= 0) {
                    break;
                }
            }
        }

        // Step 3: If there's still remaining payment, add it to the customer's balance
        if ($amount_pay > 0) {
            $customer->balance += $amount_pay;
            $customer->save();

            CreditActivity::create([
                'customer_id' => $data['customer_id'],
                'action' => 'modified',
                'credit_balance' => $customer->balance,
                'added_deducted' => $amount_pay,
                'comment' => 'Non-invoice payment added',
            ]);
        }

        // Step 4: Store the payment information
        $data['created_by'] = $data['updated_by'] = auth()->user()->id;
        $data['receipt_no'] = $data['receipt_no']; // Ensure this is set correctly

        $invoicePayments = NonSalesPayment::create($data);

        $totalDue = $invoicePayments->customer->totalDue();



        try {

            $job = new SendNonInvoicePayment( $invoicePayments->customer->user->email, $invoicePayments, getLogo(), $totalDue);
            dispatch($job);
            Log::info('Email sent to: ' . $invoicePayments->customer->user->email);
        } catch (\Exception $e) {
            Log::error('Error sending email: ' . $e->getMessage());
        }

        return redirect()->route('non-sales-payments.index')
            ->with('success', 'Non-Invoice Payment created successfully.');
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
        $customers = Customer::with('sales', 'sales.invoice')->get();
        $accounts = Account::all();
        $nonSalesInvoicePayment = NonSalesPayment::findOrFail($id);
        // dd($salesInvoicePayment->saleInvoice->sale->amount_due);
        return view('back.non-sales-payments.edit', compact('nonSalesInvoicePayment', 'customers', 'accounts'));
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
        $invoicePayments = NonSalesPayment::findOrFail($id);
        $invoicePayments->update($data);
        return redirect()->route('non-sales-payments.index')
            ->with('success', 'Non-Invoice Payment updated successfully.');
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

        $invoicePayments = NonSalesPayment::findOrFail($id);

        $customer = Customer::findOrFail($invoicePayments->customer_id);
        $amount_pay = $invoicePayments->amount_pay;
        $customer->outstanding_balance += $amount_pay;
        $customer->save();

        $invoicePayments->delete();
        return redirect()->route('non-sales-payments.index')
            ->with('success', 'Non-Invoice Payment deleted successfully.');
    }


    public function multipleDelete(Request $req)
    {

        foreach ($req->ids as $key => $id) {
            $invoicePayments = NonSalesPayment::findOrFail($id);

            $customer = Customer::findOrFail($invoicePayments->customer_id);
            $amount_pay = $invoicePayments->amount_pay;
            $customer->outstanding_balance += $amount_pay;
            $customer->save();
            $invoicePayments->delete();
        }

        return response()->json(['status' => 200, 'message' => 'Sale Payment Deleted Successfully!']);
    }
}
