<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $companies = \App\Models\Company::all();
        return view('back.hrm.company.index', compact('companies'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
            'name' => 'required',
            'email' => 'required|unique:companies,email',
            'phone_no' => 'required',
            'country' => 'required',
        ]);

        $data = $request->all();
        $data['created_by'] = auth()->user()->id;
        $data['updated_by'] = auth()->user()->id;
        Company::create($data);

        return redirect()->route('companies.index')->with('success', 'Company created successfully.');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Company $company)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|unique:companies,email,'.$company->id,
            'phone_no' => 'required',
            'country' => 'required',
        ]);

        $data = $request->all();
        $data['updated_by'] = auth()->user()->id;
        $company->update($data);

        return redirect()->route('companies.index')->with('success', 'Company updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Company $company)
    {
        $company->delete();
        return redirect()->route('companies.index')->with('success', 'Company deleted successfully.');
    }

    public function multipleDelete(Request $req)
    {

        foreach ($req->ids as $key => $id) {
            $company = Company::findOrFail($id);
            $company->delete();
        }

        return response()->json(['status' => 200, 'message' => 'Companies deleted successfully.!']);
    }
}
