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
                <h3 class="all-adjustment text-center pb-2 mb-0">Holiday</h3>
            </div>

            @include('back.layout.errors')

            <div class="card card-shadow border-0 mt-5 rounded-3">
                <div class="card-header bg-white border-0 rounded-3">
                    <div class="row my-3">
                        <div class="col-md-4 col-12">
                            <div class="input-search position-relative">
                                <input type="text" placeholder="Search Category"
                                    class="form-control rounded-3 subheading" id="custom-filter"/>
                                <span class="fa fa-search search-icon text-secondary"></span>
                            </div>
                        </div>

                        <div class="col-md-8 col-12 text-end">
                            <button class="btn create-btn rounded-3 mt-2" data-bs-target="#exampleModalToggle"
                                data-bs-toggle="modal">
                                Create <i class="bi bi-plus-lg"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="table-responsive p-2">
                    <div class="alert alert-danger p-2 text-end" id="deletedAlert" style="display: none">
                        <div style="display: flex;justify-content:space-between">
                            <span><span id="deleteRowCount">0</span> rows selected</span>
                            <button class="btn btn-sm btn-danger" id="deleteRowTrigger">Delete</button>
                        </div>
                    </div>
                    <table id="example" class="table mb-0">
                        <thead>
                            <tr>
                                <th class="align-middle">
                                    {{-- <label for="myCheckbox09" class="checkbox">
                                        <input class="checkbox__input" type="checkbox" id="myCheckbox09" />
                                        <svg class="checkbox__icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 22 22">
                                            <rect width="21" height="21" x=".5" y=".5" fill="#FFF"
                                                stroke="rgba(76, 73, 227, 1)" rx="3" />
                                            <path class="tick" stroke="rgba(76, 73, 227, 1)" fill="none"
                                                stroke-linecap="round" stroke-width="3" d="M4 10l5 5 9-9" />
                                        </svg>
                                    </label> --}}
                                    No.
                                </th>
                                <th class="align-middle">Holiday</th>
                                <th class="align-middle">Company</th>
                                <th class="align-middle">Start Date</th>
                                <th class="align-middle">Finish Date</th>
                                <th class="sorting">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($holidays as $holiday)
                                <tr>
                                    <td class="align-middle">
                                        {{-- <label for="select-checkbox" class="checkbox">
                                            <input class="checkbox__input select-checkbox deleteRow" type="checkbox"
                                                id="select-checkbox" data-id="{{$holiday->id}}"/>
                                            <svg class="checkbox__icon" xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 22 22">
                                                <rect width="21" height="21" x=".5" y=".5" fill="#FFF"
                                                    stroke="rgba(76, 73, 227, 1)" rx="3" />
                                                <path class="tick" stroke="rgba(76, 73, 227, 1)" fill="none"
                                                    stroke-linecap="round" stroke-width="3" d="M4 10l5 5 9-9" />
                                            </svg>
                                        </label> --}}
                                        {{$loop->iteration ?? ''}}
                                    </td>
                                    <td class="align-middle">
                                        {{$holiday->name ?? '...'}}
                                    </td>
                                    <td  class="align-middle">
                                        {{ $holiday->company->name ?? '...' }}
                                    </td>
                                    <td  class="align-middle">
                                        {{ $holiday->start_date ?? '...' }}
                                    </td>
                                    <td  class="align-middle">
                                        {{ $holiday->end_date ?? '...' }}
                                    </td>
                                    <td class="align-middle">

                                        <div class="d-flex justify-content-start">

                                            <a class=" text-decoration-none btn edit-category-btn" data-bs-toggle="modal"
                                                data-bs-target="#editCategoryModel{{ $holiday->id }}">
                                                <img src="{{ asset('back/assets/dasheets/img/edit-2.svg') }}"
                                                    class="p-0 me-2 ms-0" alt="" />
                                            </a>

                                            <form class="d-inline delete-category-form" method="post"
                                                action="{{ route('holidays.destroy', $holiday->id) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn text-danger">
                                                    <img src="{{ asset('back/assets/dasheets/img/plus-circle.svg') }}"
                                                        class="p-0" data-bs-target="#exampleModalToggle2"
                                                        data-bs-toggle="modal" alt="" />
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="card-footer bg-white border-0 rounded-3">
                    <div class="d-flex justify-content-between p-0">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <label for="rowsPerPage" class="col-form-label">Rows per page:</label>
                            </div>
                            <div class="col-auto">
                                <select id="rowsPerPage" class="form-select border-0">
                                    <option value="3" selected>3</option>
                                    <option value="5">5</option>
                                    <option value="10">10</option>
                                    <option value="25">25</option>
                                </select>
                            </div>
                        </div>
                        <div class="row align-items-center text-end">
                            <div class="col-auto">
                                <p class="subheading col-form-label " id="dataTableInfo">

                                </p>
                            </div>
                            <div class="col-auto">
                                <div class="new-pagination">
                                    <a class="rounded-start paginate_button" style="cursor: pointer"> ❮ </a>
                                    <a class="rounded-end paginate_button page-item next" style="cursor: pointer"> ❯ </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Create Modal STart -->
        <div class="modal fade" id="exampleModalToggle" aria-hidden="true" aria-labelledby="exampleModalToggleLabel"
            tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header border-0">
                        <h3 class="all-adjustment text-center pb-2 mb-0" style="width: 57%;">
                            Create Holiday
                        </h3>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('holidays.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group mt-2">
                                <label for="name">Choose Company</label>
                                <select name="company_id" id="company_id" class="form-control">
                                    <option value="">Select Company</option>
                                    @foreach ($companies as $company)
                                        <option value="{{$company->id}}">
                                            {{$company->name ?? ''}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group mt-2">
                                <label for="title">Title</label>
                                <input type="text" class="form-control subheading" name="name"
                                    id="title" placeholder="Title"  required>
                            </div>
                            <div class="form-group mt-2">
                                <label for="start_date">Start Date</label>
                                <input type="date" class="form-control subheading" name="start_date"
                                    id="email" placeholder="Company Email" required>
                            </div>
                            <div class="form-group mt-2">
                                <label for="end_date">Finsh Date</label>
                                <input type="date" class="form-control subheading" name="end_date"
                                    id="end_date" required>
                            </div>
                            <div class="form-group mt-2">
                                <label for="end_date">Please provide any details</label>
                                <textarea class="form-control" name="details" id="details" cols="30" rows="5"></textarea>
                            </div>


                            <button class="btn save-btn text-white mt-4">Create</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal End -->

        <!-- Edit Modal STart -->
        @foreach ($holidays as $holiday)
            <div class="modal fade" id="editCategoryModel{{ $holiday->id }}" aria-hidden="true"
                aria-labelledby="editCategoryModelToggleLabel" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header border-0">
                            <h3 class="all-adjustment text-center pb-2 mb-0" style="width: 57%;">
                                Edit Holiday
                            </h3>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('holidays.update', $holiday->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT') <!-- This specifies that the form method is PUT, which is used for updates -->

                                <div class="form-group mt-2">
                                    <label for="company_id">Choose Company</label>
                                    <select name="company_id" id="company_id" class="form-control">
                                        <option value="">Select Company</option>
                                        @foreach ($companies as $company)
                                            <option value="{{ $company->id }}" {{ $holiday->company_id == $company->id ? 'selected' : '' }}>
                                                {{ $company->name ?? '' }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group mt-2">
                                    <label for="title">Title</label>
                                    <input type="text" class="form-control subheading" name="name" id="title" placeholder="Title" value="{{ $holiday->name }}" required>
                                </div>

                                <div class="form-group mt-2">
                                    <label for="start_date">Start Date</label>
                                    <input type="date" class="form-control subheading" name="start_date" id="start_date" value="{{ $holiday->start_date }}" required>
                                </div>

                                <div class="form-group mt-2">
                                    <label for="end_date">Finish Date</label>
                                    <input type="date" class="form-control subheading" name="end_date" id="end_date" value="{{ $holiday->end_date }}" required>
                                </div>

                                <div class="form-group mt-2">
                                    <label for="details">Please provide any details</label>
                                    <textarea class="form-control" name="details" id="details" cols="30" rows="5">{{ $holiday->details }}</textarea>
                                </div>

                                <button class="btn save-btn text-white mt-4">Update</button>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        @endforeach


    </div>
@endsection

@section('scripts')
    <script src="{{ asset('back/assets/js/datatable/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('back/assets/js/datatable/js/dataTables.bootstrap5.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-ajaxy/1.6.1/scripts/jquery.ajaxy.min.js"></script>
    <script>
        $(document).ready(function() {
            // $('#example').DataTable();

            // // Custom pagination events
            // $('.prev-page').on('click', function() {
            //     table.page('previous').draw('page');
            // });

            // $('.next-page').on('click', function() {
            //     table.page('next').draw('page');
            // });



            var table = $('#example').DataTable({
                dom: 'Bfrtip',
                select: true,
                select: {
                    style: 'multi',
                    selector: 'td:first-child'
                },
            });

            $('#custom-filter').keyup(function() {
                table.search(this.value).draw();
            });


            // Select all checkbox click handler
            $('#myCheckbox09').on('click', function() {
                var isSelected = $(this).is(':checked'); // Check if checkbox is checked

                // Select/deselect all checkboxes with class 'select-checkbox'
                $('.select-checkbox').prop('checked', isSelected);

                // Optional: Update DataTables selection based on checkbox state
                if (isSelected) {
                    table.rows().select(); // Select all rows in DataTables (adjust if needed)
                    $('#deletedAlert').css('display', 'block');
                    $('#deleteRowCount').text($('.deleteRow:checked').length);
                } else {
                    table.rows().deselect(); // Deselect all rows in DataTables (adjust if needed)
                    $('#deletedAlert').css('display', 'none');
                }
            });

            // Handle click on checkbox to toggle row selection
            $('#example tbody').on('click', 'input[type="checkbox"]', function(e) {
                var $row = $(this).closest('tr');

                // Check the checkbox state and toggle row selection accordingly
                if (this.checked) {
                    table.row($row).select();
                    // $('#myCheckbox09').prop('checked', true);
                } else {
                    table.row($row).deselect();
                    // if ($('.deleteRow:checked').length === 0)
                    //     $('#myCheckbox09').prop('checked', false);

                }

                // Prevent click event from propagating to parent
                e.stopPropagation();
            });

            // Handle click on table cells with .select-checkbox
            $('#example tbody').on('click', 'td.select-checkbox', function(e) {
                $(this).parent().find('input[type="checkbox"]').trigger('click');
            });

            // Update the count and alert display whenever the selection changes
            table.on('select.dt deselect.dt', function() {
                var selectedRows = table.rows('.selected').count();
                if (selectedRows === 0) {
                    $('#deletedAlert').css('display', 'none');
                } else {
                    $('#deletedAlert').css('display', 'block');
                    $('#deleteRowCount').text(selectedRows);
                }
            });

            $('#deleteRowTrigger').on("click", function(event){ // triggering delete one by one
                if(confirm("Are you sure you won't be able to revert this!")){
                    if( $('.deleteRow:checked').length > 0 ){  // at-least one checkbox checked
                        var ids = [];
                        $('.deleteRow').each(function(){
                            if($(this).is(':checked')) {
                                let id = $(this).data('id');
                                ids.push(id);
                            }
                        });
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });

                        $.ajax({
                            type: "POST",
                            url: "",
                            data: {ids},
                            success: function(result) {
                                if(result.status === 200){
                                    toastr.success(result.message)
                                    location.reload();
                                }
                            },
                            async:false
                        });
                    }
                }
            });


            // // Custom pagination events
            $('.new-pagination .paginate_button').on('click', function() {
                if ($(this).hasClass('rounded-start')) {
                    table.page('previous').draw('page');
                } else if ($(this).hasClass('rounded-end')) {
                    table.page('next').draw('page');
                }
            });

            // Handle rows per page change
            $('#rowsPerPage').on('change', function() {
                var rowsPerPage = $(this).val();
                table.page.len(rowsPerPage).draw();
            });

            // Update rows per page select on table draw
            table.on('draw', function() {

                var pageInfo = table.page.info();
                var currentPage = pageInfo.page + 1; // Adding 1 to match human-readable page numbering
                var totalPages = pageInfo.pages;
                var totalRecords = pageInfo.recordsTotal;

                // Calculate start and end records for the current page
                var startRecord = pageInfo.start + 1;
                var endRecord = startRecord + pageInfo.length - 1;
                if (endRecord > totalRecords) {
                    endRecord = totalRecords;
                }

                $('#rowsPerPage').val(table.page.len());
                $('#dataTableInfo').text('Showing ' + startRecord + '-' + endRecord + ' of ' +
                    totalRecords + ' entries');
            });

            table.draw();

            // Custom pagination events
            // $('#prevPage').on('click', function() {
            //     table.page('previous').draw('page');
            // });

            // $('#nextPage').on('click', function() {
            //     table.page('next').draw('page');
            // });

            // // Handle rows per page change
            // $('#rowsPerPage').on('change', function() {
            //     var rowsPerPage = $(this).val();
            //     table.page.len(rowsPerPage).draw();
            // });

            // // Update rows per page select on table draw
            // table.on('draw', function() {
            //     $('#rowsPerPage').val(table.page.len());
            // });

        });


        $(document).ready(function() {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $(document).on('click', '.delete-category-link', function(e) {
                e.preventDefault();
                $(this).find('.delete-category-form').submit();
            });

            $(".delete-category-form").submit(function() {
                var decision = confirm("Are you sure, You want to Delete this company?");
                if (decision) {
                    return true;
                }
                return false;
            });


        });

    </script>
@endsection
