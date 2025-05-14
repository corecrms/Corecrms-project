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


                @include('back.layout.errors')


                <form action="{{ route('store.shipment') }}" method="POST">
                    @csrf
                    @method('POST')

                    <div class="">

                        <div class="row">
                            <input type="hidden" value="{{ $sale->id }}" name="sale_id">

                            <h5 class="mb-3 mt-4">Service Options</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="service_type" class="mb-1">Service Type <span
                                                class="text-danger">*</span></label>
                                        <select class="form-control" id="service_type" name="service_type" required>
                                            <option value="">Select Service Type</option>

                                            <option value="FIRST_OVERNIGHT"
                                                {{ ($sale->shipping_method ?? '') == 'FIRST_OVERNIGHT' ? 'selected' : '' }}>
                                                First Overnight
                                            </option>
                                            <option value="PRIORITY_OVERNIGHT"
                                                {{ ($sale->shipping_method ?? '') == 'PRIORITY_OVERNIGHT' ? 'selected' : '' }}>
                                                Priority Overnight
                                            </option>
                                            <option value="FEDEX_2_DAY"
                                                {{ ($sale->shipping_method ?? '') == 'FEDEX_2_DAY' ? 'selected' : '' }}>
                                                FedEx 2 Day</option>
                                            <option value="FEDEX_EXPRESS_SAVER"
                                                {{ ($sale->shipping_method ?? '') == 'FEDEX_EXPRESS_SAVER' ? 'selected' : '' }}>
                                                FedEx Express Saver
                                            </option>
                                            <option value="FEDEX_GROUND"
                                                {{ ($sale->shipping_method ?? '') == 'FEDEX_GROUND' ? 'selected' : '' }}>
                                                FedEx Ground</option>
                                        </select>
                                    </div>
                                </div>

                            </div>


                            {{-- @php
                                $package_weight = 0;
                                $package_length = 0;
                                $package_width = 0;
                                $package_height = 0;

                                foreach ($sale->productItems as $item) {
                                    // Check if weight is not in KG, convert LB to KG
                                    if ($item->product->product_weight_unit != 'KG') {
                                        $package_weight += ($item->product->product_weight ?? 0) * 0.453592; // LB to KG conversion
                                    } else {
                                        $package_weight += $item->product->product_weight ?? 0;
                                    }

                                    // Check if dimensions are not in CM, convert IN to CM
                                    if ($item->product->product_dimension_unit != 'CM') {
                                        $package_length += ($item->product->product_length ?? 0) * 2.54; // IN to CM conversion
                                        $package_width += ($item->product->product_width ?? 0) * 2.54; // IN to CM conversion
                                        $package_height += ($item->product->product_height ?? 0) * 2.54; // IN to CM conversion
                                    } else {
                                        $package_length += $item->product->product_length ?? 0;
                                        $package_width += $item->product->product_width ?? 0;
                                        $package_height += $item->product->product_height ?? 0;
                                    }
                                }
                            @endphp --}}
                            @php
                                $package_weight = 0;
                                $package_length = 0;
                                $package_width = 0;
                                $package_height = 0;

                                foreach ($sale->productItems as $item) {
                                    // Check if weight is not in LB, convert KG to LB
                                    if ($item->product->product_weight_unit != 'LB') {
                                        $package_weight += ($item->product->product_weight ?? 0) * 2.20462; // KG to LB conversion
                                    } else {
                                        $package_weight += $item->product->product_weight ?? 0;
                                    }

                                    // Check if dimensions are not in IN, convert CM to IN
                                    if ($item->product->product_dimension_unit != 'IN') {
                                        $package_length += ($item->product->product_length ?? 0) * 0.393701; // CM to IN conversion
                                        $package_width += ($item->product->product_width ?? 0) * 0.393701; // CM to IN conversion
                                        $package_height += ($item->product->product_height ?? 0) * 0.393701; // CM to IN conversion
                                    } else {
                                        $package_length += $item->product->product_length ?? 0;
                                        $package_width += $item->product->product_width ?? 0;
                                        $package_height += $item->product->product_height ?? 0;
                                    }
                                }
                            @endphp

                            <h5 class="mb-3 mt-4">Package Information</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="package_weight_unit" class="mb-1">Weight Unit<span
                                                class="text-danger">*</span></label>
                                        <select name="package_weight_unit" id="package_weight_unit" class="form-control"
                                            required>
                                            <option value="LB" selected>Pounds (LB)</option>
                                            <option value="KG">Kilograms (KG)</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="package_weight" class="mb-1">Weight <span
                                                class="text-danger">*</span></label>
                                        <input type="number" class="form-control" id="package_weight"
                                            placeholder="Weight" name="package_weight" required
                                            value="{{ $package_weight ?? 0 }}">
                                        <span class="small">lbs / kg</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="dimension_unit" class="mb-1">Dimension Unit <span
                                                class="text-danger">*</span></label>
                                        <select name="dimension_unit" id="dimension_unit" class="form-control" required>
                                            <option value="IN" selected>Inches (IN)</option>
                                            <option value="CM">Centimeters (CM)</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="package_length" class="mb-1"> Length <span
                                                class="text-danger">*</span></label>
                                        <input type="number" class="form-control" id="package_length"
                                            placeholder="Length" name="package_length" required
                                            value="{{ $package_length ?? 0 }}">
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
                                            placeholder="Width" name="package_width" required
                                            value="{{ $package_width ?? 0 }}">
                                        <span class="small">in / cm</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="package_height" class="mb-1">Height <span
                                                class="text-danger">*</span></label>
                                        <input type="number" class="form-control" id="package_height"
                                            placeholder="Height" name="package_height" required
                                            value="{{ $package_height ?? 0 }}">
                                        <span class="small">in / cm</span>
                                    </div>
                                </div>
                            </div>


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
