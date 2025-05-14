@extends('back.layout.app')
@section('title', 'Units')
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
                <h3 class="all-adjustment text-center pb-2 mb-0">Unit</h3>
            </div>

            @include('back.layout.errors')

            <div class="card card-shadow border-0 mt-5 rounded-3">
                <div class="card-header bg-white border-0 rounded-3">
                    <div class="row my-3">
                        <div class="col-md-4 col-12">
                            <div class="input-search position-relative">
                                <input type="text" placeholder="Search Units" class="form-control rounded-3 subheading"
                                    id="custom-filter" />
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
                                <th class="text-secondary">Name</th>
                                <th class="text-secondary">Short Name</th>
                                <th class="text-secondary">Base Unit</th>
                                <th class="text-secondary">Operator</th>
                                <th class="text-secondary">Operator Value</th>
                                <th class="text-secondary">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($units as $unit)
                                <tr class="even">
                                    <td class="align-middle">
                                        <label for="select-checkbox" class="checkbox">
                                            <input class="checkbox__input select-checkbox deleteRow" type="checkbox"
                                                id="select-checkbox" data-id="{{$unit->id}}"/>
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
                                        {{ $unit->name }}
                                    </td>
                                    <td class="align-middle">{{ $unit->short_name }}</td>
                                    <td class="text-primary pt-3 align-middle">
                                        @if (isset($unit->baseUnit))
                                            {{ $unit->baseUnit->name }}
                                        @endif
                                    </td>
                                    <td class="text-primary pt-3 align-middle">{{ $unit->operator }}</td>
                                    <td class="text-primary pt-3 align-middle">{{ $unit->operator_value }}</td>
                                    <td class="align-middle">

                                        <div class="d-flex justify-content-start">

                                            <a class=" text-decoration-none btn editUnitBtn"
                                                data-unit_id={{ $unit->id }} data-name="{{ $unit->name }}"
                                                data-short-name={{ $unit->short_name }}
                                                data-baseunit={{ $unit->baseUnit->id ?? 0 }}
                                                data-operator="{{ $unit->operator }}"
                                                data-operator_value="{{ $unit->operator_value }}">
                                                <img src="{{ asset('back/assets/dasheets/img/edit-2.svg') }}"
                                                    class="p-0 me-2 ms-0" alt="" />
                                            </a>

                                            <form class="d-inline delete-category-form" method="post"
                                                action="{{ route('units.destroy', $unit->id) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn text-danger btn-outline-light"
                                                    onclick="return confirm('Are you sure to delete the units?')">
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

    </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModalToggle" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h3 class="all-adjustment text-center pb-2 mb-0 w-50">
                        Create Unit
                    </h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('units.store') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group mt-1">
                            <label for="name">Unit Name</label>
                            <input type="text" name="name" id="name" class="form-control mt-2">
                        </div>
                        <div class="form-group mt-1">
                            <label for="short_name">Short Name</label>
                            <input type="text" name="short_name" id="short_name" class="form-control mt-2">
                        </div>
                        <div class="form-group mt-2">
                            <label for="base_unit" class="form-label">Base Unit</label>
                            <select name="base_unit" id="base_unit" class="form-select mt-2">
                                <option value="">-Select Unit-</option>
                                @foreach ($parentUnit as $unit)
                                    <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div id="hiddenFeilds" style="display: none;">
                            <div class="form-group mt-1">
                                <label for="operator">Operator</label>
                                <select name="operator" id="operator" class="form-select">
                                    <option value="*">Multiply (*)</option>
                                    <option value="/">Divide (/)</option>
                                </select>
                            </div>
                            <div class="form-group mt-1">
                                <label for="operator_value">Operator Value</label>
                                <input type="text" name="operator_value" id="operator_value"
                                    class="form-control mt-2">
                            </div>
                        </div>
                        <button type="submit" class="btn save-btn text-white mt-2">Save</button>
                    </form>
                </div>

            </div>
        </div>
    </div>
    <!-- Modal Edit-->
    <div class="modal fade" id="editModalToggle" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h3 class="all-adjustment text-center pb-2 mb-0 w-50">
                        Edit Unit
                    </h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="mt-4">
                        @csrf
                        @method('POST')
                        <input type="hidden" name="unit_id" id="unit_id">
                        <div class="form-group mt-1">
                            <label for="edit_name">Unit Name</label>
                            <input type="text" name="edit_name" id="edit_name" class="form-control mt-2">
                        </div>
                        <div class="form-group mt-1">
                            <label for="edit_short_name">Short Name</label>
                            <input type="text" name="edit_short_name" id="edit_short_name" class="form-control mt-2">
                        </div>
                        <div class="form-group mt-2">
                            <label for="edit_baseunit" class="form-label">Base Unit</label>
                            <select name="edit_baseunit" id="edit_baseunit" class="form-select mt-2">
                                <option value="">-Select Unit-</option>
                                @foreach ($parentUnit as $punit)
                                    <option value="{{ $punit->id }}"
                                        {{ $punit->id == $unit->parent_id ? 'selected' : '' }}>{{ $punit->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div id="hiddenFeilds">
                            <div class="form-group mt-1">
                                <label for="edit_operator">Operator</label>
                                <select name="edit_operator" id="edit_operator" class="form-select">
                                    <option value="*">Multiply (*)</option>
                                    <option value="/">Divide (/)</option>
                                </select>
                            </div>
                            <div class="form-group mt-1">
                                <label for="edit_operator_value">Operator Value</label>
                                <input type="text" name="edit_operator_value" id="edit_operator_value"
                                    class="form-control mt-2">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary mt-2" id="editSaveBtn">Update</button>
                    </form>
                </div>

            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('back/assets/js/datatable/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('back/assets/js/datatable/js/dataTables.bootstrap5.min.js') }}"></script>

    <script>
        $(document).ready(function() {

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

            // // Select all checkbox click handler
            // $('#myCheckbox09').on('click', function() {
            //     var isSelected = $(this).is(':checked'); // Check if checkbox is checked

            //     // Select/deselect all checkboxes with class 'select-checkbox'
            //     $('.select-checkbox').prop('checked', isSelected);

            //     // Optional: Update DataTables selection based on checkbox state
            //     if (isSelected) {
            //         table.rows().select(); // Select all rows in DataTables (adjust if needed)
            //         // confirm('Are you sure you want to delete all record?');
            //         $('#deletedAlert').css('display','block');
            //         $('#deleteRowCount').text($('.deleteRow:checked').length);


            //     } else {
            //         table.rows().deselect(); // Deselect all rows in DataTables (adjust if needed)
            //         $('#deletedAlert').css('display','none');
            //     }
            // });

            // table.on('select.dt', function (e, dt, type, indexes) {
            //         // console.log("slected")
            //         var row = table.row(indexes[0]); // Get the selected row

            //         // Find checkbox within the selected row
            //         var checkbox = row.node().querySelector('.select-checkbox');

            //         if (checkbox) {  // Check if checkbox exists
            //             // console.log("slected")
            //             checkbox.checked = true; // Check the checkbox
            //             $('#deletedAlert').css('display','block');
            //             $('#deleteRowCount').text($('.deleteRow:checked').length);

            //         }
            // });

            // table.on('deselect.dt', function (e, dt, type, indexes) {
            //     var selectedRows = table.rows('.selected').count();
            //     var row = table.row(indexes[0]); // Get the selected/deselected row
            //     var checkbox = row.node().querySelector('.select-checkbox');

            //     if (checkbox) {
            //         // Update checkbox state based on event type
            //         checkbox.checked = type === 'select';
            //     }
            //     $// Show/hide delete alert based on selection count
            //     if (selectedRows === 0) {
            //         $('#deletedAlert').css('display', 'none');
            //     } else {
            //         $('#deletedAlert').css('display', 'block');
            //         $('#deleteRowCount').text($('.deleteRow:checked').length);
            //     }
            // });

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
                if(confirm("Are you sure you want to proceed? You won't be able to undo this action!")){
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
                            url: "{{route('unit.delete')}}",
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

        $(document).ready(function() {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $(document).on('click', '.delete-unit-link', function(e) {
                e.preventDefault();
                $(this).find('.delete-unit-form').submit();
            });

            $(".delete-unit-form").submit(function() {
                var decision = confirm("Are you sure, You want to Delete this unit?");
                if (decision) {
                    return true;
                }
                return false;
            });


            $('.editUnitBtn').on('click', function() {
                let name = $(this).data('name');
                let unit_id = $(this).data('unit_id');
                let short_name = $(this).data('short-name');
                let baseunit = $(this).data('baseunit');
                let operator = $(this).data('operator');
                let operator_value = $(this).data('operator_value');

                $('#edit_name').val(name);
                $('#unit_id').val(unit_id);
                $('#edit_short_name').val(short_name);
                $('#edit_baseunit').val(baseunit);
                $('#edit_operator').val(operator);
                $('#edit_operator_value').val(operator_value);

                $('#editModalToggle').modal('show');

            });

            $('#editModalToggle form').on('submit', function(e) {
                e.preventDefault();
                $('#editSaveBtn').text('Saving..')
                var name = $('#edit_name').val();
                var unit_id = $('#unit_id').val();
                var short_name = $('#edit_short_name').val();
                var base_unit = $('#edit_baseunit').val();
                var operator = $('#edit_operator').val();
                var operator_value = $('#edit_operator_value').val();

                $.ajax({
                    url: `/units-update/${unit_id}`,
                    type: 'post',
                    data: {
                        name,
                        short_name,
                        base_unit,
                        operator,
                        operator_value
                    },
                    success: function(response) {
                        location.reload();
                        toastr.success("Unit Updated Successfullly!")
                    },
                    completed: function() {
                        $('#editSaveBtn').text('Save')
                        toastr.success("Unit Updated Successfullly!")
                    }
                })
            });



        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let select = document.getElementById('base_unit');
            let fields = document.getElementById('hiddenFeilds');

            select.addEventListener('change', function() {
                if (this.value) {
                    fields.style.display = 'block';
                } else {
                    fields.style.display = 'none';
                }
            })
        })
    </script>
@endsection
