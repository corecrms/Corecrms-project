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
                <h3 class="all-adjustment text-center pb-2 mb-0">Taxes</h3>
            </div>

            @include('back.layout.errors')

            <div class="card card-shadow border-0 mt-5 rounded-3">
                <div class="card-header bg-white border-0 rounded-3">
                    <div class="row my-3">
                        <div class="col-md-4 col-12">
                            <div class="input-search position-relative">
                                <input type="text" placeholder="Search Taxes"
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
                    <table class="table product-table datanew dataTable no-footer" id="example" role="grid"
                        aria-describedby="DataTables_Table_0_info">
                        <thead>
                            <tr role="row">
                                <th><strong>No.</strong></th>
                                <th >Tax Type</th>

                                <th >Tax Information </th>

                                <th >Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($taxes as $tax)
                                <tr class="even">
                                    <td class="align-middle">{{$loop->iteration}}</td>

                                    <td class="align-middle">
                                        {{ $tax->name }}
                                    </td>
                                    <td class="align-middle">{{ $tax->information }}</td>
                                    <td class="align-middle">
                                        <a class="text-decoration-none text-secondary btn" data-bs-toggle="modal"
                                            data-bs-target="#editTaxModel{{ $tax->id }}">
                                            <img src="{{ asset('back/assets/dasheets/img/edit-2.svg') }}"
                                                class="p-0 me-2 ms-0" alt="" />
                                        </a>
                                        <form class="d-inline delete-unit-form" method="post"
                                            action="{{ route('taxes.destroy', $tax->id) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn text-danger btn-outline-light"
                                                onclick=" return confirm('Are you sure, You want to Delete this tax?')">
                                                <img src="{{ asset('back/assets/dasheets/img/plus-circle.svg') }}"
                                                    class="p-0" data-bs-target="#exampleModalToggle2"
                                                    data-bs-toggle="modal" alt="" />
                                            </button>
                                        </form>
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
                            Create Tax
                        </h3>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('taxes.store') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group mt-1">
                                <label for="name">Tax Type</label>
                                <input type="text" name="name" id="name" class="form-control mt-2">
                            </div>
                            <div class="form-group mt-1">
                                <label for="information">Tax Information</label>
                                <input type="text" name="information" id="information" class="form-control mt-2">
                            </div>

                            <button type="submit" class="btn save-btn text-white mt-2">Save</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal End -->

        <!-- Edit Modal STart -->
        @foreach ($taxes as $tax)
            <div class="modal fade" id="editTaxModel{{ $tax->id }}" aria-hidden="true"
                aria-labelledby="editCategoryModelToggleLabel" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header border-0">
                            <h3 class="all-adjustment text-center pb-2 mb-0" style="width: 57%;">
                                Edit Tax
                            </h3>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('taxes.update', $tax->id) }}" method="post"
                                enctype="multipart/form-data" class="mt-4">
                                @csrf
                                @method('PUT')
                                <div class="form-group mt-1">
                                    <label for="name">Tax Type</label>
                                    <input type="text" name="name" id="name" class="form-control mt-2"
                                        value="{{ $tax->name }}">
                                </div>
                                <div class="form-group mt-1">
                                    <label for="information">Tax Information</label>
                                    <input type="text" name="information" id="information" class="form-control mt-2"
                                        value="{{ $tax->information }}">
                                </div>
                                <button type="submit" class="btn save-btn text-white mt-2">Save</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
        <!-- Modal End -->
    </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Create Tax</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('taxes.store') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group mt-1">
                            <label for="name">Tax Type</label>
                            <input type="text" name="name" id="name" class="form-control mt-2">
                        </div>
                        <div class="form-group mt-1">
                            <label for="information">Tax Information</label>
                            <input type="text" name="information" id="information" class="form-control mt-2">
                        </div>

                        <button type="submit" class="btn save-btn text-white mt-2">Save</button>
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

            var table = $('#example').DataTable();

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
