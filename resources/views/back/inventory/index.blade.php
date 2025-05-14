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
                                <input type="text" placeholder="Search Bill" class="form-control rounded-3 subheading"
                                    id="custom-filter" />
                                <span class="fa fa-search search-icon text-secondary"></span>
                            </div>
                        </div>

                        <div class="col-md-9 col-12 text-end">
                            <a href="#" class="btn rounded-3 mt-2 excel-btn" id="download-excel">Excel <i
                                    class="bi bi-file-earmark-text"></i></a>
                            <a href="#" class="btn pdf rounded-3 mt-2" id="download-pdf">Pdf <i
                                    class="bi bi-file-earmark"></i></a>
                            {{-- <a href="{{ route('bills.create') }}" class="btn create-btn rounded-3 mt-2">Create <i
                                    class="bi bi-plus-lg"></i></a> --}}
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
                                <th class="text-secondary">Reference</th>
                                <th class="text-secondary">Date</th>
                                <th class="text-secondary">Vendor</th>
                                <th class="text-secondary">Bill Amount</th>
                                <th class="text-secondary">Status</th>
                                <th class="text-secondary">Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($purchases as $bill)
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
                                            href="{{ route('bills.show', $bill->id) }}">{{ $bill->reference }}</a>
                                    </td>
                                    <td class="align-middle">{{ $bill->date }}</td>
                                    <td class="align-middle">{{ $bill->vendor->user->name ?? 'N/A' }}</td>
                                    <td class="align-middle">${{ $bill->grand_total ?? '0.00' }}</td>
                                    <td class="align-middle"><span
                                            class="badges green-border text-center">{{ $bill->payment_status ?? '' }}</span>
                                    </td>
                                    <td class="align-middle">
                                        <div>
                                            <a class="btn btn-secondary bg-transparent border-0 text-dark" role="button"
                                                id="dropdownMenuLink" data-bs-toggle="dropdown" aria-haspopup="true"
                                                aria-expanded="false">
                                                <i class="fa-solid fa-ellipsis-v"></i>
                                            </a>

                                            <div class="dropdown-menu p-2 ps-0" aria-labelledby="dropdownMenuLink">
                                                <?php
                                                    $paidBill = App\Models\PayBill::where('purchase_id',$bill->id)->first();

                                                ?>

                                                <button class="dropdown-item" @if (isset($paidBill)) disabled @endif data-bs-toggle="modal"
                                                    data-bs-target="#exampleModalToggle" data-bill="{{ $bill }}">
                                                    <img src="{{ asset('back/assets/dasheets/img/menu.svg') }}"
                                                        class="img-fluid me-1" alt="" />
                                                    Paybill
                                                </button>
                                                <a class="dropdown-item" href="{{ route('bills.show', $bill->id) }}">
                                                    <img src="{{ asset('back/assets/dasheets/img/menu.svg') }}"
                                                        class="img-fluid me-1" alt="" />
                                                    Detail Bill
                                                </a>
                                                {{-- <a class="dropdown-item" href="{{ route('bills.edit', $bill->id) }}">
                                                    <img src="{{ asset('back/assets/dasheets/img/menu.svg') }}"
                                                        class="img-fluid me-1" alt="" />
                                                    Edit Bill
                                                </a> --}}

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

                                            </div>
                                        </div>

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
        {{-- <div class="modal fade" id="exampleModalToggle" aria-hidden="true" aria-labelledby="exampleModalToggleLabel"
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
        </div> --}}
        <!-- Modal End -->


        <div class="modal fade" id="exampleModalToggle" aria-hidden="true" aria-labelledby="exampleModalToggleLabel"
            tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalToggleLabel">
                            Choose the type of payment to send
                        </h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <nav>
                            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab"
                                    data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home"
                                    aria-selected="true">
                                    Bank Payment (ACH)
                                </button>
                                <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab"
                                    data-bs-target="#nav-profile" type="button" role="tab"
                                    aria-controls="nav-profile" aria-selected="false">
                                    Paper Check
                                </button>
                            </div>
                        </nav>
                        <div class="tab-content" id="nav-tabContent">
                            <div class="tab-pane fade show active" id="nav-home" role="tabpanel"
                                aria-labelledby="nav-home-tab" tabindex="0">
                                <form action="{{ route('bills.store') }}" method="POST">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6 mt-2">
                                            <div class="form-group fw-bold">
                                                <label for="exampleFormControlSelect1">Pay to</label>
                                                <input type="text" class="form-control subheading mt-2" value=""
                                                    name="pay_to" id="exampleFormControlInput1" required/>
                                            </div>
                                            <input type="hidden" name="type" value="Bank">
                                            <input type="hidden" name="purchase_id" class="purchase_id">
                                        </div>
                                        <div class="col-md-6 mt-2">
                                            <div class="form-group fw-bold">
                                                <label for="exampleFormControlSelect1">Email</label>
                                                <input type="text" class="form-control subheading mt-2" name="email"
                                                    id="exampleFormControlInput1" required/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mt-2">
                                            <div class="form-group fw-bold">
                                                <label for="exampleFormControlSelect1">Zip code</label>
                                                <input type="text" class="form-control subheading mt-2"
                                                    id="exampleFormControlInput1" name="zip_code" required/>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mt-2">
                                            <div class="form-group fw-bold">
                                                <label for="exampleFormControlSelect1">State</label>
                                                <input type="text" class="form-control subheading mt-2"
                                                    id="exampleFormControlInput1" name="state" required/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mt-2">
                                            <div class="form-group fw-bold">
                                                <label for="exampleFormControlSelect1">Bank Account Number (5-7)
                                                    digits</label>
                                                <input type="text" class="form-control subheading mt-2"
                                                    id="exampleFormControlInput1" name="account_number" min="5" max="7" required/>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mt-2">
                                            <div class="form-group fw-bold">
                                                <label for="exampleFormControlSelect1">Routing Number 19 digits</label>
                                                <input type="text" class="form-control subheading mt-2"
                                                    id="exampleFormControlInput1" name="routing_number" min="19"/>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mt-2">
                                            <div class="form-group fw-bold">
                                                <label for="exampleFormControlSelect1">Bank Name</label>
                                                <input type="text" class="form-control subheading mt-2"
                                                    placeholder="Bank Name" id="exampleFormControlInput1" name="bank_name" required />
                                            </div>
                                        </div>
                                    </div>
                                    <button class="btn save-btn mt-3 text-white" data-bs-target="#exampleModalToggle2"
                                        data-bs-toggle="modal">
                                        Save
                                    </button>
                                </form>
                            </div>
                            <div class="tab-pane fade" id="nav-profile" role="tabpanel"
                                aria-labelledby="nav-profile-tab" tabindex="0">
                                <form action="{{ route('bills.store') }}" method="POST">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6 mt-2">
                                            <div class="form-group fw-bold">
                                                <label for="exampleFormControlSelect1">Pay to</label>
                                                <input type="text" class="form-control subheading mt-2" value=""
                                                    name="pay_to" id="exampleFormControlInput1" required />
                                            </div>
                                            <input type="hidden" name="type" value="Paper Check">
                                            <input type="hidden" class="purchase_id" name="purchase_id" required>
                                        </div>
                                        <div class="col-md-6 mt-2">
                                            <div class="form-group fw-bold">
                                                <label for="exampleFormControlSelect1">Email</label>
                                                <input type="text" class="form-control subheading mt-2" name="email"
                                                    id="exampleFormControlInput1" required/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mt-2">
                                            <div class="form-group fw-bold">
                                                <label for="exampleFormControlSelect1">Street Address or P.O Box</label>
                                                <input type="text" class="form-control subheading mt-2"
                                                     id="exampleFormControlInput1" name="street_address" required/>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mt-2">
                                            <div class="form-group fw-bold">
                                                <label for="exampleFormControlSelect1">APT, Suit, Unit, Building</label>
                                                <input type="text" class="form-control subheading mt-2"
                                                     id="exampleFormControlInput1" name="full_address"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mt-2">
                                            <div class="form-group fw-bold">
                                                <label for="exampleFormControlSelect1">Zip code</label>
                                                <input type="text" class="form-control subheading mt-2"
                                                    id="exampleFormControlInput1" name="zip_code" required/>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mt-2">
                                            <div class="form-group fw-bold">
                                                <label for="exampleFormControlSelect1">State</label>
                                                <input type="text" class="form-control subheading mt-2"
                                                    id="exampleFormControlInput1" name="state" required/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mt-2">
                                            <div class="form-group fw-bold">
                                                <label for="exampleFormControlSelect1">Bank Account Number (5-7)
                                                    digits</label>
                                                <input type="text" class="form-control subheading mt-2"
                                                    id="exampleFormControlInput1" name="account_number" min="5" max="7"/>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mt-2">
                                            <div class="form-group fw-bold">
                                                <label for="exampleFormControlSelect1">Routing Number 19 digits</label>
                                                <input type="text" class="form-control subheading mt-2"
                                                    id="exampleFormControlInput1" name="routing_number" required/>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mt-2">
                                            <div class="form-group fw-bold">
                                                <label for="exampleFormControlSelect1">Bank Name</label>
                                                <input type="text" class="form-control subheading mt-2"
                                                    value="Bank Name" id="exampleFormControlInput1" name="bank_name" required />
                                            </div>
                                        </div>
                                    </div>
                                    <button class="btn save-btn mt-3 text-white" data-bs-target="#exampleModalToggle2"
                                        data-bs-toggle="modal">
                                        Save
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

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
        $('#exampleModalToggle').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var data = button.data('bill'); // Extract info from data-* attributes
            console.log(data);
            var id = data.id;
            var modal = $(this);
            modal.find('.purchase_id').val(id);

        });





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
                            columns: [0, 1, 2, 3, 4, 5]
                        }
                    },
                    {
                        extend: 'csv',
                        footer: false,
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5]
                        }

                    },
                    {
                        extend: 'excel',
                        footer: false,
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5]
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
