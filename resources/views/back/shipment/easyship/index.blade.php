@extends('back.layout.app')
@section('title', 'Shipment')
@section('style')
    <link href="{{ asset('back/assets/js/datatable/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" />
    <style>

        :root{
            --dt-row-selected: 255,255,255;
            --dt-row-selected-text: 0,0,0;
        }
        .dataTables_paginate {
            display: none;
        }

        .dataTables_length {
            display: none;
        }

        .dataTables_info {
            display: none;
        }

        .odd.selected{
            background-color:azure !important;
        }

    </style>
@endsection

@section('content')

    <div class="content">

        <div class="container-fluid pt-4 px-4 mb-5">
            <div class="border-bottom">
                <h3 class="all-adjustment text-center pb-2 mb-0">Shipments</h3>
            </div>

            @include('back.layout.errors')

            <div class="card card-shadow border-0 mt-5 rounded-3">
                <div class="card-header bg-white border-0 rounded-3">
                    <div class="row my-3">
                        <div class="col-md-4 col-12">
                            <div class="input-search position-relative">
                                <input type="text" placeholder="Search Shipment"
                                    class="form-control rounded-3 subheading" id="custom-filter" />
                                <span class="fa fa-search search-icon text-secondary"></span>
                            </div>
                        </div>

                        <div class="col-md-8 col-12 text-end">
                            <button href="#" class="btn rounded-3 mt-2 excel-btn" id="download-excel">
                                Excel <i class="bi bi-file-earmark-text"></i>
                            </button>
                            <button class="btn pdf rounded-3 mt-2" id="download-pdf">
                                Pdf <i class="bi bi-file-earmark"></i>
                            </button>
                            <a href="{{ route('shipment.create') }}" class="btn create-btn rounded-3 mt-2">Create <i
                                class="bi bi-plus-lg"></i></a>
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
                    <table class="table" id="example">
                        <thead class="fw-bold">
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
                                <th>Date</th>
                                <th>Shipment ID</th>
                                <th>Tracking Url</th>
                                <th>Destination Email</th>
                                <th>Total Parcel</th>
                                <th>Total Charges</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="tbody">
                            {{-- <tr class="text-center d-flex justify-content-center align-items-center">
                                 <td colspan="8" rowspan="12">
                                      <div class="spinner-border text-primary" role="status">
                                      <span class="visually-hidden">Loading...</span> </div>
                              </td>
                           </tr> --}}

                        </tbody>
                    </table>
                    <div class="d-flex justify-content-center" >
                        <div class="spinner-border spinner-dark text-primary" role="status" id="loader" style="display:none;">
                            <span class="visually-hidden">Loading...</span> </div>
                    </div>
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
                            columns: [0, 1, 2, 3, 4, 5, 6]
                        }
                    },
                    {
                        extend: 'csv',
                        footer: false,
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6]
                        }
                    },
                    {
                        extend: 'excel',
                        footer: false,
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6]
                        }
                    }
                ],
            });

            // table.rows(rows).select(isSelected); // Select/deselect all rows based on checkbox state
            // });


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
            $('#example tbody').on('click', '.select-checkbox', function(e) {
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
                            url: "{{route('shipments.delete')}}",
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

            // let spinner = `<td colspan="8">
            //     <div class="spinner-border text-primary" role="status">
            //     <span class="visually-hidden">Loading...</span> </div>
            //     </td>`;



            // table.rows.add(spinner).draw();

            // let spinner = `<tr >
            //     <td colspan="8">
            //      <div class="spinner-border text-primary" role="status">
            //      <span class="visually-hidden">Loading...</span> </div>
            //      </td>
            //     </tr>`;

            // table.rows.add(spinner).draw();
            // $('#tbody').textContent = `<tr >
            //      <td colspan="8">
            //       <div class="spinner-border text-primary" role="status">
            //       <span class="visually-hidden">Loading...</span> </div>
            //       </td>
            //      </tr>`;

            $('#loader').show();
            let access_token = "prod_7BdvYJbNSKV5R7Bo8jtkQ8M10ldY7lObKnw5nVSutqo=";
            $.ajax({
                type: "get",
                url: "https://api.easyship.com/2023-01/shipments?easyship_shipment_id=&pickup_state=",
                headers: {
                    accept: 'application/json',
                    authorization: `Bearer ${access_token}`,
                },
                success: function (response) {
                    console.log(response)
                    const shipment = response.shipments.map(shipment => {

                        return [
                            `<label for="myCheckbox09" class="checkbox">
                                <input class="checkbox__input select-checkbox deleteRow" type="checkbox" id="select-checkbox" data-id="${shipment.id}" />
                                <svg class="checkbox__icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 22 22">
                                    <rect width="21" height="21" x=".5" y=".5" fill="#FFF"
                                        stroke="rgba(76, 73, 227, 1)" rx="3" />
                                    <path class="tick" stroke="rgba(76, 73, 227, 1)" fill="none"
                                        stroke-linecap="round" stroke-width="3" d="M4 10l5 5 9-9" />
                                </svg>
                            </label>`,
                            shipment.created_at,
                            shipment.easyship_shipment_id,
                            `<a href="${shipment.tracking_page_url}">Tracking Page</a>`,
                            shipment.destination_address.contact_email,
                            shipment.total_parcel ?? 2,
                            shipment?.rates?.[0]?.rates_in_origin_currency?.total_charge ?? "N/A",
                            shipment.pickup_state,
                            `<a href="/shipment/${shipment.easyship_shipment_id}/edit" class="text-decoration-none btn"><img src="{{ asset('back/assets/dasheets/img/edit-2.svg') }}"
                                                    class="p-0 me-2 ms-0" alt="" /></a>`,
                        ]
                    })

                    table.rows.add(shipment).draw();
                    $('#loader').hide();

                },
                complete: function(){
                    $('#loader').hide();
                }
            });
        });



    </script>
@endsection
