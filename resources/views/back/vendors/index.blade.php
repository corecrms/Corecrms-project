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

        .dropdown-menu {
            /* Positioning styles */
            position: absolute;
            z-index: 9999999999999999999999999999999999;
            /* Ensure the dropdown is above other elements */
            /* Alignment adjustments */
            top: calc(100% + 10px);
            /* Adjust the top position as needed */
            left: 0;
            /* Adjust the left position as needed */
        }
    </style>
@endsection
@section('title', 'Vendors')
@section('content')

    <div class="content">

        <div class="container-fluid pt-4 px-4 mb-5">
            <div class="border-bottom">
                <h3 class="all-adjustment text-center pb-2 mb-0 w-25">
                    All Vendors
                </h3>
            </div>

            @include('back.layout.errors')

            <div class="card card-shadow border-0 mt-3 rounded-3 p-2">
                <div class="card-header bg-white border-0 rounded-3">
                    <div class="row my-3">
                        <div class="col-md-4 col-12">
                            <div class="input-search position-relative">
                                <input type="text" placeholder="Search Vendor" class="form-control rounded-3 subheading"
                                    id="custom-filter" />
                                <span class="fa fa-search search-icon text-secondary"></span>
                            </div>
                        </div>

                        <div class="col-md-8 col-12 text-end">
                            <a href="#" class="btn create-btn rounded-3 mt-2" type="button"
                                data-bs-toggle="offcanvas" data-bs-target="#staticBackdrop"
                                aria-controls="staticBackdrop">Filter <i class="bi bi-funnel"></i></a>
                            <a href="#" class="btn border-danger text-danger rounded-3 mt-2 excel-btn"
                                id="download-excel">Excel <i class="bi bi-file-earmark-text"></i></a>
                            <a href="#" class="btn pdf rounded-3 mt-2" id="download-pdf">Pdf <i
                                    class="bi bi-file-earmark"></i></a>
                            {{-- <a href="#" class="btn import-customer-btn rounded-3 mt-2">Import Vendor <i
                                    class="bi bi-download"></i></a> --}}
                            <a href="{{ route('export-vendors') }}" class="btn import-customer-btn rounded-3 mt-2">
                                Export File
                            </a>
                            @can('supplier-create')
                                <a type="button" class="btn import-customer-btn rounded-3 mt-2" data-bs-toggle="modal"
                                    data-bs-target="#importfilemodal">Import Vendor <i class="bi bi-download"></i></a>
                                <a class="btn create-btn rounded-3 mt-2" data-bs-toggle="modal"
                                    data-bs-target="#createVendorModel">Create <i class="bi bi-plus-lg"></i>
                                </a>
                            @endcan
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <div class="alert alert-danger p-2 text-end" id="deletedAlert" style="display: none">
                        <div style="display: flex;justify-content:space-between">
                            <span><span id="deleteRowCount">0</span> rows selected</span>
                            <button class="btn btn-sm btn-danger" id="deleteRowTrigger">Delete</button>
                        </div>
                    </div>
                    <table class="table" id="example">
                        <thead class="fw-bold">
                            <tr>
                                <th>
                                    <label for="myCheckbox09" class="checkbox">
                                        <input class="checkbox__input" type="checkbox" id="myCheckbox09" />
                                        <svg class="checkbox__icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 22 22">
                                            <rect width="21" height="21" x=".5" y=".5" fill="#FFF"
                                                stroke="rgba(76, 73, 227, 1)" rx="3" />
                                            <path class="tick" stroke="rgba(76, 73, 227, 1)" fill="none"
                                                stroke-linecap="round" stroke-width="3" d="M4 10l5 5 9-9" />
                                        </svg>
                                    </label>
                                </th>
                                <th><strong>Name</strong></th>
                                <th><strong>Email</strong></th>
                                <th><strong>Phone</strong></th>
                                <th><strong>Status</strong></th>
                                <th><strong>Blacklist</strong></th>
                                <th><strong>Action</strong></th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($vendors as $key => $vendor)
                                <tr>
                                    <td class="align-middle">
                                        <label for="select-checkbox" class="checkbox">
                                            <input class="checkbox__input select-checkbox deleteRow" type="checkbox"
                                                id="select-checkbox" data-id="{{ $vendor->id }}" />
                                            <svg class="checkbox__icon" xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 22 22">
                                                <rect width="21" height="21" x=".5" y=".5" fill="#FFF"
                                                    stroke="rgba(76, 73, 227, 1)" rx="3" />
                                                <path class="tick" stroke="rgba(76, 73, 227, 1)" fill="none"
                                                    stroke-linecap="round" stroke-width="3" d="M4 10l5 5 9-9" />
                                            </svg>
                                        </label>
                                    </td>
                                    <td class="align-middle">{{ $vendor->user->name ?? '' }}</td>
                                    <td class="align-middle">{{ $vendor->user->email }}</td>
                                    <td class="align-middle">{{ $vendor->user->contact_no ?? '...' }}</td>
                                    <td class="align-middle">
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
                                    <td class="align-middle">
                                        <div class="table_switch">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input blacklistStatus"
                                                    data-id="{{ $vendor->id }}" type="checkbox"
                                                    id="flexSwitchCheck{{ $vendor->id }}"
                                                    {{ $vendor->blacklist == 1 ? 'checked' : '' }}>
                                                <label class="form-check-label"
                                                    for="flexSwitchCheck{{ $vendor->id }}"></label>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="align-middle">
                                        <a class=" text-decoration-none btn" data-bs-toggle="modal"
                                            data-bs-target="#editVendorModel{{ $vendor->id }}">
                                            <img src="{{ asset('back/assets/dasheets/img/edit-2.svg') }}"
                                                class="p-0 me-2 ms-0" alt="" />
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
                                            <img src="{{ asset('back/assets/dasheets/img/plus-circle.svg') }}"
                                                class="p-0" data-bs-target="#exampleModalToggle2"
                                                data-bs-toggle="modal" alt="" />
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


    <!-- Create Modal STart -->
    <div class="modal fade" id="createVendorModel" aria-hidden="true" aria-labelledby="exampleModalToggleLabel"
        tabindex="-1">
        <div class="modal-dialog modal-dialog-centered" style="min-width: 770px">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h3 class="all-adjustment text-center pb-2 mb-0" style="width: 50%;">
                        Create Vendor
                    </h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-xl-12 mx-auto">


                            <form action="{{ route('vendors.store') }}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="exampleFormControlSelect1" class="mb-1">Name <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control subheading"
                                                id="exampleFormControlInput1" placeholder="Name" required
                                                name="name" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="exampleFormControlSelect1" class="mb-1">Email</label>
                                            <input type="email" class="form-control subheading"
                                                id="exampleFormControlInput1" placeholder="Email" name="email"
                                                required />
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-2">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="exampleFormControlSelect1" class="mb-1">Phone No <span
                                                    class="text-danger">*</span></label>
                                            <input type="tel" class="form-control subheading"
                                                id="exampleFormControlInput1" placeholder="Phone No" required
                                                name="contact_no" />
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="exampleFormControlSelect1" class="mb-1">Tax Number</label>
                                            <input type="text" class="form-control subheading"
                                                id="exampleFormControlInput1" placeholder="Tax Number"
                                                name="tax_number" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="company_name" class="mb-1">Company Name </label>
                                            <input type="text" class="form-control subheading" id="company_name"
                                                placeholder="Business Name" required name="company_name" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="exampleFormControlSelect1" class="mb-1">Address Line 1</label>
                                            <input type="text" class="form-control subheading"
                                                id="exampleFormControlInput1" placeholder="Address" required
                                                name="address" />
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="exampleFormControlSelect1" class="mb-1">Address Line 2</label>
                                            <input type="text" class="form-control subheading"
                                                id="exampleFormControlInput1" placeholder="Address"
                                                name="address_line_2" />
                                        </div>
                                    </div>

                                </div>

                                <div class="row mt-2">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="city" class="mb-1">City</label>
                                            <input type="text" class="form-control subheading" id="city"
                                                placeholder="City" required name="city" />
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="exampleFormControlSelect1" class="mb-1">Country</label>
                                            <input type="text" class="form-control subheading"
                                                id="exampleFormControlInput1" placeholder="Country" required
                                                name="country" />
                                        </div>
                                    </div>


                                </div>

                                <div class="row mt-2">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="country_code" class="mb-1">Country Code</label>
                                            <input type="text" class="form-control subheading" id="country_code"
                                                placeholder="Country Code (e.g Us)" required name="country_code" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="state_code" class="mb-1">State Or Province Code</label>
                                            <input type="text" class="form-control subheading" id="state_code"
                                                placeholder="State Code (e.g Az)" required name="state_code" />
                                        </div>
                                    </div>

                                </div>

                                <div class="row mt-2">

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="postal_code" class="mb-1">Postal Code</label>
                                            <input type="text" class="form-control subheading" id="postal_code"
                                                placeholder="Postal Code (e.g 42134)" required name="postal_code" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="status" class="mb-1">Status</label>
                                            <select name="status" id="status" class="form-control">
                                                <option value="1" selected>Active</option>
                                                <option value="0">Inactive</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <button class="btn save-btn text-white mt-4" type="submit">Create</button>
                            </form>


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal End -->
    <div class="modal fade" id="importfilemodal" tabindex="-1" aria-labelledby="importfilemodalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('import-vendors') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title modal_header_import d-flex align-items-center" id="importfilemodalLabel">
                            <span class="me-1">
                                <i class="far fa-file-alt"></i>
                            </span>
                            Import a file
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="file_upload_modal">

                            <div class="input-group mb-3">
                                <label class="input-group-text" for="inputGroupFile01">

                                    <div class="icons">
                                        <i class="fas fa-cloud-upload-alt"></i>
                                    </div>
                                    <div class="click">
                                        <span class="button">Click to upload</span>
                                        <span>or drag and drop</span>
                                    </div>
                                    <span class="max_span">.xlsx(max 100Mb)</span>

                                </label>
                                <input type="file" name="file" class="form-control" id="inputGroupFile01">
                            </div>

                            <div class="upload_field d-none">
                                <div><i class="fas fa-file-download"></i></div>
                                <div>
                                    <p>Upload failed, please try again</p>
                                    <span>Vendor_List.xlsx</span>
                                    <p>Try again</p>
                                </div>

                            </div>
                            <ul class="">
                                <li class="error">
                                    <span><i class="fas fa-info-circle"></i></span>
                                    <span> File format should be like sample file. <br> * fields are mandatory
                                        <a href="{{ asset('sample-files/vendors.xlsx') }}" class="text-primary"
                                            download>Download sample file</a>
                                    </span>
                                </li>
                            </ul>
                            <ul class="d-none">
                                <li class="error">
                                    <span><i class="fas fa-times"></i></span>
                                    <span>File Must be less then 100 MB </span>
                                </li>
                                <li class="">
                                    <span><i class="fas fa-check"></i></span>
                                    <span> File format should be: xlsx</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-lg-between ">
                        <button type="button" class="btn  " data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary theme_btn_blue"><i
                                class="fas fa-paper-plane me-1"></i>Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @foreach ($vendors as $vendor)
        <!-- Edit Modal STart -->
        <div class="modal fade" id="editVendorModel{{ $vendor->id }}" aria-hidden="true"
            aria-labelledby="exampleModalToggleLabel" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered" style="min-width: 770px">
                <div class="modal-content">
                    <div class="modal-header border-0">
                        <h3 class="all-adjustment text-center pb-2 mb-0" style="width: 50%;">
                            Edit Vendor
                        </h3>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="card-body p-4">
                                <form action="{{ route('vendors.update', $vendor->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="exampleFormControlSelect1" class="mb-1">Name <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" class="form-control subheading"
                                                    id="exampleFormControlInput1" placeholder="Name" required
                                                    name="name" value="{{ $vendor->user->name }}" />
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="exampleFormControlSelect1" class="mb-1">Email</label>
                                                <input type="email" class="form-control subheading"
                                                    id="exampleFormControlInput1" placeholder="Email" name="email"
                                                    required value="{{ $vendor->user->email }}" />
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="exampleFormControlSelect1" class="mb-1">Phone No <span
                                                        class="text-danger">*</span></label>
                                                <input type="tel" class="form-control subheading"
                                                    id="exampleFormControlInput1" placeholder="Phone No" required
                                                    name="contact_no" value="{{ $vendor->user->contact_no }}" />
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="tax_number" class="mb-1">Tax Number</label>
                                                <input type="text" class="form-control subheading" id="tax_number"
                                                    placeholder="Tax Number" name="tax_number"
                                                    value="{{ $vendor->tax_number }}" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="company_name" class="mb-1">Business Name </label>
                                                <input type="text" class="form-control subheading" id="company_name"
                                                    placeholder="Business Name" required name="company_name"
                                                    value="{{ $vendor->company_name ?? '' }}" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="address" class="mb-1">Address</label>
                                                <input type="text" class="form-control subheading" id="address"
                                                    placeholder="Address" required name="address"
                                                    value="{{ $vendor->user->address ?? '' }}" />
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="exampleFormControlSelect1" class="mb-1">Address Line
                                                    2</label>
                                                <input type="text" class="form-control subheading"
                                                    id="exampleFormControlInput1" placeholder="Address"
                                                    name="address_line_2"
                                                    value="{{ $vendor->user->address_line_2 ?? '' }}" />
                                            </div>
                                        </div>

                                    </div>


                                    <div class="row mt-2">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="country_code" class="mb-1">Country Code</label>
                                                <input type="text" class="form-control subheading" id="country_code"
                                                    placeholder="Country Code (e.g Us)" required name="country_code"
                                                    value="{{ $vendor->user->country_code }}" />
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="city" class="mb-1">City</label>
                                                <input type="text" class="form-control subheading" id="city"
                                                    placeholder="City" required name="city"
                                                    value="{{ $vendor->city }}" />
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="state_code" class="mb-1">State Or Province Code</label>
                                                <input type="text" class="form-control subheading" id="state_code"
                                                    placeholder="State Code (e.g Az)" required name="state_code"
                                                    value="{{ $vendor->user->state_code ?? '' }}" />
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="postal_code" class="mb-1">Postal Code</label>
                                                <input type="text" class="form-control subheading" id="postal_code"
                                                    placeholder="Postal Code (e.g 42134)" required name="postal_code"
                                                    value="{{ $vendor->user->postal_code ?? '' }}" />
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-2">

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="exampleFormControlSelect1" class="mb-1">Country</label>
                                                <input type="text" class="form-control subheading"
                                                    id="exampleFormControlInput1" placeholder="Country" required
                                                    name="country" value="{{ $vendor->country }}" />
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="status" class="mb-1">Status</label>
                                                <select name="status" id="status" class="form-control">
                                                    <option value="1" {{ $vendor->status == 1 ? 'selected' : '' }}>Active
                                                    </option>
                                                    <option value="0" {{ $vendor->status == 0 ? 'selected' : '' }}>Inactive
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <button class="btn save-btn text-white mt-4" type="submit">Update</button>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal End -->
        <!-- Modal End -->
    @endforeach

    <div class="offcanvas offcanvas-end" tabindex="-1" id="staticBackdrop" aria-labelledby="staticBackdropLabel"
        style="width: 20rem">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="staticBackdropLabel">Filter</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <form action="{{ route('vendors.filter') }}" method="POST">
                @csrf
                @method('POST')
                <div>
                    <div class="form-group">
                        <label for="name">Supplier Name</label>
                        <input type="text" class="form-control mt-2" name="name" id="name"
                            placeholder="Supplier Name">
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone</label>
                        <input type="text" class="form-control mt-2" name="phone" id="phone"
                            placeholder="Customer phone">
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="text" class="form-control mt-2" name="email" id="email"
                            placeholder="Customer Email">
                    </div>

                    <button class="btn save-btn text-white mt-3" type="submit">Filter <i
                            class="bi bi-funnel"></i></button>

                </div>
            </form>
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
        $(document).on('change', '.blacklistStatus', function() {
            console.log('status changed');
            var id = $(this).data('id');

            $.ajax({
                type: "put",
                data: {
                    _token: '{{ csrf_token() }}'
                },
                url: "{{ url('/vendor/blacklistStatus') }}" + "/" + id,
                success: function(response) {
                    toastr.success(response)
                },
                error: function(err) {
                    console.log(err);
                }
            })

        });

        $(document).ready(function() {

            var table = $('#example').DataTable({
                dom: 'Bfrtip',
                select: true,
                select: {
                    style: 'multi',
                    selector: 'td:first-child .select-checkbox',
                },
                buttons: [{
                        extend: 'pdf',
                        footer: true,
                        exportOptions: {
                            columns: [0, 1, 2]
                        }
                    },
                    {
                        extend: 'csv',
                        footer: false,
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


            // Select all checkbox click handler
            $('#myCheckbox09').on('click', function() {
                var isSelected = $(this).is(':checked'); // Check if checkbox is checked

                // Select/deselect all checkboxes with class 'select-checkbox'
                $('.select-checkbox').prop('checked', isSelected);

                // Optional: Update DataTables selection based on checkbox state
                if (isSelected) {
                    table.rows().select(); // Select all rows in DataTables (adjust if needed)
                    $('#deletedAlert').css('display', 'block');
                    $('#deleteRowCount').text($('.deleteRow:checked').length);
                } else {
                    table.rows().deselect(); // Deselect all rows in DataTables (adjust if needed)
                    $('#deletedAlert').css('display', 'none');
                }
            });

            // Handle click on checkbox to toggle row selection
            $('#example tbody').on('click', 'td.select-checkbox', function(e) {
                var $row = $(this).closest('tr');

                // Check the checkbox state and toggle row selection accordingly
                if (this.checked) {
                    table.row($row).select();
                    // $('#myCheckbox09').prop('checked', true);
                } else {
                    table.row($row).deselect();
                    // if ($('.deleteRow:checked').length === 0)
                    //     $('#myCheckbox09').prop('checked', false);

                }

                // Prevent click event from propagating to parent
                e.stopPropagation();
            });

            // Handle click on table cells with .select-checkbox
            $('#example tbody').on('click', 'td.select-checkbox', function(e) {
                $(this).parent().find('input[type="checkbox"]').trigger('click');
            });

            // Update the count and alert display whenever the selection changes
            table.on('select.dt deselect.dt', function() {
                var selectedRows = table.rows('.selected').count();
                if (selectedRows === 0) {
                    $('#deletedAlert').css('display', 'none');
                } else {
                    $('#deletedAlert').css('display', 'block');
                    $('#deleteRowCount').text(selectedRows);
                }
            });

            $('#deleteRowTrigger').on("click", function(event) { // triggering delete one by one
                if (confirm("Are you sure you won't be able to revert this!")) {
                    if ($('.deleteRow:checked').length > 0) { // at-least one checkbox checked
                        var ids = [];
                        $('.deleteRow').each(function() {
                            if ($(this).is(':checked')) {
                                let id = $(this).data('id');
                                ids.push(id);
                            }
                        });
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });

                        $.ajax({
                            type: "POST",
                            url: "{{ route('vendors.delete') }}",
                            data: {
                                ids
                            },
                            success: function(result) {
                                if (result.status === 200) {
                                    toastr.success(result.message)
                                    location.reload();
                                }
                            },
                            async: false
                        });
                    }
                }
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
