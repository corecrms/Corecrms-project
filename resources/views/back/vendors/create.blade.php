@extends('back.layout.app')
@section('title', 'Create Vendor')
@section('content')



    <div class="content">
        <div class="container-fluid px-4 mt-3">
            <div class="border-bottom">
                <h3 class="all-adjustment text-center pb-2 mb-0">Create Vendor</h3>
            </div>

            <div class="shadow p-4 mt-3">
                <div class="container-fluid create-product-form rounded">

                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <strong>Whoops!</strong> There were some problems with your input.<br><br>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!--end breadcrumb-->
                    {{-- <div>
                        {!! Form::open(['route' => 'vendors.store', 'method' => 'POST', 'class' => 'row', 'g-3']) !!}

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Name:</label>
                            {!! Form::text('name', null, ['placeholder' => 'Name', 'class' => 'form-control']) !!}

                        </div>
                        <div class="col-md-6 mb-3">

                            <label class="form-label">Email:</label>
                            {!! Form::text('email', null, ['placeholder' => 'Email', 'class' => 'form-control']) !!}

                        </div>
                        <div class="col-md-6 mb-3">

                            <label class="form-label">Phone No:</label>
                            {!! Form::text('contact_no', null, ['placeholder' => 'Phone', 'class' => 'form-control']) !!}

                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">status:</label>
                            <select name="status" id="status" class="form-control">
                                <option value="1" selected>Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                        <div class="col-md-12 mb-3">

                            <label class="form-label">Company Name:</label>
                            {!! Form::text('company_name', null, ['placeholder' => 'Company Name', 'class' => 'form-control']) !!}

                        </div>

                        <input type="hidden" name="password" id="password" value="Password">

                        <div class="col-md-12 mb-3">

                            <label class="form-label">Address:</label>
                            <textarea name="address" id="address" cols="30" rows="4" class="form-control"></textarea>
                        </div>

                        <div class="col-xs-12 col-sm-12 mt-3 col-md-12">
                            <button type="submit" class="btn save-btn text-white">Submit</button>
                        </div>
                        {!! Form::close() !!}
                    </div> --}}


                    <!--end breadcrumb-->
                    <form action="{{ route('vendors.store') }}" method="POST">
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
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="exampleFormControlSelect1" class="mb-1">Phone No <span
                                            class="text-danger">*</span></label>
                                    <input type="tel" class="form-control subheading" id="exampleFormControlInput1"
                                        placeholder="Name" required name="contact_no" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="tax_number" class="mb-1">Tax Number</label>
                                    <input type="text" class="form-control subheading" id="tax_number"
                                        placeholder="Tax Number" name="tax_number" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="company_name" class="mb-1">Business Name </label>
                                    <input type="text" class="form-control subheading" id="company_name"
                                        placeholder="Business Name" required name="company_name" />
                                </div>
                            </div>
                        </div>

                        <div class="row mt-2">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="address" class="mb-1">Address Line 1</label>
                                    <input type="text" class="form-control subheading" id="address"
                                        placeholder="Address" required name="address"
                                        value="{{ $customer->user->address ?? '' }}" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="address" class="mb-1">Address Line 2</label>
                                    <input type="text" class="form-control subheading" id="address"
                                        placeholder="Address" name="address_line_2"
                                        value="{{ $customer->user->address ?? '' }}" />
                                </div>
                            </div>
                        </div>


                        <div class="row mt-2">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="city" class="mb-1">City</label>
                                    <input type="text" class="form-control subheading" id="city" placeholder="City"
                                        required name="city" />
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


    <!--end page wrapper -->
@endsection
