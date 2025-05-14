@extends('back.layout.app')
@section('title', 'Sale Return')
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
                <h3 class="all-adjustment text-center pb-2 mb-0">Sale Return</h3>
            </div>

            <div class="card border-0 card-shadow rounded-3 p-2 mt-5">
                <div class="card-header border-0 bg-white">
                    <div class="row my-3">
                        <div class="col-md-3 col-12 mt-2">
                            <div class="input-search position-relative">
                                <input type="text" placeholder="Search Sale Return"
                                    class="form-control rounded-3 subheading" id="custom-filter" />
                                <span class="fa fa-search search-icon text-secondary"></span>
                            </div>
                        </div>

                        <div class="col-md-9 col-12 text-end">
                            <a href="#" class="btn create-btn rounded-3 mt-2" type="button"
                            data-bs-toggle="offcanvas" data-bs-target="#staticBackdrop"
                            aria-controls="staticBackdrop">Filter <i class="bi bi-funnel"></i></a>
                            <a href="#" class="btn rounded-3 mt-2 excel-btn" id="download-excel">Excel <i
                                    class="bi bi-file-earmark-text"></i></a>
                            <a href="#" class="btn pdf rounded-3 mt-2" id="download-pdf">Pdf <i
                                    class="bi bi-file-earmark"></i></a>
                            <a href="{{ route('manual-sale-return.index') }}" class="btn rounded-3 mt-2 save-btn text-white"
                                id="download-pdf">Manual Return </a>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <div class="alert alert-danger p-2 text-end" id="deletedAlert" style="display: none">
                        <div style="display: flex;justify-content:space-between">
                            <span><span id="deleteRowCount">0</span> rows selected</span>
                            <button class="btn btn-sm btn-danger" id="deleteRowTrigger">Delete</button>
                        </div>
                    </div>
                    <table class="table" id="example">
                        <thead>
                            <tr>
                                <th>
                                    <label for="myCheckbox09" class="checkbox">
                                        <input class="checkbox__input" type="checkbox" id="myCheckbox09" />
                                        <svg class="checkbox__icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 22 22">
                                            <rect width="21" height="21" x=".5" y=".5" fill="#FFF"
                                                stroke="rgba(76, 73, 227, 1)" rx="3" />
                                            <path class="tick" stroke="rgba(76, 73, 227, 1)" fill="none"
                                                stroke-linecap="round" stroke-width="3" d="M4 10l5 5 9-9" />
                                        </svg>
                                    </label>
                                </th>
                                <th class="text-secondary">Date</th>
                                <th class="text-secondary">Refference</th>
                                <th class="text-secondary">Customer</th>
                                <th class="text-secondary">Warehouse</th>
                                <th class="text-secondary">Status</th>
                                <th class="text-secondary">Grand Total</th>
                                <th class="text-secondary">Paid</th>
                                <th class="text-secondary">Due</th>
                                <th class="text-secondary">Payment Status</th>
                                <th class="text-secondary">Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($returns as $return)
                                <tr>
                                    <td class="pt-3">
                                        <label for="select-checkbox" class="checkbox">
                                            <input class="checkbox__input select-checkbox deleteRow" type="checkbox"
                                                id="select-checkbox" data-id="{{ $return->id }}" />
                                            <svg class="checkbox__icon" xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 22 22">
                                                <rect width="21" height="21" x=".5" y=".5" fill="#FFF"
                                                    stroke="rgba(76, 73, 227, 1)" rx="3" />
                                                <path class="tick" stroke="rgba(76, 73, 227, 1)" fill="none"
                                                    stroke-linecap="round" stroke-width="3" d="M4 10l5 5 9-9" />
                                            </svg>
                                        </label>
                                    </td>
                                    <td class="align-middle">{{ $return->date }}</td>
                                    <td class="text-primary align-middle"><a class="text-decoration-none" href="{{route('sale-return.detail', $return->id)}}">{{ $return->reference }}</a></td>
                                    <td class="align-middle">{{ $return->sales->customer->user->name ?? '' }}</td>
                                    <td class="align-middle">{{ $return->sales->warehouse->users->name ?? 'N/A' }}</td>
                                    <td class="align-middle">
                                        <span class="badges green-border text-center">{{ $return->status ?? '' }}</span>
                                    </td>
                                    <td class="align-middle">${{ $return->grand_total ?? '' }}</td>
                                    <td class="align-middle">${{ $return->amount_paid ?? '' }}</td>
                                    <td class="align-middle">${{ $return->amount_due ?? '' }}</td>
                                    <td class="align-middle"><span
                                            class="badges green-border text-center">{{ $return->payment_status ?? '' }}</span>
                                    </td>
                                    {{-- <td class="align-middle">$ {{ $return->amount_recieved ?? '' }}</td>
                                    <td class="align-middle">$ {{ $return->change_return ?? '' }}</td>
                                    <td class="align-middle"><span class="badges green-border text-center">{{ $return->payment_status ?? '' }}</span></td> --}}
                                    <td class="align-middle">
                                        <div>
                                            <a class="btn btn-secondary bg-transparent border-0 text-dark" role="button"
                                                id="dropdownMenuLink" data-bs-toggle="dropdown" aria-haspopup="true"
                                                aria-expanded="false">
                                                <i class="fa-solid fa-ellipsis-v"></i>
                                            </a>

                                            <div class="dropdown-menu p-2 ps-0" aria-labelledby="dropdownMenuLink">
                                                <a class="dropdown-item"
                                                    href="{{ route('sale-return.detail', $return->id) }}">
                                                    <img src="{{ asset('back/assets/dasheets/img/menu.svg') }}"
                                                        class="img-fluid me-1" alt="" />
                                                    Detail Sale Return
                                                </a>
                                                <a class="dropdown-item"
                                                    href="{{ route('sale_return.edit', $return->id) }}">
                                                    <img src="{{ asset('back/assets/dasheets/img/menu.svg') }}"
                                                        class="img-fluid me-1" alt="" />
                                                    Edit Sale Return
                                                </a>

                                                <form id="deleteForm"
                                                    action="{{ route('sale_return.destroy', $return->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="submit" class="dropdown-item confirm-text">
                                                        <img src="{{ asset('back/assets/dasheets/img/menu.svg') }}"
                                                            class="img-fluid me-1" alt="">
                                                        Delete Sale Return
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
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
                            Create Category
                        </h3>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('categories.store') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="exampleFormControlInput1">Category Name</label>
                                <input type="text" class="form-control subheading" name="name"
                                    id="exampleFormControlInput1" placeholder="Name" required>
                            </div>

                            <div class="form-group mt-2">
                                <label for="exampleFormControlInput1">Category Code</label>
                                <input type="text" class="form-control subheading" name="code"
                                    id="exampleFormControlInput1" placeholder="" required>
                            </div>

                            <div class="form-group mt-2">
                                <label for="exampleFormControlTextarea1">Description</label>
                                <textarea class="form-control subheading" id="exampleFormControlTextarea1" name="description" rows="3"
                                    required></textarea>
                            </div>

                            <button class="btn save-btn text-white mt-4">Done</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal End -->

        <div class="offcanvas offcanvas-end" tabindex="-1" id="staticBackdrop" aria-labelledby="staticBackdropLabel"
            style="width: 20rem">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="staticBackdropLabel">Filter</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <form action="{{ route('sale_returns.filter') }}" method="POST">
                    @csrf
                    @method('POST')
                    <div>
                        <div class="form-group">
                            <label for="date">Date</label>
                            <input type="date" class="form-control mt-2" name="date" id="date">
                        </div>
                        <div class="form-group">
                            <label for="reference">Reference</label>
                            <input type="text" class="form-control mt-2" name="reference" id="reference"
                                placeholder="Reference">
                        </div>
                        <div class="form-group">
                            <label for="customer_id">Customer</label>
                            <select class="form-control form-select subheading mt-1" aria-label="Default select example"
                                name="customer_id" id="customer_id">
                                <option value="0">Select Customer</option>
                                @foreach ($customers as $customer)
                                    <option value="{{ $customer->id }}">{{ $customer->user->name ?? ''}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="warehouse_id">Warehouse</label>
                            <select class="form-control form-select subheading mt-1" aria-label="Default select example"
                                name="warehouse_id" id="warehouse_id">
                                <option value="0">Select Warehouse</option>
                                @foreach ($warehouses as $warehouse)
                                    <option value="{{ $warehouse->id }}">{{ $warehouse->users->name ?? '' }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="status" class="mb-1 fw-bold">Status <span class="text-danger">*</span></label>
                            <select class="form-control form-select subheading" aria-label="Default select example"
                                id="status" name="status">
                                <option value="0">Select Status</option>
                                <option value="received">Received</option>
                                <option value="pending">Pending</option>

                            </select>
                        </div>
                        <div class="form-group">
                            <label for="payment_status" class="mb-1 fw-bold">Payment Status</label>
                            <select class="form-control form-select subheading" aria-label="Default select example"
                                id="payment_status" name="payment_status">
                                <option value="0">Select Payment Status</option>
                                <option value="paid">Paid</option>
                                <option value="Unpaid">Unpaid</option>
                                <option value="partial">Partial</option>
                                <option value="pending">Pending</option>
                            </select>
                        </div>

                        <button class="btn save-btn text-white mt-3" type="submit">Filter <i
                                class="bi bi-funnel"></i></button>

                    </div>
                </form>
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

            var table = $('#example').DataTable({
                dom: 'Bfrtip',
                select: true,
                select: {
                    style: 'multi',
                    selector: 'td:first-child .select-checkbox',
                },
                buttons: [{
                        extend: 'pdf',
                        footer: true,
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4]
                        }
                    },
                    {
                        extend: 'csv',
                        footer: false,
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4]
                        }

                    },
                    {
                        extend: 'excel',
                        footer: false,
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4]
                        }
                    }
                ]
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

            $('#deleteRowTrigger').on("click", function(event) { // triggering delete one by one
                if (confirm("Are you sure you won't be able to revert this!")) {
                    if ($('.deleteRow:checked').length > 0) { // at-least one checkbox checked
                        var ids = [];
                        $('.deleteRow').each(function() {
                            if ($(this).is(':checked')) {
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
                            url: "{{ route('sale_return.delete') }}",
                            data: {
                                ids
                            },
                            success: function(result) {
                                if (result.status === 200) {
                                    toastr.success(result.message)
                                    location.reload();
                                }
                            },
                            async: false
                        });
                    }
                }
            });


            $('#custom-filter').keyup(function() {
                table.search(this.value).draw();
            });

            $('#download-pdf').on('click', function() {
                table.button('.buttons-pdf').trigger();
            });
            $('#download-excel').on('click', function() {
                table.button('.buttons-excel').trigger();
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
                var decision = confirm("Are you sure, You want to Delete this category?");
                if (decision) {
                    return true;
                }
                return false;
            });


        });
    </script>
    {{-- <script>
        // Add a click event listener to the anchor tag
        document.getElementById('deleteSaleLink').addEventListener('click', function(event) {
            // Prevent the default behavior of the anchor tag
            // event.preventDefault();
            // Submit the form
            document.getElementById('deleteForm').submit();
        });
    </script> --}}
@endsection
