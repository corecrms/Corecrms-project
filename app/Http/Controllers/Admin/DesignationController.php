<?php

namespace App\Http\Controllers\Admin;

use App\Models\Designation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DesignationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $designations = Designation::with('company', 'department')->get();
        // dd($designations);
        $companies = \App\Models\Company::all();
        $departments = \App\Models\Department::all();
        return view('back.hrm.designation.index', compact('designations','companies','departments'));
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
            'name' => 'required|string|max:255',
            'company_id' => 'required|exists:companies,id',
            'department_id' => 'required|exists:departments,id',
        ]);

        $data = $request->all();
        $data['created_by'] = $data['updated_by'] = auth()->id();

        Designation::create($data);
        return redirect()->route('designations.index')->with('success', 'Designation created successfully.');
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
    public function update(Request $request, $id)
    {
        $designation = Designation::find($id);
        $request->validate([
            'name' => 'required|string|max:255',
            'company_id' => 'required|exists:companies,id',
            'department_id' => 'required|exists:departments,id',
        ]);

        $data = $request->all();
        $data['updated_by'] = auth()->id();

        $designation->update($data);
        return redirect()->route('designations.index')->with('success', 'Designation updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Designation::find($id)->delete();
        return redirect()->route('designations.index')->with('success', 'Designation deleted successfully.');
    }

    public function multipleDelete(Request $req)
    {

        foreach ($req->ids as $key => $id) {
            Designation::find($id)->delete();
        }

        return response()->json(['status' => 200, 'message' => 'Designation deleted successfully.']);
    }
}
