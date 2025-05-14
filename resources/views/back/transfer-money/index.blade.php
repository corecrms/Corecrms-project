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
                <h3 class="all-adjustment text-center pb-2 mb-0">Transfer Money</h3>
            </div>

            @include('back.layout.errors')

            <div class="card card-shadow border-0 mt-5 rounded-3">
                <div class="card-header bg-white border-0 rounded-3">
                    <div class="row my-3">
                        <div class="col-md-4 col-12">
                            <div class="input-search position-relative">
                                <input type="text" placeholder="Search Transfer Money"
                                    class="form-control rounded-3 subheading" id="custom-filter" />
                                <span class="fa fa-search search-icon text-secondary"></span>
                            </div>
                        </div>

                        <div class="col-md-8 col-12 text-end">
                            @can('transfer-money-create')
                                <button class="btn create-btn rounded-3 mt-2" data-bs-target="#createModalToggle"
                                    data-bs-toggle="modal">
                                    Create <i class="bi bi-plus-lg"></i>
                                </button>
                            @endcan
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
                    <table class="table product-table datanew dataTable no-footer" id="example" role="grid"
                        aria-describedby="DataTables_Table_0_info">

                        <thead>

                            <tr>
                                <th class="align-middle">
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
                                <th class="align-middle">Date</th>
                                <th class="align-middle">From Account</th>
                                <th class="align-middle">To Account</th>
                                <th class="align-middle">Amount</th>
                                @can(['transfer-money-edit','transfer-money-delete'])
                                    <th class="align-middle">Action</th>
                                @endcan
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($transferMoney as $transferMoney)
                                <tr class="even">

                                    <td class="align-middle">
                                        <label for="select-checkbox" class="checkbox">
                                            <input class="checkbox__input select-checkbox deleteRow" type="checkbox"
                                                id="select-checkbox" data-id="{{$transferMoney->id}}"/>
                                            <svg class="checkbox__icon" xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 22 22">
                                                <rect width="21" height="21" x=".5" y=".5" fill="#FFF"
                                                    stroke="rgba(76, 73, 227, 1)" rx="3" />
                                                <path class="tick" stroke="rgba(76, 73, 227, 1)" fill="none"
                                                    stroke-linecap="round" stroke-width="3" d="M4 10l5 5 9-9" />
                                            </svg>
                                        </label>
                                    </td>
                                    <td class="align-middle">
                                        {{ $transferMoney->date }}
                                    </td>
                                    <td class="align-middle">
                                        {{ $transferMoney->from_account_id }}
                                    </td>
                                    <td class="align-middle">
                                        {{ $transferMoney->to_account_id }}
                                    </td>
                                    <td class="align-middle">
                                        ${{ $transferMoney->amount }}
                                    </td>
                                    <td class="align-middle">

                                        @can('transfer-money-edit')
                                            <a class=" text-decoration-none btn edit-category-btn" data-bs-toggle="modal"
                                                data-bs-target="#editModalToggle" data-transfer="{{ $transferMoney }}">
                                                <img src="{{ asset('back/assets/dasheets/img/edit-2.svg') }}"
                                                    class="p-0 me-2 ms-0" alt="" />
                                            </a>
                                        @endcan

                                        @can('transfer-money-delete')
                                            <form class="d-inline delete-transfer-money-form" method="post"
                                                action="{{ route('transfer-money.destroy', $transferMoney->id) }}">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit" class="btn text-danger btn-outline-light"
                                                    onclick="return confirm('Are you sure to delete Transfer Money?')">
                                                    <img src="{{ asset('back/assets/dasheets/img/plus-circle.svg') }}"
                                                        class="p-0" alt="" />
                                                </button>
                                            </form>
                                        @endcan

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

        <!-- Modal STart -->
        <div class="modal fade" id="createModalToggle" aria-hidden="true" aria-labelledby="createModalToggleLabel"
            tabindex="-1">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header border-0">
                        <h3 class="all-adjustment text-center pb-2 mb-0" style="width: 57%;">
                            Transfer Money
                        </h3>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="createTransfer" action="{{ route('transfer-money.store') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row mb-3">
                                <div class="col-md-6 mt-2">
                                    <div class="form-group">
                                        <label for="date" class="mb-1">Date <span
                                                class="text-danger">*</span></label>
                                        <input type="date" class="form-control subheading" id="date"
                                            name="date">
                                    </div>
                                </div>
                                <div class="col-md-6 mt-2">
                                    <div class="form-group">
                                        <label for="amount" class="mb-1">Amount <span
                                                class="text-danger">*</span></label>
                                        <input type="number" class="form-control subheading" id="amount"
                                            name="amount" placeholder="Enter Amount" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mt-2">
                                    <div class="form-group">
                                        <label for="from_account_id" class="mb-1 fw-bold">From Account <span
                                                class="text-danger">*</span></label>
                                        <select class="form-control form-select subheading" name="from_account_id"
                                            id="from_account_id" required>
                                            <option disabled>Choose Account</option>
                                            {{-- @forelse ($accounts as $account)
                                                <option value="{{ $account->id }}">{{ $account->name }}</option>
                                            @empty
                                                <option disabled>No Account Found</option>
                                            @endforelse --}}
                                            @foreach ($accounts as $account)
                                                <option value="{{ $account->id }}">{{ $account->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 mt-2">
                                    <div class="form-group">
                                        <label for="to_account_id" class="mb-1 fw-bold">To Account <span
                                                class="text-danger">*</span></label>
                                        <select class="form-control form-select subheading" name="to_account_id"
                                            id="to_account_id" required>
                                            <option disabled>Choose Account</option>
                                            {{-- @forelse ($accounts as $account)
                                                <option value="{{ $account->id }}">{{ $account->name }}</option>
                                            @empty
                                                <option disabled>No Account Found</option>
                                            @endforelse --}}
                                            @foreach ($accounts as $account)
                                                <option value="{{ $account->id }}">{{ $account->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>


                            <button type="submit" class="btn save-btn text-white mt-4">Submit</button>

                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal End -->

        <!-- Edit Modal STart -->
        <div class="modal fade" id="editModalToggle" aria-hidden="true" aria-labelledby="createModalToggleLabel"
            tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header border-0">
                        <h3 class="all-adjustment text-center pb-2 mb-0" style="width: 57%;">
                            Edit Transfer Money
                        </h3>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="editTransfer" action="" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="row mb-3">
                                <div class="col-md-6 mt-2">
                                    <div class="form-group">
                                        <label for="date" class="mb-1">Date <span
                                                class="text-danger">*</span></label>
                                        <input type="date" class="form-control subheading" id="date"
                                            name="date" value="">
                                    </div>
                                </div>
                                <div class="col-md-6 mt-2">
                                    <div class="form-group">
                                        <label for="amount" class="mb-1">Amount <span
                                                class="text-danger">*</span></label>
                                        <input type="number" class="form-control subheading" id="amount"
                                            name="amount" placeholder="Enter Amount" required value="">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mt-2">
                                    <div class="form-group">
                                        <label for="from_account_id" class="mb-1 fw-bold">From Account <span
                                                class="text-danger">*</span></label>
                                        <select class="form-control form-select subheading" name="from_account_id"
                                            id="from_account_id" required>
                                            <option disabled>Choose Account</option>
                                            {{-- @forelse ($accounts as $account)
                                                <option value="{{ $account->id }}">{{ $account->name }}</option>
                                                @empty
                                                <option disabled>No Account Found</option>
                                                @endforelse --}}
                                            @foreach ($accounts as $account)
                                                <option value="{{ $account->id }}">{{ $account->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 mt-2">
                                    <div class="form-group">
                                        <label for="to_account_id" class="mb-1 fw-bold">To Account <span
                                                class="text-danger">*</span></label>
                                        <select class="form-control form-select subheading" name="to_account_id"
                                            id="to_account_id" required>
                                            <option disabled>Choose Account</option>
                                            {{-- @forelse ($accounts as $account)
                                                <option value="{{ $account->id }}">{{ $account->name }}</option>
                                            @empty
                                                <option disabled>No Account Found</option>
                                            @endforelse --}}
                                            @foreach ($accounts as $account)
                                                <option value="{{ $account->id }}">{{ $account->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn save-btn text-white mt-4">Submit</button>

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

    <script>
        $(document).ready(function() {

            // When the form is submitted
            $('#createTransfer, #editTransfer').on('submit', function(e) {
                // Get the values of the from_account_id and to_account_id fields
                var fromAccountId = $(this).find('#from_account_id').val();
                var toAccountId = $(this).find('#to_account_id').val();

                // If they are the same
                if (fromAccountId === toAccountId) {
                    // Prevent the form from being submitted
                    e.preventDefault();

                    // Display an error message
                    alert('The from account and to account must be different.');
                }
            });
            $('#editModalToggle').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget); // Button that triggered the modal
                var data = button.data('transfer'); // Extract info from data-* attributes
                // console.log(data);
                var id = data.id;
                var date = data.date;
                var amount = data.amount;
                var from_account_id = data.from_account_id;
                var to_account_id = data.to_account_id;
                var modal = $(this);
                modal.find('#editTransfer').attr('action', '/transfer-money/' + id);
                modal.find('#date').val(date);
                modal.find('#amount').val(amount);
                modal.find('#from_account_id').val(from_account_id);
                modal.find('#to_account_id').val(to_account_id);
            });

            var table = $('#example').DataTable({
                dom: 'Bfrtip',
                select: true,
                select: {
                    style: 'multi',
                    selector: 'td:first-child .select-checkbox',
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
                            url: "{{route('transfer-money.delete')}}",
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

        });
    </script>
@endsection
