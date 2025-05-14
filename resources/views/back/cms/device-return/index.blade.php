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
            <div class="breturn-bottom">
                <h3 class="all-adjustment text-center pb-2 mb-0">Device Return</h3>
            </div>

            @include('back.layout.errors')

            <div class="card card-shadow breturn-0 mt-5 rounded-3">
                <div class="card-header bg-white border-0 rounded-3">
                    <div class="row my-3">
                        <div class="col-md-4 col-12">
                            <div class="input-search position-relative">
                                <input type="text" placeholder="Search"
                                    class="form-control rounded-3 subheading" id="custom-filter"/>
                                <span class="fa fa-search search-icon text-secondary"></span>
                            </div>
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
                    <table class="table " id="example" >
                        <thead>
                            <tr>
                                <th>No#</th>
                                <th>IMEI</th>
                                <th>Shipping Method</th>
                                <th>Tracking Number</th>
                                <th>Reference Number</th>
                                <th>Address</th>
                                <th>Comments</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($returns as $return)
                                <tr>
                                    <td class="align-middle">{{ $loop->iteration ?? '' }}</td>
                                    <td class="align-item">{{ $return->imei_number ?? '' }}</td>
                                    <td class="align-item">{{ $return->shipping_method ?? '' }}</td>
                                    <td class="align-item">{{ $return->tracking_number ?? '...' }}</td>
                                    <td class="align-item">{{ $return->reference_number ?? '...' }}</td>
                                    <td class="align-item">{{ $return->address ?? '...' }}</td>
                                    <td class="align-item">{{ $return->comment ?? '...' }}</td>
                                    <td class="align-item">
                                        <h6>
                                            <span class="badge text-bg-primary">
                                                {{ $return->status ?? '' }}
                                            </span>
                                        </h6>
                                    </td>
                                    <td class="align-middle">
                                        <form class="d-inline delete-category-form" method="post"
                                            action="{{ route('device-return.destroy', $return->id) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn text-danger" onclick="return confirm('Are you sure to delete this record.')">
                                                <img src="{{ asset('back/assets/dasheets/img/plus-circle.svg') }}"
                                                    class="p-0" data-bs-target="#exampleModalToggle2"
                                                    data-bs-toggle="modal" alt="" />
                                            </button>
                                        </form>
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



    </div>
@endsection

@section('scripts')
    <script src="{{ asset('back/assets/js/datatable/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('back/assets/js/datatable/js/dataTables.bootstrap5.min.js') }}"></script>

    <script>
        $(document).ready(function() {

            var table = $('#example').DataTable({
                dom: 'Bfrtip',
            });

            $('#custom-filter').keyup(function() {
                table.search(this.value).draw();
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
