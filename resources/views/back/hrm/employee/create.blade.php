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
                <h3 class="all-adjustment text-center pb-2 mb-0">Create Employees</h3>
            </div>

            @include('back.layout.errors')

            <div class="card card-shadow border-0 mt-5 rounded-3">

                <div class="table-responsive p-5">
                    <form action="{{ route('employees.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('POST')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mt-2">
                                    <label for="first_name">First Name</label>
                                    <input type="text" class="form-control subheading" name="first_name" id="first_name"
                                        placeholder="First Name" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mt-2">
                                    <label for="last_name">Last Name</label>
                                    <input type="text" class="form-control subheading" name="last_name" id="last_name"
                                        placeholder="Last Name" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mt-2">
                                    <label for="last_name">Gender</label>
                                    <select name="gender" id="gender" name="gender" class="form-control">
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mt-2">
                                    <label for="dob">Date of birth</label>
                                    <input type="date" class="form-control subheading" name="dob" id="dob"
                                        placeholder="Date of birth" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mt-2">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control subheading" name="email" id="email"
                                        placeholder="Email Address" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mt-2">
                                    <label for="country">Country</label>
                                    <input type="country" class="form-control subheading" name="country" id="country"
                                        placeholder="Country" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mt-2">
                                    <label for="phone">Phone</label>
                                    <input type="phone" class="form-control subheading" name="phone" id="phone"
                                        placeholder="Phone" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mt-2">
                                    <label for="joining_date">Joining Date</label>
                                    <input type="date" class="form-control subheading" name="joining_date"
                                        id="joining_date" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mt-2">
                                    <label for="company_id">Company</label>
                                    <select name="company_id" id="company_id" class="form-control">
                                        <option value="">Select Company</option>
                                        @foreach ($companies as $company)
                                            <option value="{{ $company->id }}"
                                                data-departments="{{ json_encode($company->departments) }}"
                                                data-office-shift="{{ json_encode($company->officeShifts) }}">
                                                {{ $company->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mt-2">
                                    <label for="department_id">Department</label>
                                    <select name="department_id" id="department_id" class="form-control">
                                        <option value="">Select Department</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mt-2">
                                    <label for="designation_id">Designation</label>
                                    <select name="designation_id" id="designation_id" class="form-control">
                                        <option value="">Select Designation</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mt-2">
                                    <label for="office_shift_id">Office Shift</label>
                                    <select name="office_shift_id" id="office_shift_id" class="form-control">
                                        <option value="">Select Office Shift</option>
                                    </select>
                                </div>
                            </div>
                        </div>


                        <button class="btn save-btn text-white mt-4">Create</button>
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
        // $(document).ready(function() {

        //     $('#company_id').change(function() {
        //         var departments = $(this).find(':selected').data('departments');
        //         var office_shifts = $(this).find(':selected').data('office-shift');
        //         var departmentSelect = $('#department_id');
        //         var officeShiftSelect = $('#office_shift_id');
        //         departmentSelect.empty();
        //         officeShiftSelect.empty();
        //         $.each(departments, function(key, value) {
        //             departmentSelect.append('<option value=' + value.id + ' data-designations='+ value.designations + '>' + value.name + '</option>');
        //         });
        //         $.each(office_shifts, function(key, value) {
        //             officeShiftSelect.append('<option value=' + value.id + '>' + value.name + '</option>');
        //         });
        //     });

        //     $(document).on('change','#department_id' , function() {
        //         var designations = $(this).find(':selected').data('designations');
        //         var designationSelect = $('#designation_id');
        //         designationSelect.empty();
        //         $.each(designations, function(key, value) {
        //             designationSelect.append('<option value=' + value.id + '>' + value.name + '</option>');
        //         });
        //     });


        // });


        $(document).ready(function() {

            $('#company_id').change(function() {
                var departments = $(this).find(':selected').data('departments');
                var office_shifts = $(this).find(':selected').data('office-shift');
                var departmentSelect = $('#department_id');
                var officeShiftSelect = $('#office_shift_id');

                // Clear previous selections
                departmentSelect.empty().append('<option value="">Select Department</option>');
                officeShiftSelect.empty().append('<option value="">Select Office Shift</option>');
                $('#designation_id').empty().append(
                '<option value="">Select Designation</option>'); // Reset designations

                // Populate departments
                if (departments && departments.length > 0) {
                    $.each(departments, function(key, value) {
                        departmentSelect.append('<option value="' + value.id +
                            '" data-designations=\'' + JSON.stringify(value.designations) +
                            '\'>' + value.name + '</option>');
                    });
                }

                // Populate office shifts
                if (office_shifts && office_shifts.length > 0) {
                    $.each(office_shifts, function(key, value) {
                        officeShiftSelect.append('<option value="' + value.id + '">' + value.name +
                            '</option>');
                    });
                }
            });

            $(document).on('change', '#department_id', function() {
                var designations = $(this).find(':selected').data('designations');
                var designationSelect = $('#designation_id');

                // Clear previous selections
                designationSelect.empty().append('<option value="">Select Designation</option>');

                // Populate designations
                if (designations && designations.length > 0) {
                    $.each(designations, function(key, value) {
                        designationSelect.append('<option value="' + value.id + '">' + value.name +
                            '</option>');
                    });
                }
            });

        });
    </script>
@endsection
