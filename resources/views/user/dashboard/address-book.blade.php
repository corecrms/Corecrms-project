@extends('user.dashboard-layout.app')


@section('content')
    <div class="container-fluid pt-4 px-4 mb-5">
        <div class="border-bottom">
            <h3 class="all-adjustment text-center pb-2 mb-0 w-25">
                Address Book
            </h3>
        </div>

        <!-- Input Fields -->
        <div class="card card-shadow rounded-3 border-0 mt-4 p-2 pt-0 px-0">
            <div class="card-header p-2 border-0 d-flex justify-content-between align-items-center">
                <h4 class="mb-0 mt-2 heading text-start card-title">
                    Default Billing
                </h4>
                <button class="btn create-btn rounded-3 mt-2" data-bs-target="#exampleModalToggle" data-bs-toggle="modal">
                    Add Shipping Address <i class="bi bi-plus-lg"></i>
                </button>

            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mt-2">
                        <div class="card">
                            <div class="card-header p-2 border-0">
                                <h4 class="mb-0 heading text-start card-title">
                                    Default Billing Address
                                </h4>
                            </div>
                            <div class="card-body">
                                <div class="">
                                    Name: <span class="fw-bold">{{ auth()->user()->name ?? '' }}</span>
                                </div>
                                <div class="">
                                    Email: <span class="fw-bold">{{ auth()->user()->email ?? '' }}</span>
                                </div>
                                <div class="">
                                    Contact No#: <span class="fw-bold">{{ auth()->user()->contact_no ?? '' }}</span>
                                </div>
                                <div class="">
                                    Country: <span class="fw-bold">{{ auth()->user()->customer->country ?? '' }}</span>
                                </div>
                                <div class="">
                                    City: <span class="fw-bold">{{ auth()->user()->customer->city ?? '' }}</span>
                                </div>
                                <div class="">
                                    Address Line 1: <span class="fw-bold">{{ auth()->user()->address ?? '' }}</span>
                                </div>
                                <div class="">
                                    Address Line 2: <span
                                        class="fw-bold">{{ auth()->user()->address_line_2 ?? '...' }}</span>
                                </div>
                                <div class="">
                                    Zip/Postal Code: <span class="fw-bold">{{ auth()->user()->postal_code ?? '' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mt-2">
                        <div class="card">
                            <div class="card-header p-2 border-0">
                                <h4 class="mb-0 heading text-start card-title">
                                    Default Shipping Address
                                </h4>
                            </div>
                            <div class="card-body">
                                <div class="">
                                    Name: <span class="fw-bold">{{ auth()->user()->name ?? '' }}</span>
                                </div>
                                <div class="">
                                    Email: <span class="fw-bold">{{ auth()->user()->email ?? '' }}</span>
                                </div>
                                <div class="">
                                    Contact No#: <span class="fw-bold">{{ auth()->user()->contact_no ?? '' }}</span>
                                </div>
                                <div class="">
                                    Country: <span class="fw-bold">{{ auth()->user()->customer->country ?? '' }}</span>
                                </div>
                                <div class="">
                                    City: <span class="fw-bold">{{ auth()->user()->customer->city ?? '' }}</span>
                                </div>
                                <div class="">
                                    Address Line 1: <span class="fw-bold">{{ auth()->user()->address ?? '' }}</span>
                                </div>
                                <div class="">
                                    Address Line 2: <span
                                        class="fw-bold">{{ auth()->user()->address_line_2 ?? '...' }}</span>
                                </div>
                                <div class="">
                                    Zip/Postal Code: <span class="fw-bold">{{ auth()->user()->postal_code ?? '' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- <div class="col-md-6 mt-2">
                        <div class="card">
                            <div class="card-header p-2 border-0">
                                <h4 class="mb-0 heading text-start card-title">
                                    Additional Address
                                </h4>
                            </div>
                            <div class="card-body">
                                <h2 class="heading">You have not add additional address</h2>
                            </div>
                        </div>
                    </div> --}}

                    @forelse ($addresses as $address)
                        <div class="col-md-6 mt-3">
                            <div class="card">
                                <div class="card-header p-2 border-0 d-flex justify-content-between">
                                    <h4 class="mb-0 heading text-start card-title">
                                        Additional Address
                                    </h4>
                                    <div class="d-flex gap-2">
                                        <a href="" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#editModalToggle{{ $address->id }}">
                                            Edit
                                        </a>

                                        <form action="{{ route('shipping_address.update', $address->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-outline-danger btn-sm" type="submit">Delete</button>
                                        </form>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="">
                                        Name: <span class="fw-bold">{{ $address->name ?? '' }}</span>
                                    </div>
                                    <div class="">
                                        Email: <span class="fw-bold">{{ $address->email ?? '' }}</span>
                                    </div>
                                    <div class="">
                                        Contact No#: <span class="fw-bold">{{ $address->phone_no ?? '' }}</span>
                                    </div>
                                    <div class="">
                                        Country: <span class="fw-bold">{{ $address->country ?? '' }}</span>
                                    </div>
                                    <div class="">
                                        City: <span class="fw-bold">{{ $address->city ?? '' }}</span>
                                    </div>
                                    <div class="">
                                        Address Line 1: <span class="fw-bold">{{ $address->address ?? '' }}</span>
                                    </div>
                                    <div class="">
                                        Address Line 2: <span class="fw-bold">{{ $address->address_line_2 ?? '' }}</span>
                                    </div>
                                    <div class="">
                                        Zip/Postal Code: <span class="fw-bold">{{ $address->postal_code ?? '' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                    @empty
                        <div class="col-md-6 mt-3">
                            <div class="card">
                                <div class="card-header p-2 border-0">
                                    <h4 class="mb-0  text-start card-title">
                                        Additional Address
                                    </h4>
                                </div>
                                <div class="card-body">
                                    <div class="">You have not add additional address</div>
                                </div>
                            </div>
                        </div>
                    @endforelse

                </div>
            </div>
        </div>
        <!-- Input Fields End -->
    </div>
    <!-- Create Modal STart -->
    <div class="modal fade" id="exampleModalToggle" aria-hidden="true" aria-labelledby="exampleModalToggleLabel"
        tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h3 class="all-adjustment text-center pb-2 mb-0" style="width: 50%;">
                        Shipping Address
                    </h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('shipping_address.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleFormControlSelect1" class="mb-1">Name <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control subheading" id="exampleFormControlInput1"
                                        placeholder="Name" required name="name" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleFormControlSelect1" class="mb-1">Email</label>
                                    <input type="email" class="form-control subheading" id="exampleFormControlInput1"
                                        placeholder="Email" name="email" required />
                                </div>
                            </div>
                        </div>

                        <div class="row mt-2">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleFormControlSelect1" class="mb-1">Phone No <span
                                            class="text-danger">*</span></label>
                                    <input type="tel" class="form-control subheading" id="exampleFormControlInput1"
                                        placeholder="Phone No" required name="phone_no" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleFormControlSelect1" class="mb-1">State <span
                                            class="text-danger">*</span></label>
                                    <input type="tel" class="form-control subheading" id="exampleFormControlInput1"
                                        placeholder="State" required name="state" />
                                </div>
                            </div>


                        </div>
                        <div class="row mt-2">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="exampleFormControlSelect1" class="mb-1">Address Line 1</label>
                                    <input type="text" class="form-control subheading" id="exampleFormControlInput1"
                                        placeholder="Address" required name="address" />
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="exampleFormControlSelect1" class="mb-1">Address Line 2</label>
                                    <input type="text" class="form-control subheading" id="exampleFormControlInput1"
                                        placeholder="Address" required name="address_line_2" />
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
                                    <input type="text" class="form-control subheading" id="exampleFormControlInput1"
                                        placeholder="Country" required name="country" />
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
                                    <label for="postal_code" class="mb-1">Zip/Postal Code</label>
                                    <input type="text" class="form-control subheading" id="postal_code"
                                        placeholder="Postal Code (e.g 42134)" required name="postal_code" />
                                </div>
                            </div>

                        </div>

                        <button class="btn save-btn text-white mt-4" type="submit">Done</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal End -->

    @foreach ($addresses as $address)
        <!-- Edit Modal STart -->
        <div class="modal fade" id="editModalToggle{{ $address->id }}" aria-hidden="true"
            aria-labelledby="exampleModalToggleLabel" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header border-0">
                        <h3 class="all-adjustment text-center pb-2 mb-0" style="width: 50%;">
                            Edit Address
                        </h3>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('shipping_address.update', $address->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleFormControlSelect1" class="mb-1">Name <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control subheading"
                                            id="exampleFormControlInput1" placeholder="Name" required name="name"
                                            value="{{ $address->name ?? '' }}" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleFormControlSelect1" class="mb-1">Email</label>
                                        <input type="email" class="form-control subheading"
                                            id="exampleFormControlInput1" placeholder="Email" name="email" required
                                            value="{{ $address->email ?? '' }}" />
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleFormControlSelect1" class="mb-1">Phone No <span
                                                class="text-danger">*</span></label>
                                        <input type="tel" class="form-control subheading"
                                            id="exampleFormControlInput1" placeholder="Phone No" required name="phone_no"
                                            value="{{ $address->phone_no ?? '' }}" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleFormControlSelect1" class="mb-1">State <span
                                                class="text-danger">*</span></label>
                                        <input type="tel" class="form-control subheading"
                                            id="exampleFormControlInput1" placeholder="State" required name="state"
                                            value="{{ $address->state ?? '' }}" />
                                    </div>
                                </div>

                            </div>
                            <div class="row mt-2">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="exampleFormControlSelect1" class="mb-1">Address Line 1</label>
                                        <input type="text" class="form-control subheading"
                                            id="exampleFormControlInput1" placeholder="Address" required name="address"
                                            value="{{ $address->address ?? '' }}" />
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="exampleFormControlSelect1" class="mb-1">Address Line 2</label>
                                        <input type="text" class="form-control subheading"
                                            id="exampleFormControlInput1" placeholder="Address" required
                                            name="address_line_2" value="{{ $address->address_line_2 ?? '' }}" />
                                    </div>
                                </div>

                            </div>

                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="city" class="mb-1">City</label>
                                        <input type="text" class="form-control subheading" id="city"
                                            placeholder="City" required name="city"
                                            value="{{ $address->city ?? '' }}" />
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleFormControlSelect1" class="mb-1">Country</label>
                                        <input type="text" class="form-control subheading"
                                            id="exampleFormControlInput1" placeholder="Country" required name="country"
                                            value="{{ $address->country ?? '' }}" />
                                    </div>
                                </div>

                            </div>

                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="country_code" class="mb-1">Country Code</label>
                                        <input type="text" class="form-control subheading" id="country_code"
                                            placeholder="Country Code (e.g Us)" required name="country_code"
                                            value="{{ $address->country_code ?? '' }}" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="state_code" class="mb-1">State Or Province Code</label>
                                        <input type="text" class="form-control subheading" id="state_code"
                                            placeholder="State Code (e.g Az)" required name="state_code"
                                            value="{{ $address->state_code ?? '' }}" />
                                    </div>
                                </div>

                            </div>

                            <div class="row mt-2">

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="postal_code" class="mb-1">Zip/Postal Code</label>
                                        <input type="text" class="form-control subheading" id="postal_code"
                                            placeholder="Postal Code (e.g 42134)" required name="postal_code"
                                            value="{{ $address->postal_code ?? '' }}" />
                                    </div>
                                </div>

                            </div>

                            <button class="btn save-btn text-white mt-4" type="submit">Update</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal End -->
    @endforeach
@endsection
