<?php

namespace App\Http\Controllers\Admin;

use App\Models\ExpenseCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;


class ExpenseCategoryController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->handleException(function () {
            $expenseCategories = ExpenseCategory::all();
            return view('back.expense-categories.index', compact('expenseCategories'));
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
            return view('back.expense-categories.create');
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
                'name' => 'required|string',
                'description' => 'nullable|string',
            ]);
            $data['created_by'] = $data['updated_by'] = auth()->id();

            ExpenseCategory::create($data);

            return redirect()->route('expense-categories.index')
                ->with('success', 'Expense category created successfully.');
        });
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ExpenseCategory  $expenseCategory
     * @return \Illuminate\Http\Response
     */
    public function show(ExpenseCategory $expenseCategory)
    {
        return $this->handleException(function () use ($expenseCategory) {
            return view('back.expense-categories.show', compact('expenseCategory'));
        });
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ExpenseCategory  $expenseCategory
     * @return \Illuminate\Http\Response
     */
    public function edit(ExpenseCategory $expenseCategory)
    {
        return $this->handleException(function () use ($expenseCategory) {
            return view('back.expense-categories.edit', compact('expenseCategory'));
        });
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ExpenseCategory  $expenseCategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ExpenseCategory $expenseCategory)
    {
        return $this->handleException(function () use ($request, $expenseCategory) {
            $data = $request->validate([
                'name' => 'required|string',
                'description' => 'nullable|string',
            ]);

            $data['updated_by'] = auth()->id();

            $expenseCategory->update($data);

            return redirect()->route('expense-categories.index')
                ->with('success', 'Expense category updated successfully.');
        });
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ExpenseCategory  $expenseCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy(ExpenseCategory $expenseCategory)
    {
        return $this->handleException(function () use ($expenseCategory) {
            $expenseCategory->delete();
            return redirect()->route('expense-categories.index')
                ->with('success', 'Expense category deleted successfully.');
        });
    }
    
    public function deleteExpenseCategory(Request $req)
    {
        if(!empty($req->ids) && is_array($req->ids)){
            // dd($req->all());
            foreach ($req->ids as $id) {
                ExpenseCategory::find($id)->delete();
            }
            return response()->json(['status' => 200,'message' => 'Expense Category Deleted Successfully!']);
        }
    }
}
