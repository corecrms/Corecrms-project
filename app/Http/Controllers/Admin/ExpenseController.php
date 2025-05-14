<?php

namespace App\Http\Controllers\Admin;

use App\Models\Expense;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use App\Models\ExpenseCategory;
use App\Models\Account;
use App\Models\Warehouse;

class ExpenseController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     function __construct()
     {
         $this->middleware('permission:expense-list|expense-create|expense-edit|expense-delete|expense-show
          ', ['only' => ['index', 'show']]);
         $this->middleware('permission:expense-list', ['only' => ['index']]);
         $this->middleware('permission:expense-create', ['only' => ['create', 'store']]);
         $this->middleware('permission:expense-edit', ['only' => ['edit', 'update']]);
         $this->middleware('permission:expense-delete', ['only' => ['destroy']]);
         $this->middleware('permission:expense-show', ['only' => ['show']]);
     }

    public function index()
    {
        return $this->handleException(function () {

            if(auth()->user()->hasRole(['Cashier', 'Manager'])){
                $expenses = Expense::with('warehouse')->where('warehouse_id',auth()->user()->warehouse_id)->latest()->get();
            }
            else
            {
                if (session()->has('selected_warehouse_id') && auth()->user()->hasRole('Admin')) {
                    $warehouseId = session()->get('selected_warehouse_id');
                    $expenses = Expense::with('warehouse')->where('warehouse_id',$warehouseId)->latest()->get();
                }
                else {
                    $expenses = Expense::with('warehouse')->latest()->get();
                }
            }

            return view('back.expenses.index', compact('expenses'));
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
            $accounts = Account::all();
            $warehouses = Warehouse::all();
            $expenseCategories = ExpenseCategory::all();
            return view('back.expenses.create', compact('accounts', 'warehouses', 'expenseCategories'));
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
            $data = $request->validate([
                'amount' => 'required|numeric',
                'date' => 'required|date',
                'description' => 'nullable|string',
                'account_id' => 'required|exists:accounts,id',
                'expense_category_id' => 'required|exists:expense_categories,id',
                'warehouse_id' => 'nullable|exists:warehouses,id',
            ]);
            // expense random reference will be generated unique
            $reference = 'EXP-' . rand(1000, 9999);
            $exists = Expense::where('reference', $reference)->exists();
            while ($exists) {
                $reference = 'EXP-' . rand(1000, 9999);
                $exists = Expense::where('reference', $reference)->exists();
            }
            $account = Account::find($data['account_id']);
            $account->init_balance -= $data['amount'];
            $account->save();

            $data['reference'] = $reference;
            $data['created_by'] = $data['updated_by'] = auth()->id();
            Expense::create($data);

            return redirect()->route('expenses.index')
                ->with('success', 'Expense created successfully.');
        });
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function show(Expense $expense)
    {
        return $this->handleException(function () use ($expense) {
            return view('back.expenses.show', compact('expense'));
        });
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function edit(Expense $expense)
    {
        return $this->handleException(function () use ($expense) {
            $warehouses = Warehouse::all();
            $expenseCategories = ExpenseCategory::all();
            $accounts = Account::all();
            return view('back.expenses.edit', compact('expense', 'warehouses', 'expenseCategories', 'accounts'));
        });
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Expense $expense)
    {

        return $this->handleException(function () use ($request, $expense) {
            // dd($request->all());
            $data = $request->validate([
                'amount' => 'required|numeric',
                'date' => 'required|date',
                'description' => 'nullable|string',
                'account_id' => 'required|exists:accounts,id',
                'expense_category_id' => 'required|exists:expense_categories,id',
                'warehouse_id' => 'nullable|exists:warehouses,id',
            ]);

            $account = Account::find($expense->account_id);
            $account->init_balance += $expense->amount;
            $account->save();
            $account = Account::find($data['account_id']);
            $account->init_balance -= $data['amount'];
            $account->save();

            $data['updated_by'] = auth()->id();
            $expense->update($data);

            return redirect()->route('expenses.index')
                ->with('success', 'Expense updated successfully.');
        });
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function destroy(Expense $expense)
    {
        return $this->handleException(function () use ($expense) {
            $account = Account::find($expense->account_id);
            $account->init_balance += $expense->amount;
            $account->save();
            $expense->delete();


            return redirect()->route('expenses.index')
                ->with('success', 'Expense deleted successfully.');
        });
    }

    public function deleteExpenses(Request $req)
    {
        if(!empty($req->ids) && is_array($req->ids)){
            // dd($req->all());
            foreach ($req->ids as $id) {
                Expense::find($id)->delete();

            }

            return response()->json(['status' => 200,'message' => 'Expense deleted successfully.']);

        }

    }
}
