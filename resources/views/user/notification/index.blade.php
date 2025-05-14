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
    <div class="container-xxl container-fluid pt-4 px-4 mb-5">
        <div class="border-bottom">
            <h3 class="all-adjustment text-center pb-2 mb-0 w-25">
                All Notifications
            </h3>
        </div>

        <div class="card card-shadow rounded-3 border-0 mt-4 p-2 pt-0 px-0">
            <div class="card-header bg-white border-0 rounded-3">
                <div class="row my-3">
                    <div class="col-md-4 col-12">
                        <div class="input-search position-relative">
                            <input type="text" placeholder="Search" class="form-control rounded-3 subheading"
                                id="custom-filter" />
                            <span class="fa fa-search search-icon text-secondary"></span>
                        </div>
                    </div>

                    <div class="col-md-8 col-12 text-end">

                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive p-2">
                    <table class="table" id="example">
                        <thead class="fw-bold">
                            <tr>
                                <th>No.</th>
                                <th>Subject</th>
                                <th>Message</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($notifications as $notification)
                                <tr>
                                    <td class="align-middle">{{ $loop->iteration }}</td>
                                    <td class="align-middle">{{ $notification->subject ?? '' }}</td>

                                    <td class="align-middle">{{ $notification->message ?? ''}}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center">No notifications found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- Input Fields End -->
    </div>

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
