@extends('back.layout.app')

@section('style')
    <link href="{{ asset('dashassets/plugins/datatable/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" />
@endsection

@section('content')
    <!--start page wrapper -->
    <div class="page-wrapper">
        <div class="content">
            <div class="container-fluid px-4">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <h2 class="mb-3">Services</h2>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <a href="{{ route('subscription-services.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-1"></i>Add Service
                        </a>
                    </div>
                </div>

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>Success!</strong> {{ session('success') }}.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Error!</strong> {{ session('error') }}.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (count($errors) > 0)
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Whoops!</strong> There were some problems with your input.<br><br>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="card International_datatable p-3">
                    <div class="table-responsive">
                        <table id="example" class="table table-striped align-middle mb-0">
                            <thead>
                                <tr>
                                    <th><strong>No.</strong></th>
                                    <th><strong>Name</strong></th>
                                    <th><strong>Description</strong></th>
                                    <th><strong>Status</strong></th>
                                    <th><strong>Actions</strong></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($subscriptionService as $key => $service)
                                    <tr>
                                        <td><strong>{{ $key + 1 }}</strong></td>
                                        <td>{{ $service->name }}</td>
                                        <td>{{ Str::limit($service->description, 20, '...') }}</td>
                                        <td class="text-center">
                                            <div class="table_switch">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input status" data-id="{{ $service->id }}"
                                                        type="checkbox" id="flexSwitchCheck{{ $service->id }}"
                                                        {{ $service->status == 1 ? 'checked' : '' }}>
                                                    <label class="form-check-label"
                                                        for="flexSwitchCheck{{ $service->id }}"></label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="dropdown ms-auto">
                                                <a class="dropdown-toggle dropdown-toggle-nocaret" href="#"
                                                    role="button" id="dropdownMenuLink{{ $service->id }}"
                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class='bx bx-dots-horizontal-rounded font-22 text-option'></i>
                                                </a>
                                                <ul class="dropdown-menu"
                                                    aria-labelledby="dropdownMenuLink{{ $service->id }}">
                                                    <li><a class="dropdown-item"
                                                            href="{{ route('subscription-services.edit', $service->id) }}">Edit</a>
                                                    </li>
                                                    <li>
                                                        <form class="delete-service-form"
                                                            action="{{ route('subscription-services.destroy', $service->id) }}"
                                                            method="post">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="dropdown-item">Delete</button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--end page wrapper -->
@endsection


@section('script')
    <script src="{{ asset('dashassets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('dashassets/plugins/datatable/js/dataTables.bootstrap5.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            $('#example').DataTable();

            $(".delete-service-form").submit(function() {
                var decision = confirm("Are you sure, You want to Delete this Vendor?");
                if (decision) {
                    return true;
                }
                return false;
            });

            // $(document).on('change', '.status', function() {
            //     var status = $(this).prop('checked') == true ? 1 : 0;
            //     var id = $(this).data('id');

            //     const url = '';

            //     const data = {
            //         'status': status,
            //         'id': id
            //     };

            //     $.ajaxSetup({
            //         headers: {
            //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            //         }
            //     });

            //     $.ajax({
            //         url: url,
            //         type: 'PATCH',
            //         data: data,
            //         success: function(response) {
            //             if (response.status == 200) {
            //                 // alert('Status updated successfully');
            //             }
            //         },
            //         error: function(response) {
            //             alert('Error occured, Please try again');
            //         }
            //     });

            // });
        });
    </script>
@endsection
