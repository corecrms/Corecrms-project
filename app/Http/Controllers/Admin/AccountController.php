<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use App\Http\Resources\AccountResource;
use App\Models\Account;
use App\Http\Requests\AccountStoreRequest;
use App\Models\Customer;

class AccountController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     function __construct()
     {
         $this->middleware('permission:account-list|account-create|account-edit|account-delete|account-show
          ', ['only' => ['index', 'show']]);
         $this->middleware('permission:account-list', ['only' => ['index']]);
         $this->middleware('permission:account-create', ['only' => ['create', 'store']]);
         $this->middleware('permission:account-edit', ['only' => ['edit', 'update']]);
         $this->middleware('permission:account-delete', ['only' => ['destroy']]);
         $this->middleware('permission:account-show', ['only' => ['show']]);
     }

    public function index()
    {
        return $this->handleException(function () {
            $accounts = AccountResource::collection(Account::all());
            $customers = Customer::with('user')->get();
            return view('back.accounts.index', compact('accounts', 'customers'));
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
            $customers = Customer::with('user')->get();
            return view('back.accounts.create', compact('customers'));
        });
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AccountStoreRequest $request)
    {
        $data = $request->validated();
        // dd($data);

        $data['created_by'] = $data['updated_by'] = auth()->user()->id;

        Account::create($data);

        return redirect()->route('accounts.index')
            ->with('success', 'Account created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Account  $account
     * @return \Illuminate\Http\Response
     */
    public function show(Account $account)
    {
        return $this->handleException(function () use ($account) {
            $account = new AccountResource($account);

            return view('back.accounts.show', compact('account'));
        });
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Account  $account
     * @return \Illuminate\Http\Response
     */
    public function edit(Account $account)
    {
        return $this->handleException(function () use ($account) {
            $account = new AccountResource($account);


            return view('back.accounts.edit', compact('account'));
        });
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Account  $account
     * @return \Illuminate\Http\Response
     */
    public function update(AccountStoreRequest $request, Account $account)
    {
        $data = $request->validated();
        $data['updated_by'] = auth()->user()->id;
        if ($request->hasFile('account_img')) {
            if (Storage::disk('public')->exists('account/' . $account->account_img)) {
                Storage::disk('public')->delete('account/' . $account->account_img);
            }

            $image = $request->file('account_img');
            $filename = $data['name'] . '-' . time() . '.' . $image->getClientOriginalExtension();
            $data['account_img'] = $filename;

            Storage::disk('public')->put('account/' . $filename, file_get_contents($image));
        } else {
            unset($data['account_img']);
        }
        $account->update($data);

        return redirect()->route('accounts.index')
            ->with('success', 'Account updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Account  $account
     * @return \Illuminate\Http\Response
     */
    public function destroy(Account $account)
    {
        return $this->handleException(function () use ($account) {
            $account->delete();

            return redirect()->route('accounts.index')
                ->with('success', 'Account deleted successfully');
        });
    }
    public function deleteAccount(Request $req)
    {
        if(!empty($req->ids) && is_array($req->ids)){
            // dd($req->all());
            foreach ($req->ids as $id) {
                Account::find($id)->delete();
            }
            return response()->json(['status' => 200,'message' => 'Account Deleted Successfully!']);
        }



    }
}
