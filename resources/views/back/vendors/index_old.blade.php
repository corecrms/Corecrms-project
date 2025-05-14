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
        {{-- <div class="container-fluid px-4">
            <div class="d-flex justify-content-between align-items-center">


                <div>
                    <h1 class="mt-5 fs-2">Vendor Management</h1>
                    <p>Manage your Vendors</p>

                </div>
                <div class="d-flex gap-4 flex-wrap align-items-baseline my-2 right-btn">

                    <a href="{{ route('vendors.create') }}" type="button" class="btn my-btn"><span><svg
                                class="svg-inline--fa fa-plus" aria-hidden="true" focusable="false" data-prefix="fas"
                                data-icon="plus" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"
                                data-fa-i2svg="">
                                <path fill="currentColor"
                                    d="M240 80c0-17.7-14.3-32-32-32s-32 14.3-32 32V224H32c-17.7 0-32 14.3-32 32s14.3 32 32 32H176V432c0 17.7 14.3 32 32 32s32-14.3 32-32V288H384c17.7 0 32-14.3 32-32s-14.3-32-32-32H240V80z">
                                </path>
                            </svg></span>
                        Create New Vendors</a>
                </div>
            </div>

            <div class="page-content">
                =
                @if ($message = Session::get('success'))
                    <div class="alert alert-success">
                        <p>{{ $message }}</p>
                    </div>
                @endif
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="example" class="table align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th><strong>#</strong></th>
                                        <th><strong>Name</strong></th>
                                        <th><strong>Email</strong></th>
                                        <th><strong>Status</strong></th>
                                        <th><strong>Action</strong></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $i = 0;
                                    @endphp
                                    @foreach ($vendors as $key => $vendor)
                                        <tr>
                                            <td><strong>{{ ++$i }}</strong></td>
                                            <td>{{ $vendor->user->name }}</td>
                                            <td>{{ $vendor->user->email }}</td>
                                            <td>
                                                <div class="table_switch">
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input status" data-id="{{ $vendor->id }}"
                                                            type="checkbox" id="flexSwitchCheck{{ $vendor->id }}"
                                                            {{ $vendor->status == 1 ? 'checked' : '' }}>
                                                        <label class="form-check-label"
                                                            for="flexSwitchCheck{{ $vendor->id }}"></label>
                                                    </div>
                                                </div>
                                            </td>

                                            <td>
                                                <a class="me-3 text-decoration-none text-secondary"
                                                    href="{{ route('vendors.edit', $vendor->id) }}">
                                                    <i class="fa-solid fa-pencil"></i>
                                                </a>
                                                <a href="javascript:void(0);"
                                                    class="text-decoration-none text-danger delete-vendor-link">
                                                    {!! Form::open([
                                                        'class' => 'delete-vendor-form text-decoration-none text-danger',
                                                        'method' => 'DELETE',
                                                        'route' => ['vendors.destroy', $vendor->id],
                                                        'style' => 'display:inline',
                                                    ]) !!}
                                                    <i class="fa-solid fa-trash"></i>
                                                    {!! Form::submit('', ['class' => 'dropdown-item']) !!}
                                                    {!! Form::close() !!}
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}


        <div class="container-fluid px-4 mt-3">
            {{-- <div class="border-bottom">
                <h3 class="all-adjustment text-center pb-2 mb-0">All Vendors</h3>
            </div> --}}
            <div class="border-bottom">
                <h3 class="all-adjustment text-center pb-2 mb-0 w-25">
                    All Vendors
                </h3>
            </div>

            <div class="row my-3">
                <div class="col-md-3 col-12 mt-2">
                    <input type="text" placeholder="Search" class="form-control rounded-5 subheading">
                </div>

                <div class="col-md-9 col-12 text-end">
                    <a href="#" class="btn create-btn rounded-3 mt-2">Filter <i class="bi bi-funnel"></i></a>
                    <a href="#" class="btn border-danger text-danger rounded-3 mt-2 excel-btn">Excel <i
                            class="bi bi-file-earmark-text"></i></a>
                    <a href="#" class="btn pdf rounded-3 mt-2">Pdf <i class="bi bi-file-earmark"></i></a>
                    <a class="btn create-btn rounded-3 mt-2" data-bs-toggle="modal"
                        data-bs-target="#createVendorModel">Create <i class="bi bi-plus-lg"></i></a>
                </div>
            </div>
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            <div class="table-responsive rounded-3 shadow p-2 pt-0">

                <table id="example" class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th><strong>#</strong></th>
                            <th><strong>Name</strong></th>
                            <th><strong>Email</strong></th>
                            <th><strong>Status</strong></th>
                            <th><strong>Action</strong></th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $i = 0;
                        @endphp
                        @foreach ($vendors as $key => $vendor)
                            <tr>
                                <td><strong>{{ ++$i }}</strong></td>
                                <td>{{ $vendor->user->name }}</td>
                                <td>{{ $vendor->user->email }}</td>
                                <td>
                                    <div class="table_switch">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input status" data-id="{{ $vendor->id }}"
                                                type="checkbox" id="flexSwitchCheck{{ $vendor->id }}"
                                                {{ $vendor->status == 1 ? 'checked' : '' }}>
                                            <label class="form-check-label"
                                                for="flexSwitchCheck{{ $vendor->id }}"></label>
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    <a class=" text-decoration-none btn" data-bs-toggle="modal"
                                        data-bs-target="#editVendorModel{{ $vendor->id }}">
                                        <img src="{{ asset('back/assets/dasheets/img/edit-2.svg') }}" class="p-0 me-2 ms-0"
                                            alt="" />
                                    </a>

                                    <a href="javascript:void(0);"
                                        class="text-decoration-none text-danger delete-vendor-link">
                                        {!! Form::open([
                                            'class' => 'delete-vendor-form text-decoration-none text-danger',
                                            'method' => 'DELETE',
                                            'route' => ['vendors.destroy', $vendor->id],
                                            'style' => 'display:inline',
                                        ]) !!}
                                        {{-- <i class="fa-solid fa-trash"></i> --}}
                                        <img src="{{ asset('back/assets/dasheets/img/plus-circle.svg') }}" class="p-0"
                                            data-bs-target="#exampleModalToggle2" data-bs-toggle="modal" alt="" />
                                        {!! Form::submit('', ['class' => 'dropdown-item']) !!}
                                        {!! Form::close() !!}
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div>

    <!-- Create Modal STart -->
    <div class="modal fade" id="createVendorModel" aria-hidden="true" aria-labelledby="exampleModalToggleLabel"
        tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h3 class="all-adjustment text-center pb-2 mb-0" style="width: 57%;">
                        Create Vendor
                    </h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-xl-12 mx-auto">

                                <div class=" p-1">

                                    {!! Form::open(['route' => 'vendors.store', 'method' => 'POST', 'class' => 'row', 'g-3']) !!}

                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Name:</label>
                                        {!! Form::text('name', null, ['placeholder' => 'Name', 'class' => 'form-control']) !!}

                                    </div>
                                    <div class="col-md-12 mb-3">

                                        <label class="form-label">Email:</label>
                                        {!! Form::text('email', null, ['placeholder' => 'Email', 'class' => 'form-control']) !!}

                                    </div>
                                    <div class="col-md-12 mb-3">

                                        <label class="form-label">Password:</label>
                                        {!! Form::password('password', ['placeholder' => 'Password', 'class' => 'form-control']) !!}

                                    </div>
                                    <div class="col-md-12 mb-3">

                                        <label class="form-label">Confirm Password:</label>
                                        {!! Form::password('confirm-password', ['placeholder' => 'Confirm Password', 'class' => 'form-control']) !!}

                                    </div>

                                    <div class="col-xs-12 col-sm-12 mt- col-md-12">
                                        <button type="submit" class="btn save-btn text-white">Submit</button>
                                    </div>
                                    {!! Form::close() !!}
                                </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal End -->

    @foreach ($vendors as $vendor)
        <!-- Edit Modal STart -->
    <div class="modal fade" id="editVendorModel{{$vendor->id}}" aria-hidden="true" aria-labelledby="exampleModalToggleLabel"
    tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h3 class="all-adjustment text-center pb-2 mb-0" style="width: 57%;">
                    Edit Vendor
                </h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="card-body p-4">
                        <h5 class="mb-4">Edit Vendor</h5>
                        {!! Form::model($vendor, [
                            'method' => 'PATCH',
                            'route' => ['vendors.update', $vendor->id],
                            'class' => 'row',
                            'g-3',
                        ]) !!}

                        <div class="col-md-12 mb-3">
                            <label class="form-label">Name:</label>
                            {!! Form::text('name', $vendor->user->name, ['placeholder' => 'Name', 'class' => 'form-control']) !!}

                        </div>
                        <div class="col-md-12 mb-3">

                            <label class="form-label">Email:</label>
                            {!! Form::text('email', $vendor->user->email, ['placeholder' => 'Email', 'class' => 'form-control']) !!}

                        </div>
                        <div class="col-md-12 mb-3">

                            <label class="form-label">Password:</label>
                            {!! Form::password('password', ['placeholder' => 'Password', 'class' => 'form-control']) !!}

                        </div>
                        <div class="col-md-12 mb-3">

                            <label class="form-label">Confirm Password:</label>
                            {!! Form::password('confirm-password', ['placeholder' => 'Confirm Password', 'class' => 'form-control']) !!}

                        </div>

                        <div class="col-xs-12 col-sm-12 mt-5 col-md-12">
                            <button type="submit" class="btn save-btn text-white">Submit</button>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
<!-- Modal End -->
    @endforeach


@endsection
@section('scripts')
    <script src="{{ asset('back/assets/js/datatable/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('back/assets/js/datatable/js/dataTables.bootstrap5.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            $('#example').DataTable({
                "paging": true,
                "ordering": false,
                "info": false,
                "searching": true,
            });

            $(document).on('click', '.delete-vendor-link', function(e) {
                e.preventDefault();
                $(this).find('.delete-vendor-form').submit();
            });
            $(".delete-vendor-form").submit(function() {
                var decision = confirm("Are you sure, You want to Delete this vendor?");
                if (decision) {
                    return true;
                }
                return false;
            });
        });
        $(document).on('change', '.status', function() {
            console.log('status changed');
            var id = $(this).data('id');

            $.ajax({
                type: "put",
                data: {
                    _token: '{{ csrf_token() }}'
                },
                url: "{{ url('/vendor/status') }}" + "/" + id,
                success: function(response) {
                    toastr.success(response)
                },
                error: function(err) {
                    console.log(err);
                }
            })

        });
    </script>
@endsection
