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
                <h3 class="all-adjustment text-center pb-2 mb-0">Banner Section</h3>
            </div>

            @include('back.layout.errors')

            <div class="card card-shadow border-0 mt-5 rounded-3">
                <div class="card-header bg-white border-0 rounded-3">
                    <div class="row my-3">
                        <div class="col-md-4 col-12">
                            <div class="input-search position-relative">
                                <input type="text" placeholder="Search Brand" class="form-control rounded-3 subheading"
                                    id="custom-filter" />
                                <span class="fa fa-search search-icon text-secondary"></span>
                            </div>
                        </div>

                        <div class="col-md-8 col-12 text-end">
                            <a href="{{ route('landing-page-heading.edit', 1) }}"
                                class="btn save-btn text-white mt-2">Heading Section</a>
                            <a href="{{ route('ads.index') }}" class="btn save-btn text-white mt-2">Ads Section</a>
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

                            <tr>
                                <th>No#</th>
                                <th class="align-middle">Image</th>
                                <th class="align-middle">Heading</th>
                                <th class="align-middle">Link</th>
                                <th class="align-middle">Description</th>
                                <th class="align-middle">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($sections as $section)
                                <tr class="even">
                                    <td class="align-middle">
                                        {{ $loop->iteration }}
                                    </td>
                                    <td class="align-middle">
                                        <img src="{{ asset('storage/' . $section->image) }}" alt=""
                                            style="width: 100px;" class="rounded-cicle">
                                    </td>
                                    <td class="align-middle">
                                        {{ $section->title ?? '' }}
                                    </td>
                                    <td class="align-middle">
                                        <a href="{{ $section->link ?? '' }}" target="_blank">
                                            View
                                        </a>
                                    </td>

                                    <td class="align-middle">
                                        {{ $section->description ?? '' }}
                                    </td>
                                    <td class="align-middle">
                                        <a class=" text-decoration-none btn edit-category-btn" data-bs-toggle="modal"
                                            data-bs-target="#editModalToggle{{ $section->id }}">
                                            <img src="{{ asset('back/assets/dasheets/img/edit-2.svg') }}"
                                                class="p-0 me-2 ms-0" alt="" />
                                        </a>
                                        <form class="d-inline delete-brand-form" method="post"
                                            action="{{ route('cms.landing-page.destroy', $section->id) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn text-danger btn-outline-light"
                                                onclick="return confirm('Are you sure to delete this banner?')">
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

        <!-- Modal STart -->
        <div class="modal fade" id="exampleModalToggle" aria-hidden="true" aria-labelledby="exampleModalToggleLabel"
            tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header border-0">
                        <h3 class="all-adjustment text-center pb-2 mb-0" style="width: 57%;">
                            Create Banner
                        </h3>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('cms.landing-page.store') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row mb-3">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="exampleFormControlInput1">Heading</label>
                                        <input type="text" class="form-control" name="title"
                                            id="exampleFormControlInput1" placeholder="" required>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="exampleFormControlInput1">Image</label>
                                        <input type="file" class="form-control" name="image"
                                            id="exampleFormControlInput1" placeholder="" required>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="link">Link</label>
                                        <input type="text" class="form-control" name="link" id="link"
                                            placeholder="" required>
                                        <span class="text-danger">
                                            @error('link')
                                                {{ $message }}
                                            @enderror
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group mb-4">
                                <label for="exampleFormControlTextarea1">Description</label>
                                <textarea class="form-control" id="exampleFormControlTextarea1" name="description" rows="3"></textarea>
                            </div>

                            <button type="submit" class="btn save-btn me-2 text-white">Submit</button>

                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal End -->

        @foreach ($sections as $section)
            <!-- Edit Modal STart -->
            <div class="modal fade" id="editModalToggle{{ $section->id }}" aria-hidden="true"
                aria-labelledby="exampleModalToggleLabel" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header border-0">
                            <h3 class="all-adjustment text-center pb-2 mb-0" style="width: 57%;">
                                Edit Banner
                            </h3>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('cms.landing-page.update', $section->id) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="row mb-3">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="exampleFormControlInput1">Heading</label>
                                            <input type="text" class="form-control" name="title"
                                                id="exampleFormControlInput1" placeholder="" required
                                                value="{{ $section->title ?? '' }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="exampleFormControlInput1">Image</label>
                                            <input type="file" class="form-control" name="image"
                                                id="exampleFormControlInput1" placeholder="">
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="link">Link</label>
                                            <input type="text" class="form-control" name="link" id="link"
                                                placeholder="" required value="{{ $section->link ?? '' }}">
                                            <span class="text-danger">
                                                @error('link')
                                                    {{ $message }}
                                                @enderror
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group mb-4">
                                    <label for="exampleFormControlTextarea1">Description</label>
                                    <textarea class="form-control" id="exampleFormControlTextarea1" name="description" rows="3">{{ $section->description }}</textarea>
                                </div>

                                <button type="submit" class="btn save-btn me-2 text-white">Submit</button>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal End -->
        @endforeach



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
