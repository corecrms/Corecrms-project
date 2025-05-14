@extends('back.layout.app')
@section('title', 'All Bills')
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
                <h3 class="all-adjustment text-center pb-2 mb-0">All Bill</h3>
            </div>

            @include('back.layout.errors')

            <div class="card border-0 card-shadow rounded-3 p-2 mt-5">
                <div class="card-header border-0 bg-white">
                    <div class="row my-3">
                        <div class="col-md-3 col-12 mt-2">
                            <div class="input-search position-relative">
                                <input type="text" placeholder="Search Bill"
                                    class="form-control rounded-3 subheading" id="custom-filter" />
                                <span class="fa fa-search search-icon text-secondary"></span>
                            </div>
                        </div>

                        <div class="col-md-9 col-12 text-end">
                            <a href="#" class="btn rounded-3 mt-2 excel-btn" id="download-excel">Excel <i
                                    class="bi bi-file-earmark-text"></i></a>
                            <a href="#" class="btn pdf rounded-3 mt-2" id="download-pdf">Pdf <i
                                    class="bi bi-file-earmark"></i></a>
                            <a href="{{ route('bills.create') }}" class="btn create-btn rounded-3 mt-2">Create <i
                                    class="bi bi-plus-lg"></i></a>
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
                                <th class="text-secondary">Bill No.</th>
                                <th class="text-secondary">Vendor</th>
                                <th class="text-secondary">Warehouse</th>
                                <th class="text-secondary">Bill Date</th>
                                <th class="text-secondary">Due Date</th>
                                <th class="text-secondary">Grand Total</th>
                                <th class="text-secondary">Status</th>
                                <th class="text-secondary">Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($bills as $bill)
                                <tr>
                                    <td class="pt-3">
                                        <label for="select-checkbox" class="checkbox">
                                            <input class="checkbox__input select-checkbox deleteRow" type="checkbox"
                                                id="select-checkbox" data-id="{{ $bill->id }}" />
                                            <svg class="checkbox__icon" xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 22 22">
                                                <rect width="21" height="21" x=".5" y=".5" fill="#FFF"
                                                    stroke="rgba(76, 73, 227, 1)" rx="3" />
                                                <path class="tick" stroke="rgba(76, 73, 227, 1)" fill="none"
                                                    stroke-linecap="round" stroke-width="3" d="M4 10l5 5 9-9" />
                                            </svg>
                                        </label>
                                    </td>
                                    <td class="text-primary align-middle"><a class="text-decoration-none"
                                            href="{{ route('bills.show', $bill->id) }}">{{ $bill->bill_number }}</a></td>
                                    <td class="align-middle">{{ $bill->vendor->user->name ?? 'N/A' }}</td>
                                    <td class="align-middle">{{ $bill->warehouse->users->name ?? 'N/A' }}</td>
                                    <td class="align-middle">{{ $bill->bill_date ?? 'N/A' }}</td>
                                    <td class="align-middle">{{ $bill->due_date ?? 'N/A' }}</td>
                                    <td class="align-middle"> {{ $bill->grand_total ?? '0' }}</td>
                                    <td class="align-middle"><span
                                            class="badges green-border text-center">{{ $bill->status ?? '' }}</span>
                                    </td>
                                    <td class="align-middle">
                                        <div>
                                            <a class="btn btn-secondary bg-transparent border-0 text-dark" role="button"
                                                id="dropdownMenuLink" data-bs-toggle="dropdown" aria-haspopup="true"
                                                aria-expanded="false">
                                                <i class="fa-solid fa-ellipsis-v"></i>
                                            </a>

                                            <div class="dropdown-menu p-2 ps-0" aria-labelledby="dropdownMenuLink">
                                                <a class="dropdown-item" href="{{ route('bills.show', $bill->id) }}">
                                                    <img src="{{ asset('back/assets/dasheets/img/menu.svg') }}"
                                                        class="img-fluid me-1" alt="" />
                                                    Detail Bill
                                                </a>
                                                <a class="dropdown-item" href="{{ route('bills.edit', $bill->id) }}">
                                                    <img src="{{ asset('back/assets/dasheets/img/menu.svg') }}"
                                                        class="img-fluid me-1" alt="" />
                                                    Edit Bill
                                                </a>

                                                <form id="deleteForm" action="{{ route('bills.destroy', $bill->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="submit" class="dropdown-item confirm-text">
                                                        <img src="{{ asset('back/assets/dasheets/img/menu.svg') }}"
                                                            class="img-fluid me-1" alt="">
                                                        Delete Bill
                                                    </button>
                                                </form>
                                                <a class="dropdown-item" href="#"
                                                data-bs-toggle="modal"
                                                data-bs-target="#exampleModalToggle" data-bill="{{ $bill }}">
                                                    <img src="{{ asset('back/assets/dasheets/img/menu.svg') }}"
                                                        class="img-fluid me-1" alt="" />
                                                    Add Payment
                                                </a>
                                            </div>
                                        </div>
                                        {{-- <td class="align-middle">
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
                                    </td> --}}
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



        <!-- Modal STart -->
        <div class="modal fade" id="exampleModalToggle" aria-hidden="true" aria-labelledby="exampleModalToggleLabel"
            tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header border-0">
                        <h3 class="all-adjustment text-center pb-2 mb-0" style="width: 57%;">
                            Add Payment
                        </h3>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="createTransfer" action="{{ route('add-payment.store') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row mb-2">

                                <div class="form-group">
                                    <label for="date" class="mb-1">Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control subheading" id="date" name="date"
                                        value="{{ date('Y-m-d') }}">
                                </div>
                                <input type="hidden" name="bill_id" id="bill_id" value="">

                            </div>
                            <div class="form-group">
                                <label for="amount" class="mb-1">Amount <span class="text-danger">*</span></label>
                                <input type="number" class="form-control subheading" id="amount" name="amount"
                                    placeholder="Enter Amount" required>
                            </div>
                            <div class="row">

                                <div class="form-group">
                                    <label for="from_account_id" class="mb-1 fw-bold">Account <span
                                            class="text-danger">*</span></label>
                                    <select class="form-control form-select subheading" name="account_id"
                                        id="from_account_id" required>
                                        <option disabled>Choose Account</option>
                                        @forelse ($accounts as $account)
                                            <option value="{{ $account->id }}">{{ $account->name }}</option>
                                        @empty
                                            <option disabled>No Account Found</option>
                                        @endforelse
                                    </select>
                                </div>

                            </div>
                            <div class="form-group mt-2">
                                <label for="details" class="mb-1 fw-bold">Description</label>
                                <textarea class="form-control subheading" id="description" name="description" placeholder="A few words"
                                    rows="5"></textarea>
                            </div>


                            <button type="submit" class="btn save-btn text-white mt-2">Submit</button>

                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal End -->



    </div>
@endsection

@section('scripts')
    <script src="{{ asset('back/assets/js/datatable/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('back/assets/js/datatable/js/dataTables.bootstrap5.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-ajaxy/1.6.1/scripts/jquery.ajaxy.min.js"></script>
    <script>
        $('#exampleModalToggle').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var data = button.data('bill'); // Extract info from data-* attributes
            console.log(data);
            var id = data.id;
            var date = data.bill_date;
            var amount = data.grand_total;
            var modal = $(this);
            modal.find('#bill_id').val(id);
            modal.find('#date').val(date);
            modal.find('#amount').val(amount);
        });





        $(document).ready(function() {

            var table = $('#example').DataTable({
                dom: 'Bfrtip',
                select: true,
                select: {
                    style: 'multi'
                },
                buttons: [{
                        extend: 'pdf',
                        footer: true,
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4,5,6,7]
                        }
                    },
                    {
                        extend: 'csv',
                        footer: false,
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4,5,6,7]
                        }

                    },
                    {
                        extend: 'excel',
                        footer: false,
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4,5,6,7]
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
                    // confirm('Are you sure you want to delete all record?');
                    $('#deletedAlert').css('display', 'block');
                    $('#deleteRowCount').text($('.deleteRow:checked').length);


                } else {
                    table.rows().deselect(); // Deselect all rows in DataTables (adjust if needed)
                    $('#deletedAlert').css('display', 'none');
                }
            });

            table.on('select.dt', function(e, dt, type, indexes) {
                // console.log("slected")
                var row = table.row(indexes[0]); // Get the selected row

                // Find checkbox within the selected row
                var checkbox = row.node().querySelector('.select-checkbox');

                if (checkbox) { // Check if checkbox exists
                    // console.log("slected")
                    checkbox.checked = true; // Check the checkbox
                    $('#deletedAlert').css('display', 'block');
                    $('#deleteRowCount').text($('.deleteRow:checked').length);

                }
            });

            table.on('deselect.dt', function(e, dt, type, indexes) {
                var selectedRows = table.rows('.selected').count();
                var row = table.row(indexes[0]); // Get the selected/deselected row
                var checkbox = row.node().querySelector('.select-checkbox');

                if (checkbox) {
                    // Update checkbox state based on event type
                    checkbox.checked = type === 'select';
                }
                $ // Show/hide delete alert based on selection count
                if (selectedRows === 0) {
                    $('#deletedAlert').css('display', 'none');
                } else {
                    $('#deletedAlert').css('display', 'block');
                    $('#deleteRowCount').text($('.deleteRow:checked').length);
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
                            url: "{{ route('bill.delete') }}",
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
