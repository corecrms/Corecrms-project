@extends('back.layout.app')
@section('title', 'Tiers')
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
                <h3 class="all-adjustment text-center pb-2 mb-0">All Tiers</h3>
            </div>

            @include('back.layout.errors')

            <div class="card card-shadow border-0 mt-5 rounded-3">
                <div class="card-header bg-white border-0 rounded-3">
                    <div class="row my-3">
                        <div class="col-md-4 col-12">
                            <div class="input-search position-relative">
                                <input type="text" placeholder="Search Tier"
                                    class="form-control rounded-3 subheading" id="custom-filter"/>
                                <span class="fa fa-search search-icon text-secondary"></span>
                            </div>
                        </div>

                        <div class="col-md-8 col-12 text-end">
                            @can('tier-create')
                                <button class="btn create-btn rounded-3 mt-2" data-bs-target="#exampleModalToggle"
                                    data-bs-toggle="modal">
                                    Create <i class="bi bi-plus-lg"></i>
                                </button>
                            @endcan
                        </div>
                    </div>
                </div>
                <div class="table-responsive p-2">
                    <table class="table product-table datanew dataTable no-footer" id="example" role="grid"
                        aria-describedby="DataTables_Table_0_info">
                        <thead>
                            <tr role="row">

                                <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1"
                                colspan="1" aria-label="Product Name: activate to sort column ascending"
                                style="width: 138.25px;">#</th>
                                <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1"
                                    colspan="1" aria-label="Product Name: activate to sort column ascending"
                                    style="width: 138.25px;">
                                    Name
                                </th>
                                {{-- <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1"
                                    colspan="1" aria-label="Product Name: activate to sort column ascending"
                                    style="width: 138.25px;">
                                    Tier Type (%)
                                </th> --}}
                                <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1"
                                    colspan="1" aria-label="Product Name: activate to sort column ascending"
                                    style="width: 138.25px;">
                                    Discount (%)
                                </th>
                                @canany(['tier-edit','tier-delete'])
                                    <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1"
                                        colspan="1" aria-label="Action: activate to sort column ascending"
                                        style="width: 94.3438px;">Action</th>
                                @endcan
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($tiers as $tier)
                                <tr class="">
                                    <td class="align-middle">
                                        {{$loop->iteration ?? ''}}
                                    </td>
                                    <td  class="align-middle">
                                        {{ $tier->name ?? '' }}
                                    </td>
                                    {{-- <td class="productimgname">
                                        {{ $tier->tier_type ?? '' }}
                                    </td> --}}
                                    <td class="align-middle">{{ $tier->discount }}</td>

                                    <td class="align-middle">
                                        @can('tier-edit')
                                            <a class="text-decoration-none text-secondary btn" data-bs-toggle="modal"
                                                data-bs-target="#editTierModel{{ $tier->id }}">
                                                <img src="{{ asset('back/assets/dasheets/img/edit-2.svg') }}"
                                                    class="p-0 me-2 ms-0" alt="" />
                                            </a>

                                        @endcan
                                        @can('tier-delete')
                                            <form class="d-inline delete-tier-form" method="post"
                                                action="{{ route('tiers.destroy', $tier->id) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn text-danger btn-outline-light">
                                                    <img src="{{ asset('back/assets/dasheets/img/plus-circle.svg') }}"
                                                        class="p-0" data-bs-target="#exampleModalToggle2"
                                                        data-bs-toggle="modal" alt="" />
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

        <!-- Create Modal STart -->
        <div class="modal fade" id="exampleModalToggle" aria-hidden="true" aria-labelledby="exampleModalToggleLabel"
            tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header border-0">
                        <h3 class="all-adjustment text-center pb-2 mb-0" style="width: 57%;">
                            Create Tier
                        </h3>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('tiers.store') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group mt-1">
                                <label for="name">Tier Name</label>
                                <input type="text" name="name" id="name" class="form-control mt-2">
                            </div>
                            {{-- <div class="form-group mt-1">
                                <label for="tier_type">Tier Type</label>
                                <input type="text" name="tier_type" id="tier_type" class="form-control mt-2">
                            </div> --}}
                            <div class="form-group mt-1">
                                <label for="discount">Discount (%)</label>
                                <input type="text" name="discount" id="discount" class="form-control mt-2">
                            </div>

                            <button type="submit" class="btn btn-primary mt-2">Save</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal End -->

        <!-- Edit Modal STart -->
        @foreach ($tiers as $tier)
            <div class="modal fade" id="editTierModel{{ $tier->id }}" aria-hidden="true"
                aria-labelledby="editCategoryModelToggleLabel" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header border-0">
                            <h3 class="all-adjustment text-center pb-2 mb-0" style="width: 57%;">
                                Edit Tier
                            </h3>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('tiers.update', $tier->id) }}" method="post"
                                enctype="multipart/form-data" class="mt-4">
                                @csrf
                                @method('PUT')
                                <div class="form-group mt-1">
                                    <label for="name">Tier Name</label>
                                    <input type="text" name="name" id="name" class="form-control mt-2"
                                        value="{{ $tier->name ?? '' }}">
                                </div>
                                {{-- <div class="form-group mt-1">
                                    <label for="tier_type">Tier Type</label>
                                    <input type="text" name="tier_type" id="tier_type" class="form-control mt-2"
                                        value="{{ $tier->tier_type ?? '' }}">
                                </div> --}}
                                <div class="form-group mt-1">
                                    <label for="discount">Discount (%)</label>
                                    <input type="text" name="discount" id="discount" class="form-control mt-2"
                                        value="{{ $tier->discount ?? '' }}">
                                </div>
                                <button type="submit" class="btn btn-primary mt-2">Save</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
        <!-- Modal End -->

    </div>

@endsection

@section('scripts')
    <script src="{{ asset('back/assets/js/datatable/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('back/assets/js/datatable/js/dataTables.bootstrap5.min.js') }}"></script>

    <script>
        $(document).ready(function() {

            var table = $('#example').DataTable({
                dom: 'Bfrtip',
                buttons: [{
                        extend: 'pdf',
                        footer: true,
                        exportOptions: {
                            columns: [0, 1, 2, 3,]
                        }
                    },
                    {
                        extend: 'csv',
                        footer: false,
                        exportOptions: {
                            columns: [0, 1, 2, 3,]
                        }

                    },
                    {
                        extend: 'excel',
                        footer: false,
                        exportOptions: {
                            columns: [0, 1, 2, 3,]
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
            $(document).on('click', '.delete-unit-link', function(e) {
                e.preventDefault();
                $(this).find('.delete-tier-form').submit();
            });

            $(".delete-tier-form").submit(function() {
                var decision = confirm("Are you sure, You want to Delete this unit?");
                if (decision) {
                    return true;
                }
                return false;
            });
        });
    </script>
@endsection
