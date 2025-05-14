<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Employee;
use App\Models\LeaveType;
use App\Models\Department;
use App\Models\LeaveRequest;
use Illuminate\Http\Request;

class LeaveRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $companies = Company::all()->load('departments','departments.employees');
        // dd($companies);
        $departments = Department::all();
        $employees = Employee::all();
        $leave_requests = LeaveRequest::latest()->get();
        $leave_types = LeaveType::all();

        return view('back.hrm.leave-request.index',compact('leave_requests','companies','departments','employees','leave_types'));
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
            'department_id' => 'required|exists:departments,id',
            'employee_id' => 'required|exists:employees,id',
            'leave_type_id' => 'required',
            'start_date' => 'required',
            'end_date' => 'required|date|after:start_date',
            'attachment' => 'nullable|image:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if($request->hasFile('attachment')){
            $attachment = $request->file('attachment');
            $attachment_name = time().'.'.$attachment->extension();
            $attachment->move(public_path('uploads/leave-requests'),$attachment_name);
            $request['attachment'] = $attachment_name;
        }

        LeaveRequest::create($request->all());
        return redirect()->back()->with('success','Leave Request created successfully');
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
            'company_id' => 'required|exists:companies,id',
            'department_id' => 'required|exists:departments,id',
            'employee_id' => 'required|exists:employees,id',
            'leave_type_id' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'attachment' => 'nullable|image:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if($request->hasFile('attachment')){
            $attachment = $request->file('attachment');
            $attachment_name = time().'.'.$attachment->extension();
            $attachment->move(public_path('uploads/leave-requests'),$attachment_name);
            $request['attachment'] = $attachment_name;
        }

        LeaveRequest::find($id)->update($request->all());
        return redirect()->back()->with('success','Leave Request updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        LeaveRequest::find($id)->delete();
        return redirect()->back()->with('success','Leave Request deleted successfully');
    }
}
