@extends('user.dashboard-layout.app')
@section('style')
    <link href="{{ asset('back/assets/js/datatable/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" />
    <style>
        /* .dataTables_paginate {
            display: none;
        }

        .dataTables_length {
            display: none;
        }

        .dataTables_info {
            display: none;
        } */
         .dt-buttons{
            display: none !important;
         }
         .dataTables_filter{
            display: none !important;
         }
    </style>
@endsection

@section('content')
    <div class="container-xxl container-fluid pt-4 px-4 mb-5">
        <div class="border-bottom">
            <h3 class="all-adjustment text-center pb-2 mb-0 w-25">
                Reserve Orders
            </h3>
        </div>

        <div class="card card-shadow rounded-3 border-0 mt-4 p-2 pt-0 px-0">
            <div class="card-header bg-white border-0 rounded-3">
                <div class="row my-3">
                    <div class="col-md-4 col-12">
                        <div class="input-search position-relative">
                            <input type="text" placeholder="Search Order" class="form-control rounded-3 subheading"
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
            <div class="card-body">
                <div class="table-responsive p-2">
                    <table class="table" id="example">
                        <thead class="fw-bold">
                            <tr>
                                <th>Order#</th>
                                <th>Date</th>
                                <th>Ship To</th>
                                <th>Location</th>
                                <th>Total</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($orders as $order)
                                <tr>
                                    <td class="align-middle">{{ $order->order_number ?? '' }}</td>
                                    <td class="pt-3">{{ $order->date ?? '' }}</td>
                                    <td class="pt-3">{{ $order->ship_to ?? '' }}</td>
                                    <td class="pt-3">{{ $order->location ?? '' }}</td>
                                    <td class="pt-3">{{ $order->total ?? '' }}</td>

                                    <td class="align-middle">
                                        <form class="d-inline delete-category-form" method="post"
                                            action="{{ route('reserve-order.destroy', $order->id) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn text-danger btn-outline-light">
                                                <img src="{{ asset('back/assets/dasheets/img/plus-circle.svg') }}"
                                                    class="p-0" data-bs-target="#exampleModalToggle2"
                                                    data-bs-toggle="modal" alt="" />
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">No Order Found!</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- Input Fields End -->
    </div>

    <!-- Create Modal STart -->
    <div class="modal fade" id="exampleModalToggle" aria-hidden="true" aria-labelledby="exampleModalToggleLabel"
        tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h3 class="all-adjustment text-center pb-2 mb-0" style="width: 57%;">
                        Reserve Order
                    </h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('reserve-order.store') }}" method="POST">
                        @csrf
                        @method('POST')
                        <div class="row mt-2">
                            <div class=" mt-2">
                                <div class="form-group">
                                    <label for="exampleFormControlSelect1" class="mb-1 fw-bold">Order#</label>
                                    <input type="text" class="form-control subheading" id="exampleFormControlInput1"
                                        name="order_number" />
                                </div>
                            </div>
                            <div class=" mt-2">
                                <div class="form-group">
                                    <label for="date" class="mb-1">Date</label>
                                    <input type="date" name="date" id="date" value="{{ date('Y-m-d') }}"
                                        class="form-control subheading">
                                </div>
                            </div>
                            <div class=" mt-2">
                                <div class="form-group">
                                    <label for="exampleFormControlSelect1" class="mb-1 fw-bold">Ship To</label>
                                    <input type="text" class="form-control subheading" id="exampleFormControlInput1"
                                        name="ship_to" />
                                </div>
                            </div>
                            <div class=" mt-2">
                                <div class="form-group">
                                    <label for="exampleFormControlSelect1" class="mb-1 fw-bold">Location</label>
                                    <input type="text" class="form-control subheading" id="exampleFormControlInput1"
                                        name="location" />
                                </div>
                            </div>
                            <div class=" mt-2">
                                <div class="form-group">
                                    <label for="exampleFormControlSelect1" class="mb-1 fw-bold">Total</label>
                                    <input type="number" class="form-control subheading" id="exampleFormControlInput1"
                                        name="total" />
                                </div>
                            </div>
                        </div>
                        <button class="btn save-btn text-white mt-4">Create</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal End -->
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

        });
    </script>
@endsection
