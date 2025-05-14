<?php

namespace App\Http\Controllers\Admin;

use App\Models\Deposit;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use App\Models\DepositCategory;
use App\Models\Warehouse;
use App\Models\Account;


class DepositController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->handleException(function () {

            if(auth()->user()->hasRole(['Cashier', 'Manager'])){
                $deposits = Deposit::with('warehouse')->where('warehouse_id',auth()->user()->warehouse_id)->latest()->get();
            }
            else
            {
                if (session()->has('selected_warehouse_id') && auth()->user()->hasRole('Admin')) {
                    $warehouseId = session()->get('selected_warehouse_id');
                    $deposits = Deposit::with('warehouse')->where('warehouse_id',$warehouseId)->latest()->get();
                }
                else {
                    $deposits = Deposit::with('warehouse')->latest()->get();
                }
            }

            return view('back.deposits.index', compact('deposits'));
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
            $warehouses = Warehouse::all();
            $depositCategories = DepositCategory::all();
            $accounts = Account::all();

            return view('back.deposits.create',compact('warehouses', 'depositCategories', 'accounts'));
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
        return $this->handleException(function () use ($request) {
            // dd($request->all());
            $data = $request->validate([
                'amount' => 'required|numeric',
                'date' => 'required|date',
                'description' => 'nullable|string',
                'deposit_category_id' => 'required|exists:deposit_categories,id',
                'account_id' => 'required|exists:accounts,id',
                'warehouse_id' => 'nullable|exists:warehouses,id',
            ]);
            $reference = 'DEP-' . rand(1000, 9999);
            $exists = Deposit::where('reference', $reference)->exists();
            while ($exists) {
                $reference = 'EXP-' . rand(1000, 9999);
                $exists = Deposit::where('reference', $reference)->exists();
            }

            $account = Account::find($data['account_id']);
            $account->init_balance += $data['amount'];
            $account->save();

            $data['reference'] = $reference;
            $data['created_by'] = $data['updated_by'] = auth()->id();


            Deposit::create($data);

            return redirect()->route('deposits.index')
                ->with('success', 'Deposit created successfully.');
        });
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Deposit  $deposit
     * @return \Illuminate\Http\Response
     */
    public function show(Deposit $deposit)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Deposit  $deposit
     * @return \Illuminate\Http\Response
     */
    public function edit(Deposit $deposit)
    {
        return $this->handleException(function () use ($deposit) {
            $warehouses = Warehouse::all();
            $depositCategories = DepositCategory::all();
            $accounts = Account::all();

            return view('back.deposits.edit', compact('deposit', 'warehouses', 'depositCategories', 'accounts'));
        });
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Deposit  $deposit
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Deposit $deposit)
    {
        return $this->handleException(function () use ($request, $deposit) {
            $data = $request->validate([
                'amount' => 'required|numeric',
                'date' => 'required|date',
                'description' => 'nullable|string',
                'deposit_category_id' => 'required|exists:deposit_categories,id',
                'account_id' => 'required|exists:accounts,id',
                'warehouse_id' => 'nullable|exists:warehouses,id',
            ]);
            $account = Account::find($deposit->account_id);
            $account->init_balance -= $deposit->amount;
            $account->save();
            $account = Account::find($data['account_id']);
            $account->init_balance += $data['amount'];
            $account->save();
            $data['updated_by'] = auth()->id();
            $deposit->update($data);


            return redirect()->route('deposits.index')
                ->with('success', 'Deposit updated successfully.');
        });
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Deposit  $deposit
     * @return \Illuminate\Http\Response
     */
    public function destroy(Deposit $deposit)
    {
        return $this->handleException(function () use ($deposit) {
            $account = Account::find($deposit->account_id);
            $account->init_balance -= $deposit->amount;
            $account->save();
            $deposit->delete();

            return redirect()->route('deposits.index')
                ->with('success', 'Deposit deleted successfully.');
        });
    }
    public function deleteDeposits(Request $req)
    {
        if(!empty($req->ids) && is_array($req->ids)){
            // dd($req->all());
            foreach ($req->ids as $id) {
                Deposit::find($id)->delete();
            }
            return response()->json(['status' => 200,'message' => 'Deposite Deleted Successfully!']);

        }

    }
}
