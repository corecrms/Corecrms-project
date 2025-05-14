<?php

namespace App\Http\Controllers\Admin;

use App\Models\Company;
use App\Models\Employee;
use App\Models\Department;
use App\Models\Designation;
use App\Models\OfficeShift;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $employees = Employee::all();
        return view('back.hrm.employee.index',compact('employees'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $companies = Company::all()->load('departments','departments.designations');
        // $departments = Department::all()->load('designations');
        // $designations = Designation::all();
        // $office_shifts = OfficeShift::all();
        return view('back.hrm.employee.create',compact('companies'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'company_id' => 'required|exists:companies,id',
            'department_id' => 'required|exists:departments,id',
            'designation_id' => 'required|exists:designations,id',
            'office_shift_id' => 'required|exists:office_shifts,id',
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'phone' => 'required|numeric',
            'joining_date' => 'required',
            'country' => 'required',
            'dob' => 'required',
            'gender' => 'required',
        ]);
        Employee::create($request->all());
        return redirect()->route('employees.index')->with('success','Employee created successfully.');
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
        $companies = Company::all()->load('departments','departments.designations');
        $departments = Department::all();
        $designations = Designation::all();
        $office_shifts = OfficeShift::all();
        $employee = Employee::find($id);
        return view('back.hrm.employee.edit',compact('companies','departments','designations','office_shifts','employee'));
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
            'designation_id' => 'required|exists:designations,id',
            'office_shift_id' => 'required|exists:office_shifts,id',
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'phone' => 'required|numeric',
            'joining_date' => 'required',
            'country' => 'required',
            'dob' => 'required',
            'gender' => 'required',
        ]);

        $employee = Employee::find($id);
        $employee->update($request->all());
        return redirect()->route('employees.index')->with('success','Employee updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Employee::destroy($id);
        return redirect()->route('employees.index')->with('success','Employee deleted successfully.');
    }


    public function multipleDelete(Request $req)
    {

        foreach ($req->ids as $key => $id) {
            Employee::destroy($id);
        }

        return response()->json(['status' => 200, 'message' => 'Employee deleted successfully!']);
    }
    
}
