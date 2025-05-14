@extends('back.layout.app')

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
                <h3 class="all-adjustment text-center pb-2 mb-0">Inventory Stock Count</h3>
            </div>

            <div class="card card-shadow rounded-3 border-0 mt-4">
                <div class="card-body">
                    <form action=""  id="filterStockForm" method="get">
                        @csrf
                        <div class="row">
                            <div class="col-md-11 mt-1">
                                <input type="date" name="date" class="form-control subheading" id="dateInput" value="{{ date('Y-m-d')}}"/>
                            </div>
                            <div class="col-md-1 p-0 mt-1 text-end pe-2">
                                <button class="btn create-btn align-items-center" type="submit" >
                                    Count
                                    {{-- <img src="{{ asset('back/assets/dasheets/img/oui_token-token-count.svg') }}"
                                        alt="" id="c-img"/> --}}
                                        {{-- icons like image  --}}
                                        <i class="bi bi-ui-checks-grid ms-1"></i>

                                        <div class="spinner-border spinner-border-sm ms-1" role="status" id="loader" style="display: none">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card card-shadow border-0 mt-4 rounded-3">
                <div class="card-header bg-white border-0 rounded-3">
                    <div class="row my-3">
                        <div class="col-md-4 col-12">
                            <div class="input-search position-relative">
                                <input type="text" placeholder="Search Table"
                                    class="form-control rounded-3 subheading" id="custom-filter"/>
                                <span class="fa fa-search search-icon text-secondary"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="table-responsive p-2">
                    <table class="table" id="example">
                        <thead>
                            <tr class="fw-bold">
                                <th class="align-middle">Date</th>
                                <th class="align-middle">Warehouse</th>
                                <th class="align-middle">File</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($warehouses as $warehouse)
                            @php
                                $carbonDate = \Carbon\Carbon::parse($warehouse->created_at);
                                $warehouseDate =  $carbonDate->format('d-F-y');
                            @endphp
                                <tr>
                                    <td class="align-middle">{{$warehouseDate}}</td>
                                    <td class="align-middle">{{$warehouse->users->name ?? ''}}</td>
                                    <td class="align-middle text-primary">
                                        <a href="{{route('download-stock-pdf',$warehouse->id)}}">Download</a>
                                    </td>
                                </tr>
                            @endforeach
                            {{-- <tr id="spinner-row" style="display: none">
                                <td colspan='3' class='text-center'>
                                    <div class="spinner-border spinner-border ms-1" role="status" id="loader" >
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </td>
                            </tr> --}}
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
@endsection

@section('scripts')
{{-- <script src="{{ asset('back/assets/js/datatable/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('back/assets/js/datatable/js/dataTables.bootstrap5.min.js') }}"></script> --}}

<script>
    $(document).ready(function() {

        var table = $('#example').DataTable({
            dom: 'Bfrtip',
            // select: true,
            // select: {
            //     style: "multi",
            //     selector: "td:first-child .select-checkbox",
            // },
            buttons: [{
                    extend: 'pdf',
                    footer: true,
                    exportOptions: {
                        columns: [0, 1, 2,]
                    }
                },
                {
                    extend: 'csv',
                    footer: false,
                    exportOptions: {
                        columns: [0, 1, 2,]
                    }

                },
                {
                    extend: 'excel',
                    footer: false,
                    exportOptions: {
                        columns: [0, 1, 2,]
                    }
                }
            ]
        });

        $('#custom-filter').keyup(function() {
            table.search(this.value).draw();
        });
        // $('#download-pdf').on('click', function() {
        //     table.button('.buttons-pdf').trigger();
        // });
        // $('#download-excel').on('click', function() {
        //     table.button('.buttons-excel').trigger();
        // });


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
    <script>
        $(document).ready(function () {
            $('#filterStockForm').on('submit', function (e){
               e.preventDefault();
                const date = $('#dateInput').val();
                $('#c-img').css('display','none');
                $('#loader').css('display','inline-block');
                $('#spinner-row').css('display','table-row');
                // alert(date);
                $.ajax({
                    method: "get",
                    url: `filter-inventory-stock-count/${date}`,
                    data: {date},
                    success: function (response) {
                        if(response.status == 200){
                            var tableBody = $('.table tbody');
                            tableBody.empty();
                            let warehouses = response.data;
                            $.each(warehouses, function (indexInArray, warehouse) {
                                var newRow = '<tr>' +
                                    '<td class="align-middle">' + warehouse.created_at_formatted + '</td>' +
                                    '<td class="align-middle">' + warehouse.users.name + '</td>' +
                                    '<td class="align-middle text-primary"> <a href="/inventory-stock-count/'+warehouse.id+'/download-pdf">Download</a> </td>' +
                                    // Add more columns as needed
                                    '</tr>';

                                tableBody.append(newRow);
                            });
                        }
                        else if (response.status == 404){
                            var tableBody = $('.table tbody');
                            tableBody.empty();
                            var row = "<tr><td colspan='3' class='text-center'>No Record Found</td></tr>";
                            tableBody.append(row);
                        }
                        else
                        {

                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                    },
                    complete: function () {
                        $('#spinner-row').css('display','none');
                        $('#c-img').css('display','inline-block');
                        $('#loader').css('display','none');
                    }
                });
            })
        });
    </script>

@endsection
