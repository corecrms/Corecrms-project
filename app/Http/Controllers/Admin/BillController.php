<?php

namespace App\Http\Controllers\Admin;

use App\Models\Bill;
use App\Models\Vendor;
use App\Models\Account;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use App\Models\BillPaymentHistory;
use App\Http\Controllers\Controller;
use App\Http\Controllers\BaseController;
use App\Models\PayBill;
use App\Models\Purchase;

class BillController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        if(auth()->user()->hasRole('Manager')){
            $purchases = Purchase::where('warehouse_id',auth()->user()->warehouse_id)->get();
        }
        else
        {
            if (session()->has('selected_warehouse_id') && auth()->user()->hasRole('Admin')) {
                $warehouseId = session()->get('selected_warehouse_id');
                $purchases = Purchase::where('warehouse_id',$warehouseId)->get();
            }
            else {
                $purchases = Purchase::all();
            }
        }

        $purchases->load('vendor','warehouse');
        return view('back.inventory.bill.index',compact('purchases'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $vendors = Vendor::all();
        $warehouses = Warehouse::all();
        return view('back.inventory.bill.create',compact('vendors','warehouses'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $req)
    {
        $req->validate
        (
            [
                'purchase_id' => 'required',
            ],
        ['purchase_id.required' => 'Please select purchase'],
        );
        $data = $req->all();
        $purchase = Purchase::find($data['purchase_id']);
        $data['vendor_id'] = $purchase->vendor_id;
        $data['amount'] = $purchase->grand_total;
        $data['date'] = date('Y-m-d');
        $bill = PayBill::create($data);
        $purchase->update(['payment_status' => 'paid']);
        return redirect()->back()->with(['success' => "Bill added successfully!"]);

    }

    public function addPayment(Request $req,){
            $req->validate([
                'account_id' => 'required',
                'amount' => 'required',
                'date' => 'required',
            ], [
                'account_id.required' => 'Please select account',
                'amount.required' => 'Please enter amount',
                'date.required' => 'Please select date',
            ]);
        $data = $req->all();
        $account = Account::find($data['account_id']);
        if($account){
            $account->update(['init_balance' => $account->balance + $data['amount']]);
            BillPaymentHistory::create($data);
            $bill = Bill::find($data['bill_id']);
            $bill->update(['status' => 'paid']);
            return redirect()->back()->with(['success' => "Payment added successfully!"]);
        }
        else
        {
            return redirect()->back()->with(['danger' => "Account not found!"]);
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
        $bill = Purchase::find($id);
        $bill->load('vendor','warehouse','purchaseItems','bill');
        // dd($bill);
        // $billPaymentHistory = BillPaymentHistory::where('bill_id',$id)->get();
        // $billPaymentHistory->load('bill','account');
        return view('back.inventory.bill.show',compact('bill'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $bill = Bill::find($id);
        $bill->load('vendor','warehouse','ProductItems');
        $vendors = Vendor::all();
        $warehouses = Warehouse::all();
        return view('back.inventory.bill.edit',compact('bill','vendors','warehouses'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $req, $id)
    {
         // Define validation rules
         $req->validate([
            'vendor_id' => 'required',
            'warehouse_id' => 'required',
            'product_items' => 'required',
        ], [
            'vendor_id.required' => 'Please select customer',
            'product_items.required' => 'Please select product',
            'warehouse_id.required' => 'Please select warehouse',
        ]);

        $data = $req->all();
        $bill = Bill::find($id);
        $bill->update($data);

        $bill->ProductItems()->delete();

        foreach ($data['product_items'] as $item) {
            $bill->ProductItems()->create($item);
        }

        return response()->json(['message' => 'Bill updated successfully!','status' => 200]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $bill = PayBill::where('purchase_id',$id)->first();
        if($bill){
            $bill->delete();
        }
        $purchase = Purchase::find($id);
        // $purchase->update(['payment_status' => 'unpaid','recieved_amount']);
        $purchase->payment_status = 'unpaid';
        $purchase->amount_recieved = 0;
        $purchase->amount_due = $purchase->grand_total;
        $purchase->save();

        return redirect()->back()->with(['success' => "Bill deleted successfully!"]);
    }


    public function deleteBills(Request $req){
        if (!empty($req->ids) && is_array($req->ids)) {
            // dd($req->all());
            foreach ($req->ids as $id) {
                $bill = PayBill::find($id);
                if($bill){
                    $bill->delete();
                }
                $purchase = Purchase::find($id);
                $purchase->payment_status = 'unpaid';
                $purchase->amount_recieved = 0;
                $purchase->amount_due = $purchase->grand_total;
                $purchase->save();
            }
            return response()->json(['status' => 200, 'message' => 'Bill deleted successfully!']);
        }

    }
}
