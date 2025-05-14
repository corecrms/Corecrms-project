<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Company;
use App\Models\Employee;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $attendances = Attendance::all();
        // $attendances = collect();
        $companies = Company::all()->load('employees');
        $employees = Employee::all();
        return view('back.hrm.attendance.index',compact('attendances','companies','employees'));
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
            'company_id' => 'required|exists:companies,id',
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required',
            'time_in' => 'required',
            'time_out' => 'required',
        ]);
        $data =  $request->all();
        // dd($data);

        $attendance = Attendance::create($data);
        return redirect()->route('attendance.index')->with('success','Attendance created successfully');
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
        $attendance = Attendance::findOrFail($id);
        $request->validate([
            'company_id' => 'required|exists:companies,id',
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required',
            'time_in' => 'required',
            'time_out' => 'required',
        ]);
        $data =  $request->all();

        $attendance->update($data);
        return redirect()->route('attendance.index')->with('success','Attendance updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Attendance::find($id)->delete();
        return redirect()->route('attendance.index')->with('success','Attendance deleted successfully');
    }


    public function multipleDelete(Request $req)
    {

        foreach ($req->ids as $key => $id) {
            Attendance::find($id)->delete();
        }

        return response()->json(['status' => 200, 'message' => 'Attendance Deleted Successfully!']);
    }
}
