@extends('back.layout.app')
@section('title', 'Users')
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
                <h3 class="all-adjustment text-center pb-2 mb-0">All Users</h3>
            </div>

            @include('back.layout.errors')

            <div class="card card-shadow border-0 mt-5 rounded-3">
                <div class="card-header bg-white border-0 rounded-3">
                    <div class="row my-3">
                        <div class="col-md-4 col-12">
                            <div class="input-search position-relative">
                                <input type="text" placeholder="Search User" class="form-control rounded-3 subheading"
                                    id="custom-filter" />
                                <span class="fa fa-search search-icon text-secondary"></span>
                            </div>
                        </div>

                        <div class="col-md-8 col-12 text-end">
                            <a href="#" class="btn create-btn rounded-3 mt-2" type="button"
                                data-bs-toggle="offcanvas" data-bs-target="#staticBackdrop"
                                aria-controls="staticBackdrop">Filter <i class="bi bi-funnel"></i></a>
                            <a href="#" class="btn border-danger text-danger rounded-3 mt-2 excel-btn"
                                id="download-excel">Excel <i class="bi bi-file-earmark-text"></i></a>
                            <a href="#" class="btn pdf rounded-3 mt-2" id="download-pdf">Pdf <i
                                    class="bi bi-file-earmark"></i></a>
                            {{-- <a href="#" class="btn import-customer-btn rounded-3 mt-2">Import Users <i
                                    class="bi bi-download"></i></a> --}}
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
                                <th class="sorting">Name</th>
                                <th class="sorting">Email </th>
                                <th class="sorting">Phone </th>
                                <th class="sorting">Roles</th>
                                <th class="sorting">Assign Warehouse</th>
                                <th class="sorting">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($data as $user)
                                @if ($user->hasRole(['Admin', 'Manager', 'Cashier']))
                                    <tr>
                                        <td class="align-middle">
                                            <label for="select-checkbox" class="checkbox">
                                                <input class="checkbox__input select-checkbox deleteRow" type="checkbox"
                                                    id="select-checkbox" data-id="{{ $user->id }}" />
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
                                            {{ $user->name }}
                                        </td>
                                        <td class="align-middle"> {{ $user->email }}</td>
                                        <td class="align-middle"> {{ $user->contact_no ?? '...' }}</td>
                                        <td class="align-middle">
                                            @if (!empty($user->getRoleNames()))
                                            @foreach ($user->getRoleNames() as $v)
                                                    <span>{{ $v }}</span>
                                                @endforeach
                                            @endif
                                        </td>
                                        <td class="align-middle"> {{ $user->assignWarehouse?->users->name ?? '...' }}</td>
                                        <td class="align-middle">
                                            <div class="d-flex justify-content-start">

                                                <a class=" text-decoration-none btn edit-category-btn"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editUserModel{{ $user->id }}">
                                                    <img src="{{ asset('back/assets/dasheets/img/edit-2.svg') }}"
                                                        class="p-0 me-2 ms-0" alt="" />
                                                </a>

                                                <form class="d-inline delete-category-form" method="post"
                                                    action="{{ route('users.destroy', $user->id) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn text-danger btn-outline-light">
                                                        <img src="{{ asset('back/assets/dasheets/img/plus-circle.svg') }}"
                                                            class="p-0" data-bs-target="#exampleModalToggle2"
                                                            data-bs-toggle="modal" alt="" />
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
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
                            Create Users
                        </h3>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        {!! Form::open(['route' => 'users.store', 'method' => 'POST']) !!}

                        <div class=" mb-3">
                            <label class="form-label">Name:</label>
                            {!! Form::text('name', null, ['placeholder' => 'Name', 'class' => 'form-control']) !!}

                        </div>
                        <div class=" mb-3">

                            <label class="form-label">Email:</label>
                            {!! Form::text('email', null, ['placeholder' => 'Email', 'class' => 'form-control']) !!}

                        </div>
                        <div class=" mb-3">

                            <label class="form-label">Password:</label>
                            {!! Form::password('password', ['placeholder' => 'Password', 'class' => 'form-control']) !!}

                        </div>
                        <div class=" mb-3">

                            <label class="form-label">Confirm Password:</label>
                            {!! Form::password('confirm-password', ['placeholder' => 'Confirm Password', 'class' => 'form-control']) !!}

                        </div>
                        <div class=" mb-3">

                            <label class="form-label">Role:</label>
                            {{-- {!! Form::select('roles', $roles,[], ['class' => 'form-control']) !!} --}}
                            <select name="roles" class="form-control" id="createRole">
                                <option value="" selected disabled>Select Role</option>

                                @foreach ($roles as $role)
                                    <option value="{{ $role }}">{{ $role }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3 " style="display: none;" id="warehouseSelector">

                            <label class="form-label">Select Warehouse:</label>
                            <select name="warehouse_id" class="form-control" id="warehouse" required>
                                <option value="" selected disabled>Select Warehouse</option>
                                @foreach ($warehouses as $warehouse)
                                    <option value="{{ $warehouse->id }}">{{ $warehouse->users->name ?? '' }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button class="btn save-btn text-white ">Done</button>
                        {!! Form::close() !!}


                    </div>
                </div>
            </div>
        </div>
        <!-- Modal End -->

        <!-- Edit Modal STart -->
        <!-- Edit Modal Start -->
        @foreach ($data as $user)
            <div class="modal fade" id="editUserModel{{ $user->id }}" aria-hidden="true"
                aria-labelledby="editCategoryModelToggleLabel" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header border-0">
                            <h3 class="all-adjustment text-center pb-2 mb-0" style="width: 57%;">
                                Edit User
                            </h3>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            {!! Form::model($user, ['method' => 'PATCH', 'route' => ['users.update', $user->id]]) !!}
                            <div class="mb-3">
                                <label class="form-label">Name:</label>
                                {!! Form::text('name', null, ['placeholder' => 'Name', 'class' => 'form-control']) !!}
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email:</label>
                                {!! Form::text('email', null, ['placeholder' => 'Email', 'class' => 'form-control']) !!}
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Password:</label>
                                {!! Form::password('password', ['placeholder' => 'Password', 'class' => 'form-control']) !!}
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Confirm Password:</label>
                                {!! Form::password('confirm-password', ['placeholder' => 'Confirm Password', 'class' => 'form-control']) !!}
                            </div>
                            <div class="col-md-12">
                                @php
                                    $userRole = $user->roles->pluck('name', 'name')->all();
                                @endphp
                                <label class="form-label">Role:</label>
                                <select name="roles" class="form-control editRole" data-user-id="{{ $user->id }}">
                                    <option value="" disabled>Select Role</option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role }}"
                                            {{ $user->hasRole($role) ? 'selected' : '' }}>
                                            {{ $role }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3 warehouseSelector" id="warehouseSelector{{ $user->id }}"
                                style="{{ in_array($userRole, ['Cashier', 'Manager']) ? '' : 'display: none;' }}">
                                <label class="form-label">Select Warehouse:</label>
                                <select name="warehouse_id" class="form-control">
                                    <option value="" selected disabled>Select Warehouse</option>
                                    @foreach ($warehouses as $warehouse)
                                        <option value="{{ $warehouse->id }}" {{$warehouse->id == $user->warehouse_id ? 'selected':''}} >{{ $warehouse->users->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <button type="submit" class="btn save-btn text-white mt-2">Submit</button>
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
        <!-- Edit Modal End -->


        <!-- Modal End -->
        {{-- <!-- Edit Modal STart -->
        <div class="modal fade" id="editCategoryModel" aria-hidden="true" aria-labelledby="editCategoryModelToggleLabel"
            tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header border-0">
                        <h3 class="all-adjustment text-center pb-2 mb-0" style="width: 57%;">
                            Edit Category
                        </h3>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form >
                            @csrf
                            <input type="hidden" id="cate_id_input">
                            <div class="form-group">
                                <label for="cate_name_input">Category Name</label>
                                <input type="text" class="form-control subheading" name="name"
                                    id="cate_name_input" placeholder="Name" required >
                            </div>

                            <div class="form-group mt-2">
                                <label for="cate_code_input">Category Code</label>
                                <input type="text" class="form-control subheading" name="code"
                                    id="cate_code_input" placeholder="" required>
                            </div>

                            <div class="form-group mt-2">
                                <label for="cate_desc_input">Description</label>
                                <textarea class="form-control subheading" id="cate_desc_input" name="description" rows="3" required></textarea>
                            </div>

                            <button class="btn save-btn text-white mt-4">Done</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal End --> --}}

        <div class="offcanvas offcanvas-end" tabindex="-1" id="staticBackdrop" aria-labelledby="staticBackdropLabel"
            style="width: 20rem">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="staticBackdropLabel">Filter</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <form action="{{ route('users.filter') }}" method="POST">
                    @csrf
                    @method('POST')
                    <div>
                        <div class="form-group">
                            <label for="name">Username</label>
                            <input type="text" class="form-control mt-2" name="name" id="name"
                                placeholder="User Name">
                        </div>
                        <div class="form-group">
                            <label for="phone">Phone</label>
                            <input type="text" class="form-control mt-2" name="phone" id="phone"
                                placeholder="User phone">
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="text" class="form-control mt-2" name="email" id="email"
                                placeholder="User Email">
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
                    selector: 'td:first-child .select-checkbox',
                },
                buttons: [{
                        extend: 'pdf',
                        footer: true,
                        exportOptions: {
                            columns: [0, 1, 2, 3, ]
                        }
                    },
                    {
                        extend: 'csv',
                        footer: false,
                        exportOptions: {
                            columns: [0, 1, 2, 3, ]
                        }

                    },
                    {
                        extend: 'excel',
                        footer: false,
                        exportOptions: {
                            columns: [0, 1, 2, 3, ]
                        }
                    }
                ]
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
                            url: "{{ route('users.delete') }}",
                            data: {
                                ids
                            },
                            success: function(result) {
                                if (result.status === 200) {
                                    location.reload();
                                }
                            },
                            async: false
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


            $(".delete-category-form").submit(function() {
                var decision = confirm("Are you sure, You want to delete this user?");
                if (decision) {
                    return true;
                }
                return false;
            });


            $(document).on('change', '#createRole', function() {
                if (this.value == 'Manager' || this.value == 'Cashier') {
                    $('#warehouseSelector').show();
                } else {
                    $('#warehouseSelector').hide();
                }

            });


            document.querySelectorAll('.editRole').forEach(function(selectElement) {
                const userId = selectElement.getAttribute('data-user-id');
                const warehouseSelector = document.getElementById('warehouseSelector' + userId);

                // Set initial visibility based on pre-selected role
                toggleWarehouseVisibility(selectElement.value, warehouseSelector);

                // Add event listener for change in role selection
                selectElement.addEventListener('change', function() {
                    toggleWarehouseVisibility(this.value, warehouseSelector);
                });
            });

            function toggleWarehouseVisibility(role, warehouseSelector) {
                if (role === 'Cashier' || role === 'Manager') {
                    warehouseSelector.style.display = 'block';
                } else {
                    warehouseSelector.style.display = 'none';
                }
            }


        });
    </script>

@endsection
