<?php

namespace App\Http\Controllers\Admin;

use App\Models\DepositCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;

class DepositCategoryController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->handleException(function () {
            $depositCategories = DepositCategory::all();
            return view('back.deposit-categories.index', compact('depositCategories'));
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
            return view('back.deposit-categories.create');
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
            DepositCategory::create($data);

            return redirect()->route('deposit-categories.index')
                ->with('success', 'Deposit category created successfully.');
        });
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DepositCategory  $depositCategory
     * @return \Illuminate\Http\Response
     */
    public function show(DepositCategory $depositCategory)
    {
        return $this->handleException(function () use ($depositCategory) {
            return view('back.deposit-categories.show', compact('depositCategory'));
        });
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DepositCategory  $depositCategory
     * @return \Illuminate\Http\Response
     */
    public function edit(DepositCategory $depositCategory)
    {
        return $this->handleException(function () use ($depositCategory) {
            return view('back.deposit-categories.edit', compact('depositCategory'));
        });
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DepositCategory  $depositCategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DepositCategory $depositCategory)
    {
        return $this->handleException(function () use ($request, $depositCategory) {
            $data = $request->validate([
                'name' => 'required|string',
                'description' => 'nullable|string',
            ]);
            $data['updated_by'] = auth()->id();
            $depositCategory->update($data);

            return redirect()->route('deposit-categories.index')
                ->with('success', 'Deposit category updated successfully.');
        });
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DepositCategory  $depositCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy(DepositCategory $depositCategory)
    {
        return $this->handleException(function () use ($depositCategory) {
            $depositCategory->delete();

            return redirect()->route('deposit-categories.index')
                ->with('success', 'Deposit category deleted successfully.');
        });
    }
    public function deleteDepositCategory(Request $req)
    {
        if(!empty($req->ids) && is_array($req->ids)){
            // dd($req->all());
            foreach ($req->ids as $id) {
                DepositCategory::find($id)->delete();
            }
            return response()->json(['status' => 200,'message' => 'Deposit category Deleted Successfully!']);

        }
    }
}
