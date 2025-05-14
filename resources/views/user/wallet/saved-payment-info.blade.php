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
        .dt-buttons {
            display: none !important;
        }

        .dataTables_filter {
            display: none !important;
        }
    </style>
@endsection

@section('content')
    {{-- <div class="container-fluid pt-4 px-4 mb-5"> --}}

    <div class="container-xxl container-fluid pt-4 px-4 mb-5">
        <div class="border-bottom">
            <h3 class="all-adjustment text-center pb-2 mb-0 w-25">
                Payment Information
            </h3>
        </div>
        @if ($errors->any())
            <div class="alert alert-danger mt-4">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if(session('success'))
            <div class="alert alert-success mt-4">
                {{ session('success') }}
            </div>
        @endif



        <!-- Input Fields -->
        <div class="card card-shadow rounded-3 border-0 mt-4 p-2 pt-0 px-0">
            <div class="card-header border-0 py-3 d-flex justify-content-between">
                <h2 class="heading m-0">Credit Cards</h2>
                <button class="btn create-btn rounded-3 mt-2" data-bs-target="#exampleModalToggle" data-bs-toggle="modal">
                    Add Card <i class="bi bi-plus-lg"></i>
                </button>
            </div>
            <div class="card-body pt-0">
                @forelse ($info as $info)
                    <div class="row mt-2 border-bottom pb-2">
                        <div class="col-md-6 mt-2">
                            <div class="d-flex gap-3 align-items-center">
                                <div>
                                    <img src="{{ asset('front/dasheets/img/visa.png') }}" alt=""
                                        class="img-fluid" />
                                </div>
                                <div>
                                    <p class="m-0">************{{ $info->card_last_four ?? '' }}</p>
                                    <p class="m-0">Expiry {{ $info->card_exp_month ?? '0' }} /
                                        {{ $info->card_exp_year ?? '12' }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mt-2 text-end">
                            <form action="{{ route('remove-card', $info->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <button class="btn text-white delete-btn" type="submit">Remove</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="row mt-3 text-center w-100">
                        <span class="w-100 text-center">Card Not Found!</span>
                    </div>
                @endforelse

            </div>
        </div>
        <!-- Input Fields End -->
    </div>
    <!-- Input Fields End -->
    {{-- </div> --}}

    <!-- Create Modal STart -->
    <div class="modal fade" id="exampleModalToggle" aria-hidden="true" aria-labelledby="exampleModalToggleLabel"
        tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h3 class="all-adjustment text-center pb-2 mb-0" style="width: 57%;">
                        Add Credit Card
                    </h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('user.addCreditCard') }}" method="POST">
                        @csrf
                        @method('POST')
                        <div class="row mt-2">
                            <div class=" mt-2">
                                <div class="form-group">
                                    <label for="card_brand" class="mb-1 fw-bold">Card Name</label>
                                    <input type="text" class="form-control subheading" id="card_brand"
                                        name="card_brand" />
                                </div>
                            </div>
                            <div class=" mt-2">
                                <div class="form-group">
                                    <label for="card_last_four" class="mb-1">Card last four digit</label>
                                    <input type="text" name="card_last_four" id="card_last_four"
                                        class="form-control subheading" placeholder="Card last four digit">
                                </div>
                            </div>
                            <div class=" mt-2">
                                <div class="form-group">
                                    <label for="card_exp_month" class="mb-1 fw-bold">Expiry Month</label>
                                    <input type="text" class="form-control subheading" id="card_exp_month"
                                        name="card_exp_month" />
                                </div>
                            </div>
                            <div class=" mt-2">
                                <div class="form-group">
                                    <label for="card_exp_year" class="mb-1 fw-bold">Expiry Year</label>
                                    <input type="text" class="form-control subheading" id="card_exp_month"
                                        name="card_exp_year" />
                                </div>
                            </div>
                        </div>
                        <button class="btn save-btn text-white mt-4">Add</button>
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
