@extends('back.layout.app')
@section('title', 'Add Product')
@section('style')
    <link type="text/css" rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    {{-- CKEditor CDN --}}
    <script src="https://cdn.ckeditor.com/ckeditor5/23.0.0/classic/ckeditor.js"></script>

    {{-- <link type="text/css" rel="stylesheet" href="{{ asset('back/assets/css/image-uploader.css') }}"> --}}

    <style>
        .ck-editor__editable_inline {
            min-height: 150px;
        }

        .variant-section {
            margin-bottom: 20px;
            margin-top: 10px
        }

        .variant-input {
            margin-bottom: 10px;
        }

        .bi-trash3 {
            pointer-events: none;
        }

        .upload__box {
            padding: 10px;
        }

        .upload__inputfile {
            width: 0.1px;
            height: 0.1px;
            opacity: 0;
            overflow: hidden;
            position: absolute;
            z-index: -1;
        }

        .upload__btn {
            display: inline-block;
            font-weight: 600;
            /* color: #fff; */
            text-align: center;
            /* min-width: 116px; */
            /* padding: 5px; */
            transition: all 0.3s ease;
            cursor: pointer;
            /* border: 2px solid; */
            /* background-color: #4045ba;
                     border-color: #4045ba; */
            /* border-radius: 10px;
                     line-height: 26px;
                     font-size: 14px; */
        }

        /* .upload__btn:hover {
                     background-color: unset;
                     color: #4045ba;
                     transition: all 0.3s ease;
                    } */
        .upload__btn-box {
            margin-bottom: 10px;
        }

        .upload__img-wrap {
            display: flex;
            flex-wrap: wrap;
            margin: 0 -10px;
        }

        .upload__img-box {
            width: 90px;
            padding: 0 10px;
            margin-bottom: 12px;
        }

        .upload__img-close {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background-color: rgba(0, 0, 0, 0.5);
            position: absolute;
            top: 10px;
            right: 10px;
            text-align: center;
            line-height: 24px;
            z-index: 1;
            cursor: pointer;
        }

        .upload__img-close:after {
            content: '\2716';
            font-size: 14px;
            color: white;
        }

        .img-bg {
            background-repeat: no-repeat;
            background-position: center;
            background-size: cover;
            position: relative;
            padding-bottom: 100%;
            border-radius: 15%;
        }
    </style>
@endsection
@section('content')
    <div class="content">
        @if (count($errors) > 0)
            <div class="mt-5 alert alert-danger">
                <strong>Whoops!</strong> There were some problems with your input.<br><br>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('error'))
            <div class="mt-5 alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Error!</strong> {{ session('error') }}.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('success'))
            <div class="mt-2 alert alert-success alert-dismissible fade show" role="alert">
                <strong>Success!</strong> {{ session('success') }}.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <div class="container-fluid py-5 px-4 bg-white">
            <div class="border-bottom">
                <h3 class="all-adjustment text-center pb-2 mb-0">Create Product</h3>
            </div>
            <form action="{{ route('products.store') }}" id="productForm" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row mt-4">
                    @include('back.layout.errors')
                    <div class="col-md-8">

                        <div class="card rounded-3">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="product_name">Product Name</label>
                                            <input type="text" class="form-control mt-2" name="name" id="product_name"
                                                placeholder="">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="customers">Brand</label>
                                            <select class="form-control form-select subheading mt-1"
                                                aria-label="Default select example" name="brand_id" id="brand_id" required>
                                                <option>Select Brand</option>
                                                @foreach ($brands as $brand)
                                                    <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-6">

                                        <div class="form-group">
                                            <label for="category_id">Category</label>
                                            <select class="form-control form-select subheading mt-1"
                                                aria-label="Default select example" name="category_id" id="category_id"
                                                required>
                                                <option>Select Category</option>
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category->id }}"
                                                        data-brand-id="{{ $category->brand_id }}">{{ $category->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="category_id">Sub Category</label>
                                            <select class="form-control form-select subheading mt-1"
                                                aria-label="Default select example" name="sub_category_id"
                                                id="sub_category_id" required>
                                                <option>Select Sub Category</option>
                                                @foreach ($subCategories as $subCategory)
                                                    <option value="{{ $subCategory->id }}"
                                                        data-category-id="{{ $subCategory->category_id }}">
                                                        {{ $subCategory->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="form-group mb-3">
                                        <label for="warehouses">Warehouse</label>
                                        <select class="form-control form-select subheading mt-1" required
                                            aria-label="Default select example" name="warehouse_id"id="warehouses"
                                            @if (auth()->user()->hasRole(['Cashier', 'Manager'])) disabled @endif>
                                            <option>Select Warehouse</option>
                                            @foreach ($warehouses as $warehouse)
                                                <option value="{{ $warehouse->id }}"
                                                    @if (auth()->user()->hasRole(['Cashier', 'Manager'])) selected @endif>
                                                    {{ $warehouse->users->name ?? ''}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="description">Product Description</label>
                                            <textarea class="form-control subheading mt-1" id="description" name="description" rows="5"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card rounded-3 mt-4">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">

                                        <div class="form-group">
                                            <label for="type" class="mb-1">Product Type</label>
                                            <select name="product_type" id="product_type"
                                                class="form-control form-select subheading mt-1">
                                                <option value="" selected disabled>Select product type</option>
                                                <option value="standard">Standard Product</option>
                                                {{-- <option value="variable">Variable Product</option> --}}
                                                <option value="service">Service Product</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3" id="purchase_price">
                                        <div class="form-group">
                                            <div class="form-group">
                                                <label for="purchase_price">Product Cost</label>
                                                <input type="text" class="form-control subheading mt-1"
                                                    name="purchase_price" placeholder="Enter Product Cost">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3" id="sell_price">
                                        <div class="form-group">
                                            <div class="form-group">
                                                <label for="sell_price">Product Price</label>
                                                <input type="text" class="form-control subheading mt-1"
                                                    name="sell_price" placeholder="Enter Product Price" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 unit-display">
                                        <div class="form-group">
                                            <label for="product_unit">Product Unit</label>
                                            <select class="form-control form-select subheading mt-1" id="product_unit"
                                                name="product_unit">
                                                <option disabled>Select Unit</option>
                                                @foreach ($mainUnits as $unit)
                                                    <option value="{{ $unit->id }}"
                                                        {{ $loop->iteration == 1 ? 'selected' : '' }}>{{ $unit->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6 unit-display">
                                        <div class="form-group">
                                            <label for="sale_unit">Sale Unit</label>
                                            <select class="form-control form-select subheading mt-1" id="sale_unit"
                                                name="sale_unit">

                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6 unit-display">

                                        <div class="form-group">
                                            <label for="purchase_unit">Purchase Unit</label>
                                            <select class="form-control form-select subheading mt-1" id="purchase_unit"
                                                name="purchase_unit">

                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6" id="stock_alert">
                                        <div class="form-group">
                                            <label for="stock_alert" class="mb-1">Stock Alert</label>
                                            <input type="number" name="stock_alert"
                                                class="form-control subheading mt-2"">
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-2 variant-form" style="display: none">
                                    <div class="col-md-9">
                                        <div class="form-group">
                                            <input type="text" class="variant-name-input form-control subheading mt-2"
                                                placeholder="Enter Variant Name">
                                        </div>
                                    </div>
                                    <div class="col-md-3">

                                        <button type="button" class="btn create-btn w-100 mt-2 add-variant">
                                            Create <i class="fa-solid fa-plus ms-2"></i>
                                        </button>
                                    </div>

                                    <div class="row mt-3">
                                        <div class="variants-container">
                                            <!-- Dynamic variant sections will be added here -->
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="card rounded-3 mt-4">
                            <div class="card-body">
                                {{-- <h6 class="mb-3 mt-1 heading">Package Information</h6> --}}
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="product_weight_unit" class="mb-1">Weight Unit<span
                                                    class="text-danger">*</span></label>
                                            <select name="product_weight_unit" id="product_weight_unit"
                                                class="form-control" required>
                                                <option value="LB">Pounds (LB)</option>
                                                <option value="KG">Kilograms (KG)</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="product_weight" class="mb-1">Weight <span
                                                    class="text-danger">*</span></label>
                                            <input type="number" class="form-control" id="product_weight"
                                                placeholder="Weight" name="product_weight" required step="0.01" value="1">
                                            <span class="small">lbs / kg</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="product_dimension_unit" class="mb-1">Dimension Unit <span
                                                    class="text-danger">*</span></label>
                                            <select name="product_dimension_unit" id="product_dimension_unit" class="form-control"
                                                required>
                                                <option value="IN">Inches (IN)</option>
                                                <option value="CM">Centimeters (CM)</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="product_length" class="mb-1"> Length <span
                                                    class="text-danger">*</span></label>
                                            <input type="number" class="form-control" id="product_length"
                                                placeholder="Length" name="product_length" required step="0.01" value="1">
                                            <span class="small">in / cm</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="product_width" class="mb-1">Width <span
                                                    class="text-danger">*</span></label>
                                            <input type="number" class="form-control" id="product_width"
                                                placeholder="Width" name="product_width" required step="0.01" value="1">
                                            <span class="small">in / cm</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="product_height" class="mb-1">Height <span
                                                    class="text-danger">*</span></label>
                                            <input type="number" class="form-control" id="product_height"
                                                placeholder="Height" name="product_height" required step="0.01" value="1">
                                            <span class="small">in / cm</span>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="card rounded-3 mt-4">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="order_tax">Order Tax</label>
                                            <input type="number" class="form-control subheading mt-2" placeholder="0%"
                                                id="order_tax" name="order_tax" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">

                                        <div class="form-group">
                                            <label for="tax_type">Tax Type</label>
                                            <select class="form-control form-select subheading mt-1"
                                                aria-label="Default select example" id="tax_type" name="tax_type">
                                                @foreach ($taxes as $tax)
                                                    <option value="{{ $tax->id }}">{{ $tax->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card rounded-3 mt-4">
                            <div class="card-body" id="barcodeSection">
                                <div class="row border-bottom mt-2 barcodeRow" id="barcode1">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="barcodeSymbology1">Barcode Symbology *</label>
                                            <select class="form-control form-select subheading mt-1"
                                                aria-label="Default select example" id="barcodeSymbology1"
                                                name="barcode">
                                                <option value="Code 128">Code 128</option>
                                                <option value="Code 39">Code 39</option>
                                                <option value="EAN8">EAN8</option>
                                                <option value="EAN13">EAN13</option>
                                                <option value="UPC">UPC</option>

                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="productCode">Product Code</label>
                                        <div class="input-group mt-1 subheading">
                                            <input type="text" class="form-control subheading" placeholder="Barcode"
                                                aria-label="Recipient's username" aria-describedby="basic-addon2"
                                                name="productCode" id="productCode" />
                                            <span class="input-group-text" id="basic-addon2 subheading"><i
                                                    class="bi bi-upc-scan"></i></span>
                                        </div>
                                        <p>Scan the barcode or symbology</p>
                                    </div>
                                </div>

                                <div class="row mt-4 bg-light align-middle p-3 rounded-3 mx-1" id="barcodeButtonSection">
                                    <div class="col-md-6">
                                        <p>You can scan more than one barcode for a product.</p>
                                    </div>
                                    <div class="col-md-6 text-end">
                                        <button type="button" id="addBarcode"
                                            class="text-primary btn btn-light border-0 bg-transparent"
                                            style="cursor: pointer">
                                            Add another barcode
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn save-btn text-white mt-3" id="productCreateBtn">
                            <div class="spinner-border text-light spinner-border-sm ms-2 me-2" role="status"
                                id="btn-spinner" style="display: none">
                            </div>
                            <span id="btn-text">Create</span>
                            {{-- Create --}}
                        </button>

                    </div>

                    <div class="col-md-4">
                        <div class="card shadow border-0 rounded-3">
                            <div class="card-header bg-white p-3">
                                <p class="m-0">Product Images</p>
                            </div>

                            <div class="card-body upload__box">
                                <div class="file-upload upload__btn-box">
                                    <label class="upload__btn">
                                        <img src="{{ asset('back/assets/dasheets/img/upload-btn.svg') }}"
                                            class="img-fluid" alt="">
                                        <p class="mt-2 subheading file-input-div">
                                            Drag and Drop to upload or
                                        </p>

                                        <button type="button" class="btn create-btn mt-2 file-input-div">Select
                                            Image</button>
                                        <input type="file" multiple data-max_length="20" class="file-input"
                                            name="img[]">
                                    </label>
                                </div>
                                <div class="upload__img-wrap"></div>
                            </div>

                        </div>

                        <div class="card rounded-3 shadow border-0 mt-3">
                            <div class="card-body">
                                <div class="row">
                                    <div class=" col-1">
                                        <label for="myCheckbox09" class="checkbox d-flex mt-1">
                                            <input class="checkbox__input" type="checkbox" name="imei_no" id="imei_no"
                                                value="1" />
                                            <svg class="checkbox__icon" xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 22 22">
                                                <rect width="21" height="21" x=".5" y=".5" fill="#FFF"
                                                     stroke="#FE9F43" rx="3"></rect>
                                                <path class="tick"  stroke="#FE9F43" fill="none"
                                                    stroke-linecap="round" stroke-width="3" d="M4 10l5 5 9-9"></path>
                                            </svg>
                                        </label>
                                    </div>
                                    <div class="col-10">
                                        <label for="imei_no" class="m-0">This Item has IMEI number</label>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-1">
                                        <label for="myCheckbox09" class="checkbox d-flex mt-1">
                                            <input type="checkbox" class="checkbox__input" name="status"
                                                id="product_live" value="1" />
                                            <svg class="checkbox__icon" xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 22 22">
                                                <rect width="21" height="21" x=".5" y=".5" fill="#FFF"
                                                     stroke="#FE9F43" rx="3"></rect>
                                                <path class="tick"  stroke="#FE9F43" fill="none"
                                                    stroke-linecap="round" stroke-width="3" d="M4 10l5 5 9-9"></path>
                                            </svg>
                                        </label>
                                    </div>
                                    <div class="col-10">
                                        <label for="product_live" class="m-0">This Product is Live</label>
                                    </div>
                                </div>
                                <div class="form-group mt-2" id="product_imei_input" style="display: none;">
                                    <label for="product_imei_no">IMEI #</label>
                                    <input type="text" class="form-control subheading" placeholder="IMEI #"
                                        name="product_imei_no" id="product_imei_no" />
                                </div>
                            </div>
                        </div>

                        <div class="card rounded-3 border-0 mt-3 card-shadow">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-1">
                                        <label for="myCheckbox09" class="checkbox d-flex mt-1">
                                            <input class="checkbox__input" type="checkbox" id="new_product"
                                                name="new_product" value="1" />
                                            <svg class="checkbox__icon" xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 22 22">
                                                <rect width="21" height="21" x=".5" y=".5" fill="#FFF"
                                                     stroke="#FE9F43" rx="3" />
                                                <path class="tick"  stroke="#FE9F43" fill="none"
                                                    stroke-linecap="round" stroke-width="3" d="M4 10l5 5 9-9" />
                                            </svg>
                                        </label>
                                        <!-- <input type="checkbox" name="" id=""/> -->
                                    </div>
                                    <div class="col-10">
                                        <label for="new_product">Add to New product</label>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-1">
                                        <!-- <input type="checkbox" name="" id="" /> -->
                                        <label for="myCheckbox09" class="checkbox d-flex mt-1">
                                            <input class="checkbox__input" type="checkbox" id="featured_product"
                                                name="featured_product" value="1" />
                                            <svg class="checkbox__icon" xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 22 22">
                                                <rect width="21" height="21" x=".5" y=".5" fill="#FFF"
                                                     stroke="#FE9F43" rx="3" />
                                                <path class="tick"  stroke="#FE9F43" fill="none"
                                                    stroke-linecap="round" stroke-width="3" d="M4 10l5 5 9-9" />
                                            </svg>
                                        </label>
                                    </div>
                                    <div class="col-10">
                                        <label for="featured_product">Add to featured product</label>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-1">
                                        <!-- <input type="checkbox" name="" id="" /> -->
                                        <label for="myCheckbox09" class="checkbox d-flex mt-1">
                                            <input class="checkbox__input" type="checkbox" id="best_seller"
                                                name="best_seller" value="1" />
                                            <svg class="checkbox__icon" xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 22 22">
                                                <rect width="21" height="21" x=".5" y=".5" fill="#FFF"
                                                     stroke="#FE9F43" rx="3" />
                                                <path class="tick"  stroke="#FE9F43" fill="none"
                                                    stroke-linecap="round" stroke-width="3" d="M4 10l5 5 9-9" />
                                            </svg>
                                        </label>
                                    </div>
                                    <div class="col-10">
                                        <label for="best_seller">Add to Best Seller</label>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-1">
                                        <!-- <input type="checkbox" name="" id="" /> -->
                                        <label for="myCheckbox09" class="checkbox d-flex mt-1">
                                            <input class="checkbox__input" type="checkbox" id="recommended"
                                                name="recommended" value="1" />
                                            <svg class="checkbox__icon" xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 22 22">
                                                <rect width="21" height="21" x=".5" y=".5" fill="#FFF"
                                                     stroke="#FE9F43" rx="3" />
                                                <path class="tick"  stroke="#FE9F43" fill="none"
                                                    stroke-linecap="round" stroke-width="3" d="M4 10l5 5 9-9" />
                                            </svg>
                                        </label>
                                    </div>
                                    <div class="col-10">
                                        <label for="recommended" class="">Add to Recommended</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card rounded-3 shadow border-0 mt-3 p-2">
                            <div class="card-header rounded-3 bg-white border-0 m-0">
                                <p class="m-0">Registered Barcode(s)</p>
                            </div>
                            <div class="card-body p-0 ps-3 m-0">
                                <p class="m-0" id="barcodeCount">1</p>
                            </div>
                        </div>
                        <div class="card rounded-3 shadow border-0 mt-3 p-2 pb-5">
                            <div class="card-header rounded-3 bg-white border-0 m-0">
                                <p class="m-0">Bin Location</p>
                            </div>
                            <div class="card-body p-0 p-3 m-0">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group mt-2">
                                            <label for="ailse" class="form-label">Ailse</label>
                                            <input type="text" class="form-control subheading" placeholder="Enter Ailse"
                                                name="ailse" id="ailse" />
                                        </div>
                                        <div class="form-group mt-2">
                                            <label for="rack" class="form-label">Rack</label>
                                            <input type="text" class="form-control subheading" placeholder="Enter Rack"
                                                name="rack" id="rack" />
                                        </div>
                                        <div class="form-group mt-2">
                                            <label for="shelf" class="form-label">Shelf</label>
                                            <input type="text" class="form-control subheading" placeholder="Enter Shelf"
                                                name="shelf" id="shelf" />
                                        </div>
                                        <div class="form-group mt-2">
                                            <label for="bin" class="form-label">Bin</label>
                                            <input type="text" class="form-control subheading" placeholder="Enter Bin"
                                                name="bin" id="bin" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection

@section('scripts')
    <script type="text/javascript" src="{{ asset('back/assets/js/image-uploader.js') }}"></script>

    <script>
        jQuery(document).ready(function() {
            ImgUpload();
            $('#productForm').on('submit', function(e) {
                e.preventDefault(); // Prevent standard form submission

                product_name = $('#product_name').val();
                // alert special " or ' characters not allowed
                if (product_name.includes('"') || product_name.includes("'")) {
                    toastr.error('Special characters like " or \' are not allowed in product name');
                    return;
                }



                var formData = new FormData(this); // Automatically captures all form data, including files
                console.log($('.file-input')[0].files);
                formData.delete('img[]');

                // Append all selected files to the formData
                $.each(selectedFiles, function(i, file) {
                    formData.append('img[]', file);
                });


                // console.log(formData);
                for (let [key, value] of formData.entries()) {
                    console.log(`${key}: ${value}`);
                }

                $('#btn-text').hide();
                $('#btn-spinner').show();

                let product_type = $('#product_type').val();
                if (product_type != 'service') {
                    if ($('#product_unit').val() == null || $('#sale_unit').val() == null || $(
                            '#purchase_unit').val() == null) {
                        toastr.error('Please select product unit, sale unit and purchase unit');
                        $('#btn-text').show();
                        $('#btn-spinner').hide();
                        return;
                    }
                }



                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        console.log(response);

                        if (response.status == 422) {
                            toastr.error('error');
                        }

                        toastr.success('Product created successfully!');
                        window.location.href = "{{ route('products.index') }}";

                    },
                    error: function(xhr) {
                        if (xhr.status === 422) { // If validation fails
                            var errors = xhr.responseJSON.errors;
                            $.each(errors, function(key, value) {
                                toastr.error(value[0]); // Display first error message
                            });
                        } else {
                            toastr.error('An error occurred while processing your request.');
                        }
                    },
                    complete: function() {
                        $('#btn-text').show();
                        $('#btn-spinner').hide();
                    }
                });
            });
        });
        var selectedFiles = []; // Array to store all selected files

        function ImgUpload() {
            var imgWrap = "";
            var imgArray = [];

            $('.file-input').each(function() {
                $(this).on('change', function(e) {
                    imgWrap = $(this).closest('.upload__box').find('.upload__img-wrap');
                    var maxLength = $(this).attr('data-max_length');

                    var files = e.target.files;
                    var filesArr = Array.prototype.slice.call(files);
                    var iterator = 0;
                    filesArr.forEach(function(f, index) {

                        if (!f.type.match('image.*')) {
                            return;
                        }
                        selectedFiles.push(f);

                        if (imgArray.length > maxLength) {
                            return false
                        } else {
                            var len = 0;
                            for (var i = 0; i < imgArray.length; i++) {
                                if (imgArray[i] !== undefined) {
                                    len++;
                                }
                            }
                            if (len > maxLength) {
                                return false;
                            } else {
                                imgArray.push(f);

                                var reader = new FileReader();
                                reader.onload = function(e) {
                                    var html =
                                        "<div class='upload__img-box'><div style='background-image: url(" +
                                        e.target.result + ")' data-number='" + $(
                                            ".upload__img-close").length + "' data-file='" + f
                                        .name +
                                        "' class='img-bg'><div class='upload__img-close'></div></div></div>";
                                    imgWrap.append(html);
                                    iterator++;
                                }
                                reader.readAsDataURL(f);
                            }
                        }
                    });
                });
            });

            $('body').on('click', ".upload__img-close", function(e) {
                var file = $(this).parent().data("file");
                for (var i = 0; i < imgArray.length; i++) {
                    if (imgArray[i].name === file) {
                        imgArray.splice(i, 1);
                        break;
                    }
                }

                $(this).parent().parent().remove();
            });
        }

        $('#imei_no').change(function() {
            if ($(this).is(':checked')) {
                $('#product_imei_input').show();
                $('#product_imei_no').attr('required', true);
            } else {
                $('#product_imei_input').hide();
                $('#product_imei_no').removeAttr('required');
            }
        });




        // var units = @json($units);
        // $('#product_unit').change(function() {
        //     var unitId = $(this).val();

        //     // Filter the units based on the selected product unit
        //     var childUnits = units.filter(function(unit) {
        //         return unit.parent_id == unitId;
        //     });

        //     // Clear the "Sale Unit" and "Purchase Unit" dropdowns
        //     $('#sale_unit').empty();
        //     $('#purchase_unit').empty();

        //     // Populate the "Sale Unit" and "Purchase Unit" dropdowns
        //     childUnits.forEach(function(unit) {
        //         $('#sale_unit').append('<option value="' + unit.id + '">' + unit.name + '</option>');
        //         $('#purchase_unit').append('<option value="' + unit.id + '">' + unit.name + '</option>');
        //     });
        // });
        var units = @json($units); // Assuming $units contains the list of all units

        $('#product_unit').change(function() {
            var unitId = $(this).val();

            // Filter the units based on the selected product unit
            var childUnits = units.filter(function(unit) {
                return unit.parent_id == unitId;
            });

            // Clear the "Sale Unit" and "Purchase Unit" dropdowns
            $('#sale_unit').empty();
            $('#purchase_unit').empty();

            // Append the parent unit (if available)
            var parentUnit = units.find(function(unit) {
                return unit.id == unitId;
            });
            if (parentUnit) {
                $('#sale_unit').append('<option value="' + parentUnit.id + '">' + parentUnit.name + '</option>');
                $('#purchase_unit').append('<option value="' + parentUnit.id + '">' + parentUnit.name +
                    '</option>');
            }

            // Populate the child units
            childUnits.forEach(function(unit) {
                $('#sale_unit').append('<option value="' + unit.id + '">' + unit.name + '</option>');
                $('#purchase_unit').append('<option value="' + unit.id + '">' + unit.name + '</option>');
            });
        });
        $('#product_unit').trigger('change');



        document.addEventListener('DOMContentLoaded', function() {
            let product_type = document.getElementById('product_type');
            let variant_form = document.querySelector('.variant-form')
            product_type.addEventListener('change', function() {
                if (this.value == 'variable') {
                    variant_form.style.display = 'flex';
                    // document.getElementById('purchase_price').style.display = 'none';
                    // document.getElementById('sell_price').style.display = 'none';
                    document.querySelectorAll('.unit-display').forEach(element => element.style.display =
                        'block');
                    document.getElementById('stock_alert').style.display = 'block';
                } else if (this.value == 'service') {
                    document.getElementById('sell_price').style.display = 'block';
                    document.getElementById('purchase_price').style.display = 'none';
                    document.querySelectorAll('.unit-display').forEach(element => element.style.display =
                        'none');
                    $('#product_unit').val('');
                    $('#sale_unit').val('');
                    $('#purchase_unit').val('');
                    $('.variant-input-fields').val('');
                    variant_form.style.display = 'none';
                    document.getElementById('stock_alert').style.display = 'none';
                } else {
                    $('.variant-input-fields').val('');
                    variant_form.style.display = 'none';
                    document.getElementById('purchase_price').style.display = 'block';
                    document.getElementById('sell_price').style.display = 'block';
                    document.querySelectorAll('.unit-display').forEach(element => element.style.display =
                        'block');
                    document.getElementById('stock_alert').style.display = 'block';
                }
            });

            const variantsContainer = document.querySelector('.variants-container');
            const addVariantButton = document.querySelector('.add-variant');
            const variantNameInput = document.querySelector('.variant-name-input');
            let sectionIndex = 0;

            addVariantButton.addEventListener('click', function() {
                const variantName = variantNameInput.value.trim();
                if (variantName !== '') {
                    const newSection = document.createElement('div');
                    newSection.classList.add('variant-section');
                    newSection.dataset.sectionIndex = sectionIndex;
                    newSection.innerHTML = `
                        <h4>Variant: <span class="variant-name">${variantName}</span></h4>
                        <div class="variant-inputs">
                            <!-- Variant inputs will be added here -->

                            <div class="row">
                                <div class="w-20 subheading mt-2"><b>Variant Name</b></div>
                                <div class="w-20 subheading mt-2"><b>Variant Code</b></div>
                                <div class="w-20 subheading mt-2"><b>Variant Cost</b></div>
                                <div class="w-20 subheading mt-2"><b>Variant Price</b></div>
                            </div>
                        </div>
                        <button class="add-variant-input btn btn-success btn-sm" type="button">Add</button>
                        <button class="remove-section btn btn-danger btn-sm" type="button">Remove Section</button>
                    `;
                    variantsContainer.appendChild(newSection);
                    addVariantInput(newSection, sectionIndex, 0);
                    variantNameInput.value = '';
                    sectionIndex++;
                } else {
                    alert('Please enter a variant name.');
                }
            });

            variantsContainer.addEventListener('click', function(event) {
                const target = event.target;
                if (target.classList.contains('add-variant-input')) {
                    const variantSection = target.closest('.variant-section');
                    const sectionIdx = variantSection.dataset.sectionIndex;
                    const optionIndex = variantSection.querySelectorAll('.variant-input').length;
                    addVariantInput(variantSection, sectionIdx, optionIndex);
                } else if (target.classList.contains('remove-variant-input')) {
                    target.closest('.variant-input').remove();
                } else if (target.classList.contains('remove-section')) {
                    target.closest('.variant-section').remove();
                }
            });

            function addVariantInput(variantSection, sectionIdx, optionIndex) {
                const variantInputs = variantSection.querySelector('.variant-inputs');
                const newVariantInput = document.createElement('div');
                newVariantInput.classList.add('variant-input', 'form-group', 'row', 'mb-3');
                newVariantInput.innerHTML = `
                <input class="form-control subheading mt-2 w-100 variant-input-fields" type="hidden" name="variants[${sectionIdx}][name]" value="${variantSection.querySelector('.variant-name').textContent}">
                <div class="w-20"><input class="form-control subheading mt-2 w-100 variant-input-fields" type="text" name="variants[${sectionIdx}][options][${optionIndex}][code]" placeholder="Variant Code"></div>
                <div class="w-20"><input class="form-control subheading mt-2 w-100 variant-input-fields" type="text" name="variants[${sectionIdx}][options][${optionIndex}][sub_name]" placeholder="Sub Variant Name"></div>
                <div class="w-20"><input class="form-control subheading mt-2 w-100 variant-input-fields" type="text" name="variants[${sectionIdx}][options][${optionIndex}][cost]" placeholder="Variant Cost"></div>
                <div class="w-20"><input class="form-control subheading mt-2 w-100 variant-input-fields" type="text" name="variants[${sectionIdx}][options][${optionIndex}][price]" placeholder="Variant Price"></div>
                <a class="w-20 remove-variant-input btn text-danger border-danger" type="button"><i class="bi bi-trash3"></i></a>
            `;
                variantInputs.appendChild(newVariantInput);
            }
        });

        // Function to remove barcode row
        function removeBarcodeRow(rowId) {
            var row = document.getElementById(rowId);
            if (row) {
                row.remove();
            }
        }
    </script>
    <script>
        ClassicEditor.create(document.querySelector('#description'))
            .catch(error => {
                console.error(error);
            });

        $(document).ready(function() {
            var subCategories = [];

            // Store all subcategory options initially
            $('#sub_category_id option').each(function() {
                var subCategory = {
                    id: $(this).val(),
                    name: $(this).text(),
                    category_id: $(this).data('category-id')
                };
                subCategories.push(subCategory);
            });

            $('#category_id').change(function() {
                var categoryId = $(this).val();

                // Clear the subcategory dropdown
                $('#sub_category_id').empty();
                $('#sub_category_id').append('<option value="">Select Sub Category</option>');

                // Filter subcategories based on the selected category
                var filteredSubCategories = subCategories.filter(function(subCategory) {
                    return subCategory.category_id == categoryId;
                });

                // Populate the subcategory dropdown with filtered subcategories
                filteredSubCategories.forEach(function(subCategory) {
                    $('#sub_category_id').append('<option value="' + subCategory.id + '">' +
                        subCategory.name + '</option>');
                });
            });

            // Trigger change event on page load to populate subcategories if a category is already selected
            $('#category_id').trigger('change');


            // select brands
            let categories = [];

            // Store all category options initially
            $('#category_id option').each(function() {
                var category = {
                    id: $(this).val(),
                    name: $(this).text(),
                    brand_id: $(this).data('brand-id')
                };
                categories.push(category);
            });

            $('#brand_id').change(function() {
                var brandId = $(this).val();

                // Clear the category dropdown
                $('#category_id').empty();
                $('#category_id').append('<option value="">Select Category</option>');

                // Filter categories based on the selected brand
                var filteredCategories = categories.filter(function(category) {
                    return category.brand_id == brandId;
                });

                // Populate the category dropdown with filtered categories
                filteredCategories.forEach(function(category) {
                    $('#category_id').append('<option value="' + category.id + '">' +
                        category.name + '</option>');
                });
            });

            $('#brand_id').trigger('change');


        });
    </script>


@endsection
