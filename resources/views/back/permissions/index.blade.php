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
@section('title', 'Roles')
@section('content')

    <div class="content">

        <div class="container-fluid pt-4 px-4 mb-5">
            <div class="border-bottom">
                <h3 class="all-adjustment text-center pb-2 mb-0 w-25">
                    Permission Management
                </h3>
            </div>

            @include('back.layout.errors')

            <div class="card card-shadow border-0 mt-3 rounded-3 p-2">
                <div class="card-header bg-white border-0 rounded-3">
                    <div class="row my-3">
                        <div class="col-md-4 col-12">
                            <div class="input-search position-relative">
                                <input type="text" placeholder="Search Permission" class="form-control rounded-3 subheading"
                                    id="custom-filter" />
                                <span class="fa fa-search search-icon text-secondary"></span>
                            </div>
                        </div>

                        <div class="col-md-8 col-12 text-end">
                            {{-- <a href="#" class="btn create-btn rounded-3 mt-2">Filter <i class="bi bi-funnel"></i></a> --}}
                            <a href="#" class="btn border-danger text-danger rounded-3 mt-2 excel-btn" id="download-excel" >Excel <i
                                    class="bi bi-file-earmark-text"></i></a>
                            <a href="#" class="btn pdf rounded-3 mt-2" id="download-pdf">Pdf <i class="bi bi-file-earmark"></i></a>
                            <a href="{{route('permissions.create')}}" class="btn create-btn rounded-3 mt-2" >Create <i class="bi bi-plus-lg"></i></a>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table" id="example">
                        <thead class="fw-bold">
                            <tr>
                                {{-- <th class="text-secondary">
                                    <label for="myCheckbox09" class="checkbox">
                                        <input class="checkbox__input" type="checkbox" id="myCheckbox09" />
                                        <svg class="checkbox__icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 22 22">
                                            <rect width="21" height="21" x=".5" y=".5" fill="#FFF"
                                                stroke="rgba(76, 73, 227, 1)" rx="3" />
                                            <path class="tick" stroke="rgba(76, 73, 227, 1)" fill="none"
                                                stroke-linecap="round" stroke-width="3" d="M4 10l5 5 9-9" />
                                        </svg>
                                    </label>
                                </th> --}}
                                <th><strong>No.</strong></th>
                                <th><strong>Name</strong></th>
                                <th><strong>Description</strong></th>
                                <th><strong>Action</strong></th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($permissions as $key => $permission)
                                <tr>
                                    {{-- <td class="pt-3">
                                        <label for="myCheckbox09" class="checkbox">
                                            <input class="checkbox__input" type="checkbox" id="myCheckbox09" />
                                            <svg class="checkbox__icon" xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 22 22">
                                                <rect width="21" height="21" x=".5" y=".5" fill="#FFF"
                                                    stroke="rgba(76, 73, 227, 1)" rx="3" />
                                                <path class="tick" stroke="rgba(76, 73, 227, 1)" fill="none"
                                                    stroke-linecap="round" stroke-width="3" d="M4 10l5 5 9-9" />
                                            </svg>
                                        </label>
                                    </td> --}}
                                    <td class="align-middle">{{$loop->iteration}}</td>
                                    <td class="align-middle">{{ $permission->name }}</td>
                                    <td class="align-middle">{{ $permission->description ?? '' }}</td>
                                    <td class="align-middle">
                                        <a class=" text-decoration-none btn" href="{{route('permissions.edit',$permission->id)}}">
                                            <img src="{{ asset('back/assets/dasheets/img/edit-2.svg') }}"
                                                class="p-0 me-2 ms-0" alt="" />
                                        </a>

                                        <a href="javascript:void(0);"
                                            class="text-decoration-none text-danger delete-vendor-link">
                                            {!! Form::open([
                                                'class' => 'delete-vendor-form text-decoration-none text-danger',
                                                'method' => 'DELETE',
                                                'route' => ['permissions.destroy', $permission->id],
                                                'style' => 'display:inline',
                                            ]) !!}
                                            {{-- <i class="fa-solid fa-trash"></i> --}}
                                            <img src="{{ asset('back/assets/dasheets/img/plus-circle.svg') }}"
                                                class="p-0" data-bs-target="#exampleModalToggle2" data-bs-toggle="modal"
                                                alt="" />
                                            {!! Form::submit('', ['class' => 'dropdown-item']) !!}
                                            {!! Form::close() !!}
                                        </a>
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


            $(document).on('click', '.delete-vendor-link', function(e) {
                e.preventDefault();
                $(this).find('.delete-vendor-form').submit();
            });
            $(".delete-vendor-form").submit(function() {
                var decision = confirm("Are you sure, You want to Delete this role?");
                if (decision) {
                    return true;
                }
                return false;
            });
        });

        $(document).ready(function() {

            var table = $('#example').DataTable({

                dom: 'Bfrtip',
                buttons: [{
                        extend: 'pdf',
                        footer: true,
                        exportOptions: {
                            columns: [0, 1, 2]
                        }
                    },
                    {
                        extend: 'excel',
                        footer: false,
                        exportOptions: {
                            columns: [0, 1, 2]
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
    </script>
@endsection
