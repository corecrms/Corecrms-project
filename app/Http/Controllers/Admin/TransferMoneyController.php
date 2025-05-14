<?php

namespace App\Http\Controllers\Admin;

use App\Models\TransferMoney;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use App\Models\Account;

class TransferMoneyController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     function __construct()
     {
         $this->middleware('permission:transfer-money-list|transfer-money-create|transfer-money-edit|transfer-money-delete|account-show
          ', ['only' => ['index', 'show']]);
         $this->middleware('permission:transfer-money-list', ['only' => ['index']]);
         $this->middleware('permission:transfer-money-create', ['only' => ['create', 'store']]);
         $this->middleware('permission:transfer-money-edit', ['only' => ['edit', 'update']]);
         $this->middleware('permission:transfer-money-delete', ['only' => ['destroy']]);
         $this->middleware('permission:transfer-money-show', ['only' => ['show']]);
     }

    public function index()
    {
        return $this->handleException(function () {
            $transferMoney = TransferMoney::all();
            $accounts = Account::all();
            return view('back.transfer-money.index', compact('transferMoney', 'accounts'));
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
            return view('back.transfer_money.create');
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
            'amount' => 'required|numeric',
            'date' => 'required|date',
            'from_account_id' => [
                'required',
                'exists:accounts,id',
                function ($attribute, $value, $fail) use ($request) {
                    if ($value == $request->to_account_id) {
                        $fail('The from account and to account must be different.');
                    }

                    $account = Account::find($value);
                    if ($account->init_balance < $request->amount) {
                        $fail('Insufficient balance for this transfer.');
                    }
                },
            ],
            'to_account_id' => 'required|exists:accounts,id',
        ]);
        return $this->handleException(function () use ($data) {

            $data['created_by'] = $data['updated_by'] = auth()->id();
            TransferMoney::create($data);

            return redirect()->route('transfer-money.index')
                ->with('success', 'Transfer Money created successfully.');
        });
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TransferMoney  $transferMoney
     * @return \Illuminate\Http\Response
     */
    public function show(TransferMoney $transferMoney)
    {
        return $this->handleException(function () use ($transferMoney) {
            return view('back.transfer_money.show', compact('transferMoney'));
        });
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TransferMoney  $transferMoney
     * @return \Illuminate\Http\Response
     */
    public function edit(TransferMoney $transferMoney)
    {
        return $this->handleException(function () use ($transferMoney) {
            return view('back.transfer_money.edit', compact('transferMoney'));
        });
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TransferMoney  $transferMoney
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TransferMoney $transferMoney)
    {

        $data = $request->validate([
            'amount' => 'required|numeric',
            'date' => 'required|date',
            // 'from_account_id' => 'required|exists:accounts,id',
            'from_account_id' => [
                'required',
                'exists:accounts,id',
                function ($attribute, $value, $fail) use ($request) {
                    if ($value == $request->to_account_id) {
                        $fail('The from account and to account must be different.');
                    }

                    $account = Account::find($value);
                    if ($account->init_balance < $request->amount) {
                        $fail('Insufficient balance for this transfer.');
                    }
                },
            ],
            'to_account_id' => 'required|exists:accounts,id',
        ]);
        return $this->handleException(function () use ($data, $transferMoney) {
            // dd($data);
            $data['updated_by'] = auth()->id();
            $transferMoney->update($data);

            return redirect()->route('transfer-money.index')
                ->with('success', 'Transfer Money updated successfully.');
        });
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TransferMoney  $transferMoney
     * @return \Illuminate\Http\Response
     */
    public function destroy(TransferMoney $transferMoney)
    {
        return $this->handleException(function () use ($transferMoney) {
            $transferMoney->delete();
            return redirect()->route('transfer-money.index')
                ->with('success', 'Transfer Money deleted successfully.');
        });
    }
    public function deleteTransferMoney(Request $req)
    {

        if(!empty($req->ids) && is_array($req->ids)){
            // dd($req->all());
            foreach ($req->ids as $id) {
                TransferMoney::find($id)->delete();
            }
            return response()->json(['status' => 200,'message' => 'Transfer Money Deleted Successfully!']);

        }
    }
}
