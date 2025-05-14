@extends('back.layout.app')
@section('content')
    <!--start page wrapper -->
@section('title', 'Add Customer')
<div class="content">
    <div class="container-fluid px-4 mt-3">
        <div class="border-bottom">
            <h3 class="all-adjustment text-center pb-2 mb-0">Create Shipping</h3>
        </div>

        <div class="shadow p-4 mt-3 mb-4">
            <div class="container-fluid create-product-form rounded">

                {{-- @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <strong>Whoops!</strong> There were some problems with your input.<br><br>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif --}}

                @include('back.layout.errors')

                <!--end breadcrumb-->
                {{-- <form action="{{ route('customers.store') }}" method="POST">
                    @csrf
                    <!-- Origination Section -->
                    <div class="row">
                        <h5 class="mb-3">Origination</h5>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="contact_name" class="mb-1">Contact Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="contact_name" placeholder="Contact Name" name="contact_name" required>
                            </div>
                            <div class="form-group mt-3">
                                <label for="company_name" class="mb-1">Company Name</label>
                                <input type="text" class="form-control" id="company_name" placeholder="Company Name" name="company_name">
                            </div>
                            <div class="form-group mt-3">
                                <label for="phone_no" class="mb-1">Phone No <span class="text-danger">*</span></label>
                                <input type="tel" class="form-control" id="phone_no" placeholder="Phone No" name="phone_no" required>
                            </div>
                            <div class="form-group mt-3">
                                <label for="email" class="mb-1">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="email" placeholder="Email" name="email" required>
                            </div>
                            <div class="form-group mt-3">
                                <label for="address" class="mb-1">Address Line 1 <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="address" placeholder="Address Line 1" name="address" required>
                            </div>
                            <div class="form-group mt-3">
                                <label for="address_line_2" class="mb-1">Address Line 2</label>
                                <input type="text" class="form-control" id="address_line_2" placeholder="Address Line 2" name="address_line_2">
                            </div>
                            <div class="form-group mt-3">
                                <label for="city" class="mb-1">City <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="city" placeholder="City" name="city" required>
                            </div>
                        </div>

                        <!-- Destination Section -->
                        <div class="col-md-6">
                            <h5 class="mb-3">Destination</h5>
                            <div class="form-group">
                                <label for="contact_name_dest" class="mb-1">Contact Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="contact_name_dest" placeholder="Contact Name" name="contact_name_dest" required>
                            </div>
                            <div class="form-group mt-3">
                                <label for="company_name_dest" class="mb-1">Company Name</label>
                                <input type="text" class="form-control" id="company_name_dest" placeholder="Company Name" name="company_name_dest">
                            </div>
                            <div class="form-group mt-3">
                                <label for="phone_no_dest" class="mb-1">Phone No <span class="text-danger">*</span></label>
                                <input type="tel" class="form-control" id="phone_no_dest" placeholder="Phone No" name="phone_no_dest" required>
                            </div>
                            <div class="form-group mt-3">
                                <label for="email_dest" class="mb-1">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="email_dest" placeholder="Email" name="email_dest" required>
                            </div>
                            <div class="form-group mt-3">
                                <label for="address_dest" class="mb-1">Address Line 1 <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="address_dest" placeholder="Address Line 1" name="address_dest" required>
                            </div>
                            <div class="form-group mt-3">
                                <label for="address_line_2_dest" class="mb-1">Address Line 2</label>
                                <input type="text" class="form-control" id="address_line_2_dest" placeholder="Address Line 2" name="address_line_2_dest">
                            </div>
                            <div class="form-group mt-3">
                                <label for="city_dest" class="mb-1">City <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="city_dest" placeholder="City" name="city_dest" required>
                            </div>
                        </div>
                    </div>

                    <!-- Service Options Section -->
                    <div class="row mt-4">
                        <h5 class="mb-3">Service Options</h5>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="dropoff_type" class="mb-1">Dropoff Type</label>
                                <select id="dropoff_type" class="form-select" name="dropoff_type">
                                    <option value="Regular Pickup">Regular Pickup</option>
                                    <option value="Drop Box">Drop Box</option>
                                    <!-- Add other options as needed -->
                                </select>
                            </div>
                            <div class="form-group mt-3">
                                <label for="service" class="mb-1">Service</label>
                                <select id="service" class="form-select" name="service">
                                    <option value="FedEx Priority Overnight">FedEx Priority Overnight</option>
                                    <option value="FedEx 2Day">FedEx 2Day</option>
                                    <!-- Add other options as needed -->
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="signature_option" class="mb-1">Signature Option</label>
                                <select id="signature_option" class="form-select" name="signature_option">
                                    <option value="Deliver without signature">Deliver without signature</option>
                                    <option value="Signature Required">Signature Required</option>
                                    <!-- Add other options as needed -->
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Package Information Section -->
                    <div class="row mt-4">
                        <h5 class="mb-3">Package Information</h5>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="packaging" class="mb-1">Packaging</label>
                                <select id="packaging" class="form-select" name="packaging">
                                    <option value="FedEx Box">FedEx Box</option>
                                    <!-- Add other packaging options as needed -->
                                </select>
                            </div>
                            <div class="form-group mt-3">
                                <label for="weight" class="mb-1">Weight</label>
                                <input type="number" class="form-control" id="weight" name="weight" placeholder="Weight" step="0.1">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="declared_value" class="mb-1">Declared Value</label>
                                <input type="text" class="form-control" id="declared_value" name="declared_value" placeholder="Declared Value (USD)">
                            </div>
                        </div>
                    </div>

                    <!-- Label Settings Section -->
                    <div class="row mt-4">
                        <h5 class="mb-3">Label Settings</h5>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="format" class="mb-1">Format</label>
                                <select id="format" class="form-select" name="format">
                                    <option value="PNG">PNG file - for plain paper printing</option>
                                    <!-- Add other formats as needed -->
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="screen_resolution" class="mb-1">Screen Resolution</label>
                                <select id="screen_resolution" class="form-select" name="screen_resolution">
                                    <option value="96 DPI">96 DPI (Windows Default)</option>
                                    <!-- Add other resolutions as needed -->
                                </select>
                            </div>
                        </div>
                    </div>

                    <button class="btn btn-primary mt-4" type="submit">Create</button>
                </form> --}}

                <form action="{{ route('store.shipment') }}" method="POST">
                    @csrf
                    @method('POST')

                    <div class="">

                        <div class="row">
                            <input type="hidden" value="{{ $sale->id }}" name="sale_id">


                            {{-- <div class="row">
                                <div class="col-md-6">
                                    <h5 class="mb-3">Customer Address</h5>
                                    <div class="form-group">
                                        <label for="origin_contact_name" class="mb-1">Contact Name <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="origin_contact_name"
                                            placeholder="Contact Name" name="origin_contact_name" required>
                                    </div>
                                    <div class="form-group mt-3">
                                        <label for="origin_company_name" class="mb-1">Company Name</label>
                                        <input type="text" class="form-control" id="origin_company_name"
                                            placeholder="Company Name" name="origin_company_name">
                                    </div>
                                    <div class="form-group mt-3">
                                        <label for="origin_department" class="mb-1">Department</label>
                                        <input type="text" class="form-control" id="origin_department"
                                            placeholder="Department" name="origin_department">
                                    </div>
                                    <div class="form-group mt-3">
                                        <label for="origin_phone_no" class="mb-1">Phone <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="origin_phone_no"
                                            placeholder="Phone Number" name="origin_phone_no" required>
                                    </div>
                                    <div class="form-group mt-3">
                                        <label for="origin_fax" class="mb-1">Fax</label>
                                        <input type="text" class="form-control" id="origin_fax"
                                            placeholder="Fax Number" name="origin_fax">
                                    </div>
                                    <div class="form-group mt-3">
                                        <label for="origin_email" class="mb-1">Email Address <span
                                                class="text-danger">*</span></label>
                                        <input type="email" class="form-control" id="origin_email"
                                            placeholder="Email Address" name="origin_email" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="origin_address_line_1" class="mb-1">Address Line 1 <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="origin_address_line_1"
                                            placeholder="Address Line 1" name="origin_address_line_1" required>
                                    </div>
                                    <div class="form-group mt-3">
                                        <label for="origin_address_line_2" class="mb-1">Address Line 2</label>
                                        <input type="text" class="form-control" id="origin_address_line_2"
                                            placeholder="Address Line 2" name="origin_address_line_2">
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="origin_city" class="mb-1">City <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="origin_city"
                                                    placeholder="City" name="origin_city" required>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="origin_state_or_province" class="mb-1">State/Province
                                                    <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control"
                                                    id="origin_state_or_province" placeholder="State/Province"
                                                    name="origin_state_or_province" required>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="origin_postal_code" class="mb-1">Postal Code <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="origin_postal_code"
                                                    placeholder="Postal Code" name="origin_postal_code" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="destination_country" class="mb-1">Country <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="destination_country"
                                                    placeholder="Country" name="destination_country" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="col-md-6">
                                    <h5 class="mb-3">Recipient Address</h5>
                                    <div class="form-group">
                                        <label for="destination_contact_name" class="mb-1">Contact Name <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="destination_contact_name"
                                            placeholder="Contact Name" name="destination_contact_name" required>
                                    </div>
                                    <div class="form-group mt-3">
                                        <label for="destination_company_name" class="mb-1">Company Name</label>
                                        <input type="text" class="form-control" id="destination_company_name"
                                            placeholder="Company Name" name="destination_company_name">
                                    </div>
                                    <div class="form-group mt-3">
                                        <label for="destination_department" class="mb-1">Department</label>
                                        <input type="text" class="form-control" id="destination_department"
                                            placeholder="Department" name="destination_department">
                                    </div>
                                    <div class="form-group mt-3">
                                        <label for="destination_phone_no" class="mb-1">Phone <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="destination_phone_no"
                                            placeholder="Phone Number" name="destination_phone_no" required>
                                    </div>
                                    <div class="form-group mt-3">
                                        <label for="destination_fax" class="mb-1">Fax</label>
                                        <input type="text" class="form-control" id="destination_fax"
                                            placeholder="Fax Number" name="destination_fax">
                                    </div>
                                    <div class="form-group mt-3">
                                        <label for="destination_email" class="mb-1">Email Address <span
                                                class="text-danger">*</span></label>
                                        <input type="email" class="form-control" id="destination_email"
                                            placeholder="Email Address" name="destination_email" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="destination_address_line_1" class="mb-1">Address Line 1
                                            <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="destination_address_line_1"
                                            placeholder="Address Line 1" name="destination_address_line_1" required>
                                    </div>
                                    <div class="form-group mt-3">
                                        <label for="destination_address_line_2" class="mb-1">Address Line
                                            2</label>
                                        <input type="text" class="form-control" id="destination_address_line_2"
                                            placeholder="Address Line 2" name="destination_address_line_2">
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="destination_city" class="mb-1">City <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="destination_city"
                                                    placeholder="City" name="destination_city" required>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="destination_state_or_province"
                                                    class="mb-1">State/Province <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" class="form-control"
                                                    id="destination_state_or_province" placeholder="State/Province"
                                                    name="destination_state_or_province" required>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="destination_postal_code" class="mb-1">Postal Code
                                                    <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control"
                                                    id="destination_postal_code" placeholder="Postal Code"
                                                    name="destination_postal_code" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="destination_country" class="mb-1">Country <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="destination_country"
                                                    placeholder="Country" name="destination_country" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> --}}

                            <h5 class="mb-3 mt-4">Service Options</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="service_type" class="mb-1">Service Type <span
                                                class="text-danger">*</span></label>
                                        <select class="form-control" id="service_type" name="service_type" required>
                                            <option value="">Select Service Type</option>

                                            <option value="FIRST_OVERNIGHT" {{($sale->shipping_method ?? '') == 'FIRST_OVERNIGHT' ? 'selected':''}}>First Overnight
                                            </option>
                                            <option value="PRIORITY_OVERNIGHT" {{($sale->shipping_method ?? '') == 'PRIORITY_OVERNIGHT' ? 'selected':''}} >Priority Overnight
                                            </option>
                                            <option value="FEDEX_2_DAY" {{($sale->shipping_method ?? '') == 'FEDEX_2_DAY' ? 'selected':''}} >FedEx 2 Day</option>
                                            <option value="FEDEX_EXPRESS_SAVER" {{($sale->shipping_method ?? '') == 'FEDEX_EXPRESS_SAVER' ? 'selected':''}} >FedEx Express Saver
                                            </option>
                                            <option value="FEDEX_GROUND" {{($sale->shipping_method ?? '') == 'FEDEX_GROUND' ? 'selected':''}} >FedEx Ground</option>
                                        </select>
                                    </div>
                                </div>
                                {{-- <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="dropoff_type" class="mb-1">Dropoff Type <span
                                                class="text-danger">*</span></label>
                                        <select class="form-control" id="dropoff_type" name="dropoff_type" required>
                                            <option value="">Select Dropoff Type</option>
                                            <option value="REGULAR_PICKUP">Regular Pickup</option>
                                            <option value="REQUEST_COURIER_PICKUP">Request Courier Pickup</option>
                                            <option value="DROPOFF_AT_FEDEX_LOCATION">Dropoff at FedEx Location
                                            </option>
                                        </select>
                                    </div>
                                </div> --}}
                            </div>

                            <h5 class="mb-3 mt-4">Package Information</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="package_weight_unit" class="mb-1">Weight Unit<span
                                                class="text-danger">*</span></label>
                                        <select name="package_weight_unit" id="package_weight_unit" class="form-control"
                                            required>
                                            <option value="LB">Pounds (LB)</option>
                                            <option value="KG">Kilograms (KG)</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="package_weight" class="mb-1">Weight <span
                                                class="text-danger">*</span></label>
                                        <input type="number" class="form-control" id="package_weight"
                                            placeholder="Weight" name="package_weight" required>
                                        <span class="small">lbs / kg</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="dimension_unit" class="mb-1">Dimension Unit <span
                                                class="text-danger">*</span></label>
                                        <select name="dimension_unit" id="dimension_unit" class="form-control" required>
                                            <option value="IN">Inches (IN)</option>
                                            <option value="CM">Centimeters (CM)</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="package_length" class="mb-1"> Length <span
                                                class="text-danger">*</span></label>
                                        <input type="number" class="form-control" id="package_length"
                                            placeholder="Length" name="package_length" required>
                                        <span class="small">in / cm</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="package_width" class="mb-1">Width <span
                                                class="text-danger">*</span></label>
                                        <input type="number" class="form-control" id="package_width"
                                            placeholder="Width" name="package_width" required>
                                        <span class="small">in / cm</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="package_height" class="mb-1">Height <span
                                                class="text-danger">*</span></label>
                                        <input type="number" class="form-control" id="package_height"
                                            placeholder="Height" name="package_height" required>
                                        <span class="small">in / cm</span>
                                    </div>
                                </div>
                            </div>


                            {{-- <h5 class="mb-3 mt-4">Label Settings</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="label_format" class="mb-1">Label Format <span
                                                class="text-danger">*</span></label>
                                        <select class="form-control" id="label_format" name="label_format" required>
                                            <option value="">Select Label Format</option>
                                            <option value="PNG">PNG (for plain paper printing)</option>
                                            <option value="ZPL">ZPL (for Zebra printer)</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="screen_resolution" class="mb-1">Screen Resolution</label>
                                        <input type="number" class="form-control" id="screen_resolution"
                                            placeholder="Screen Resolution" name="screen_resolution">
                                        <span class="small">dpi</span>
                                    </div>
                                </div>
                            </div> --}}

                            <div class="text-start  mt-4">
                                <button type="submit" class="btn save-btn text-white">Submit</button>
                            </div>
                        </div>

                    </div>


                </form>

            </div>
        </div>
    </div>
</div>
<!--end page wrapper -->
@endsection
