@extends('back.layout.app')
@section('title', 'Categories')
@section('style')
    <link href="{{ asset('back/assets/js/datatable/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" />
    <style>
        .dataTables_paginate {
            display: none;
        }

        .dataTables_length {
            display: none;
        }

        .dataTables_info {
            display: none;
        }
    </style>
@endsection

@section('content')

    <div class="content">

        <div class="container-fluid pt-4 px-4 mb-5">
            <div class="border-bottom">
                <h3 class="all-adjustment text-center pb-2 mb-0">Edit Employees</h3>
            </div>

            @include('back.layout.errors')

            <div class="card card-shadow border-0 mt-5 rounded-3">

                <div class="table-responsive p-5">
                    <form action="{{ route('employees.update', $employee->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mt-2">
                                    <label for="first_name">First Name</label>
                                    <input type="text" class="form-control subheading" name="first_name" id="first_name"
                                        placeholder="First Name" value="{{ old('first_name', $employee->first_name) }}"
                                        required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mt-2">
                                    <label for="last_name">Last Name</label>
                                    <input type="text" class="form-control subheading" name="last_name" id="last_name"
                                        placeholder="Last Name" value="{{ old('last_name', $employee->last_name) }}"
                                        required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mt-2">
                                    <label for="gender">Gender</label>
                                    <select name="gender" id="gender" class="form-control">
                                        <option value="Male"
                                            {{ old('gender', $employee->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                                        <option value="Female"
                                            {{ old('gender', $employee->gender) == 'Female' ? 'selected' : '' }}>Female
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mt-2">
                                    <label for="dob">Date of Birth</label>
                                    <input type="date" class="form-control subheading" name="dob" id="dob"
                                        placeholder="Date of birth" value="{{ old('dob', $employee->dob) }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mt-2">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control subheading" name="email" id="email"
                                        placeholder="Email Address" value="{{ old('email', $employee->email) }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mt-2">
                                    <label for="country">Country</label>
                                    <input type="text" class="form-control subheading" name="country" id="country"
                                        placeholder="Country" value="{{ old('country', $employee->country) }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mt-2">
                                    <label for="phone">Phone</label>
                                    <input type="text" class="form-control subheading" name="phone" id="phone"
                                        placeholder="Phone" value="{{ old('phone', $employee->phone) }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mt-2">
                                    <label for="joining_date">Joining Date</label>
                                    <input type="date" class="form-control subheading" name="joining_date"
                                        id="joining_date" value="{{ old('joining_date', $employee->joining_date) }}"
                                        required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mt-2">
                                    <label for="company_id">Company</label>
                                    <select name="company_id" id="company_id" class="form-control">
                                        <option value="">Select Company</option>
                                        @foreach ($companies as $company)
                                            <option value="{{ $company->id }}" {{ old('company_id', $employee->company_id) == $company->id ? 'selected' : '' }}>
                                                {{ $company->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mt-2">
                                    <label for="department_id">Department</label>
                                    <select name="department_id" id="department_id" class="form-control">
                                        @foreach ($departments as $department)
                                            <option value="{{ $department->id }}" {{ old('department_id', $employee->department_id) == $department->id ? 'selected' : '' }}>
                                                {{ $department->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mt-2">
                                    <label for="designation_id">Designation</label>
                                    <select name="designation_id" id="designation_id" class="form-control">
                                        @foreach ($designations as $designation)
                                            <option value="{{ $designation->id }}" {{ old('designation_id', $employee->designation_id) == $designation->id ? 'selected' : '' }}>
                                                {{ $designation->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mt-2">
                                    <label for="office_shift_id">Office Shift</label>
                                    <select name="office_shift_id" id="office_shift_id" class="form-control">
                                        @foreach ($office_shifts as $shift)
                                            <option value="{{ $shift->id }}" {{ old('office_shift_id', $employee->office_shift_id) == $shift->id ? 'selected' : '' }}>
                                                {{ $shift->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>


                        <button class="btn save-btn text-white mt-4">Update</button>
                    </form>
                </div>


            </div>
        </div>




    </div>
@endsection

@section('scripts')
    <script src="{{ asset('back/assets/js/datatable/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('back/assets/js/datatable/js/dataTables.bootstrap5.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-ajaxy/1.6.1/scripts/jquery.ajaxy.min.js"></script>
    <script>
       $(document).ready(function() {
        var departments = @json($departments);
        var designations = @json($designations);
        var officeShifts = @json($office_shifts);

        var selectedCompanyId = {{ $employee->company_id }};
        var selectedDepartmentId = {{ $employee->department_id }};
        var selectedDesignationId = {{ $employee->designation_id }};
        var selectedOfficeShiftId = {{ $employee->office_shift_id }};

        // Populate departments based on selected company
        function populateDepartments(companyId) {
            var filteredDepartments = departments.filter(dept => dept.company_id == companyId);
            var departmentSelect = $('#department_id');
            departmentSelect.empty().append('<option value="">Select Department</option>');

            $.each(filteredDepartments, function(key, department) {
                var selected = department.id == selectedDepartmentId ? 'selected' : '';
                departmentSelect.append('<option value="' + department.id + '" ' + selected + '>' + department.name + '</option>');
            });
            departmentSelect.trigger('change');
        }

        // Populate designations based on selected department
        function populateDesignations(departmentId) {
            var filteredDesignations = designations.filter(designation => designation.department_id == departmentId);
            var designationSelect = $('#designation_id');
            designationSelect.empty().append('<option value="">Select Designation</option>');

            $.each(filteredDesignations, function(key, designation) {
                var selected = designation.id == selectedDesignationId ? 'selected' : '';
                designationSelect.append('<option value="' + designation.id + '" ' + selected + '>' + designation.name + '</option>');
            });
        }

        // Populate office shifts based on selected company
        function populateOfficeShifts(companyId) {
            var filteredOfficeShifts = officeShifts.filter(shift => shift.company_id == companyId);
            var officeShiftSelect = $('#office_shift_id');
            officeShiftSelect.empty().append('<option value="">Select Office Shift</option>');

            $.each(filteredOfficeShifts, function(key, shift) {
                var selected = shift.id == selectedOfficeShiftId ? 'selected' : '';
                officeShiftSelect.append('<option value="' + shift.id + '" ' + selected + '>' + shift.name + '</option>');
            });
        }

        $('#company_id').change(function() {
            var companyId = $(this).val();
            populateDepartments(companyId);
            populateOfficeShifts(companyId);
        });

        $('#department_id').change(function() {
            var departmentId = $(this).val();
            populateDesignations(departmentId);
        });

        // Trigger the change event on page load to set the initial values
        $('#company_id').val(selectedCompanyId).trigger('change');
    });

    </script>
@endsection
