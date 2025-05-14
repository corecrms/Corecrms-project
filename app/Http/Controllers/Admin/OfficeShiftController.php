<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\OfficeShift;
use Illuminate\Http\Request;

class OfficeShiftController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $office_shifts = OfficeShift::all();
        $companies = Company::all();
        return view('back.hrm.office_shift.index', compact('office_shifts','companies'));
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
            'company_id' => 'required|integer|exists:companies,id',
        ]);
        $data = $request->all();
        $data['created_by'] = $data['updated_by'] = auth()->id();
        OfficeShift::create($data);
        return redirect()->route('office-shift.index')->with('success', 'Office Shift created successfully.');
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
        $request->validate([
            'name' => 'required|string|max:255',
            'company_id' => 'required|integer|exists:companies,id',
        ]);
        $data = $request->all();
        $data['updated_by'] = auth()->id();

        OfficeShift::find($id)->update($data);
        return redirect()->route('office-shift.index')->with('success', 'Office Shift updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        OfficeShift::find($id)->delete();
        return redirect()->route('office-shift.index')->with('success', 'Office Shift deleted successfully.');

    }


    public function multipleDelete(Request $req)
    {

        foreach ($req->ids as $key => $id) {
            OfficeShift::find($id)->delete();
        }

        return response()->json(['status' => 200, 'message' => 'Office Shift Deleted Successfully!']);
    }
}
