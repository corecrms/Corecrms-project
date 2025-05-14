@extends('back.layout.app')
@section('title', 'Add Product')
@section('style')
<link type="text/css" rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<link type="text/css" rel="stylesheet" href="{{ asset('back/assets/css/image-uploader.css') }}">

    <style>
        .variant-section {
            margin-bottom: 20px;
            margin-top: 10px
        }

        .variant-input {
            margin-bottom: 10px;
        }
    </style>
@endsection
@section('content')
    <div class="content">
        <div class="container-fluid px-4">

            @include('back.layout.errors')

            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="mt-5 fs-2">Add Product </h1>
                    <p>Add a Product
                    </p>
                </div>
            </div>
            <div class="create-product-form rounded">

                <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-lg-3 col-md-6">
                                    <div class="form-group">
                                        <label for="exampleFormControlInput1">Product Name</label>
                                        <input type="text" class="form-control" name="name"
                                            id="exampleFormControlInput1" placeholder="">
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6">
                                    <div class="form-group">
                                        <label for="exampleFormControlInput1">SKU</label>
                                        <input type="text" class="form-control" name="sku"
                                            id="exampleFormControlInput1" placeholder="">
                                    </div>
                                </div>
                                <div class="col-lg-3  col-md-6">
                                    <div class="form-group">
                                        <label for="unit_id">Unit</label>
                                        <select class="form-control form-select" aria-label="Default select example"
                                            name="unit_id" id="unit">
                                            <option>Select Unit</option>
                                            @foreach ($units as $unit)
                                                <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-3  col-md-6">
                                    <div class="form-group">
                                        <label for="barcode">Barcode Type :</label>
                                        <select class="form-control form-select" aria-label="Default select example"
                                            name="barcode" id="barcode">
                                            <option>Select Barcode</option>
                                            <option value="1">Code 128</option>
                                            <option value="2">Code 39</option>
                                            <option value="3">Code 93</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-3  col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="category_id">Category</label>
                                        <select class="form-control form-select" aria-label="Default select example"
                                            name="category_id" id="category_id">
                                            <option>Select Category</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-3  col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="exampleFormControlSelect1">Sub Category</label>
                                        <select class="form-control form-select" aria-label="Default select example"
                                            name="sub_category_id" id="exampleFormControlSelect1">
                                            <option>Select Category First</option>
                                            @foreach ($subcategories as $subCategory)
                                                <option value="{{ $subCategory->id }}">{{ $subCategory->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group mb-3">
                                        <label for="exampleFormControlSelect1">Brand</label>
                                        <select class="form-control form-select" aria-label="Default select example"
                                            name="brand_id"id="exampleFormControlSelect1">
                                            <option>Select Brand</option>
                                            @foreach ($brands as $brand)
                                                <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label for="warranty_id">Warranty </label>
                                        <select class="form-control form-select" aria-label="Default select example"
                                            name="warranty_id" id="warranty_id">
                                            <option>Select Warranty</option>
                                            @foreach ($warranties as $warranty)
                                                <option value="{{ $warranty->id }}">{{ $warranty->warranty_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">

                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label for="condition">Condition </label>
                                        <select class="form-control form-select" aria-label="Default select example"
                                            name="condition" id="condition">
                                            <option value="new">New</option>
                                            <option value="used">Used</option>

                                        </select>
                                    </div>
                                </div>


                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label for="tax_info"> Tax Information</label>
                                        <input type="text" class="form-control" id="tax_info" name="tax_info"
                                            placeholder="Enter Tax Info">
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label for="tax_type">Tax Type</label>
                                        <select class="form-control form-select"
                                            aria-label="Default select example" id="tax_type" name="tax_type">
                                            <option value="exclusive">Exclusive</option>
                                            <option value="inclusive">Inclusive</option>
                                        </select>
                                    </div>
                                </div>

                            </div>
                            <div class="col-lg-3 mb-2">
                                <div class="form-group">
                                    <label class="" for="img">Choose Product Image</label>
                                    <input type="file" class="custom-control-input" id="img" name="img[]"
                                        multiple>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="custom-control custom-checkbox">
                                    <input type="hidden" name="tax_applicable" value="0">
                                    <input type="checkbox" class="custom-control-input" id="taxApplyCheckbox"
                                        name="tax_applicable" value="1">
                                    <label class="custom-control-label" for="taxApplyCheckbox">Tax Applicable</label>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="form-group mb-4">
                        <label for="description">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>

                    <div class="mt-4 p-3 pt-1 card mb-4 shadow">
                        <div class="row mt-5 mb-4">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="type" class="mb-1">Product Type</label>
                                    <select name="product_type" id="product_type" class="form-select">
                                        <option value="standard">Standard Product</option>
                                        <option value="variable">Variable Product</option>
                                        <option value="service">Service Product</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3" id="purchase_price">
                                <div class="form-group">
                                    <label for="purchase_price">Product Cost</label>
                                    <input type="text" class="form-control" name="purchase_price"  placeholder="Enter Product Cost">
                                </div>
                            </div>
                            <div class="col-lg-3" id="sell_price">
                                <div class="form-group">
                                    <label for="sell_price">Product Price</label>
                                    <input type="text" class="form-control"  name="sell_price"
                                        placeholder="Enter Product Price">
                                </div>
                            </div>
                            <div class="col-lg-3 unit-display">
                                <div class="form-group">
                                    <label for="product_unit">Product Unit</label>
                                    <select class="form-control form-select" id="product_unit" name="product_unit">
                                        @foreach ($mainUnits as $unit)
                                        <option value="{{ $unit->id }}" >{{ $unit->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3 unit-display">
                                <div class="form-group">
                                    <label for="sale_unit">Sale Unit</label>
                                    <select class="form-control form-select" id="sale_unit" name="sale_unit">

                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3 unit-display">
                                <div class="form-group">
                                    <label for="purchase_unit">Purchase Unit</label>
                                    <select class="form-control form-select" id="purchase_unit" name="purchase_unit">

                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3" id="stock_alert">
                                <div class="form-group">
                                    <label for="stock_alert" class="mb-1">Stock Alert</label>
                                    <input type="number" name="stock_alert" id="stock_alert"  class="form-control">
                                </div>
                            </div>
                        </div>


                        <div class="row" id="vaiant-form" style="display: none">
                            <div id="variants-container">
                                <!-- Initial variant section -->
                                <div class="variant-section">

                                </div>
                                <div class="col-md-6 d-flex">
                                    <input type="text" id="variant-name" placeholder="Enter Variant Name"
                                        class="form-control">
                                    <button id="add-variant" type="button" class="btn btn-primary ms-3">Create</button>
                                </div>

                            </div>
                        </div>

                    </div>
                    <button type="submit" class="btn confirm-btn me-2">Submit</button>
                    <a href="{{ route('products.index') }}" class="btn cancel-btn">Back</a>
                </form>
            </div>



        </div>
    </div>
    </div>
@endsection

@section('scripts')

<script type="text/javascript" src="{{ asset('back/assets/js/image-uploader.js') }}"></script>

    <script>

        var units = @json($units);
        $('#product_unit').change(function() {
            var unitId = $(this).val();

            // Filter the units based on the selected product unit
            var childUnits = units.filter(function(unit) {
                return unit.parent_id == unitId;
            });

            // Clear the "Sale Unit" and "Purchase Unit" dropdowns
            $('#sale_unit').empty();
            $('#purchase_unit').empty();

            // Populate the "Sale Unit" and "Purchase Unit" dropdowns
            childUnits.forEach(function(unit) {
                $('#sale_unit').append('<option value="' + unit.id + '">' + unit.name + '</option>');
                $('#purchase_unit').append('<option value="' + unit.id + '">' + unit.name + '</option>');
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            let product_type = document.getElementById('product_type');
            let variant_form = document.getElementById('vaiant-form')
            product_type.addEventListener('change', function() {
                if (this.value == 'variable') {
                    variant_form.style.display = 'block';
                    document.getElementById('purchase_price').style.display = 'none';
                    document.getElementById('sell_price').style.display = 'none';
                    document.querySelectorAll('.unit-display').forEach(element => element.style.display = 'block');
                    document.getElementById('stock_alert').style.display = 'block';

                }else if (this.value == 'service') {
                    document.getElementById('sell_price').style.display = 'block';
                    document.getElementById('purchase_price').style.display = 'none';
                    document.querySelectorAll('.unit-display').forEach(element => element.style.display = 'none');
                    document.getElementById('stock_alert').style.display = 'none';
                } else {
                    variant_form.style.display = 'none';
                    document.getElementById('purchase_price').style.display = 'block';
                    document.getElementById('sell_price').style.display = 'block';
                    document.querySelectorAll('.unit-display').forEach(element => element.style.display = 'block');
                    document.getElementById('stock_alert').style.display = 'block';

                }
            });


            const variantsContainer = document.getElementById('variants-container');
            const addVariantButton = document.getElementById('add-variant');
            const variantNameInput = document.getElementById('variant-name');
            let sectionIndex = 0;

            addVariantButton.addEventListener('click', function() {
                const variantName = variantNameInput.value.trim();
                if (variantName !== '') {
                    const newSection = document.createElement('div');
                    newSection.classList.add('variant-section');
                    newSection.innerHTML = `
                        <h3 >Variant: <span class="variant-name">${variantName}</span></h3>

                        <div class="variant-inputs">
                            <div class="variant-input form-group row mb-3">
                                <input class="form-control" type="hidden" name="variants[${sectionIndex}][name]" value="${variantName}">
                                <div class="col-md-2"><input class="form-control"  type="text" name="variants[${sectionIndex}][options][0][code]" placeholder="Variant Code"></div>
                                <div class="col-md-2"><input class="form-control"  type="text" name="variants[${sectionIndex}][options][0][sub_name]" placeholder="Sub Variant Name"></div>
                                <div class="col-md-2"><input class="form-control"  type="text" name="variants[${sectionIndex}][options][0][cost]" placeholder="Variant Cost"></div>
                                <div class="col-md-2"><input class="form-control"  type="text" name="variants[${sectionIndex}][options][0][price]" placeholder="Variant Price"></div>
                                <button class="col-md-2 remove-variant-input btn btn-danger btn-sm" type="button">Remove</button>
                            </div>
                        </div>
                        <button class="add-variant-input btn btn-success btn-sm" type="button">Add</button>
                        <button class="remove-section btn btn-danger btn-sm" type="button">Remove Section</button>
                    `;
                    variantsContainer.appendChild(newSection);
                    variantNameInput.value = '';
                    sectionIndex++;
                }
                else
                {
                    alert('Please enter a variant name.');
                }
            });

            variantsContainer.addEventListener('click', function(event) {
                const target = event.target;
                if (target.classList.contains('remove-variant-input')) {
                    const variantInput = target.parentNode;
                    variantInput.remove();
                } else if (target.classList.contains('add-variant-input')) {
                    const variantSection = target.closest('.variant-section');
                    const variantInputs = variantSection.querySelector('.variant-inputs');
                    const newVariantInput = document.createElement('div');
                    newVariantInput.classList.add('variant-input', 'form-group', 'row','mb-3');
                    newVariantInput.innerHTML = `
                        <div class="col-md-2"><input class="form-control" type="text" name="variants[${sectionIndex}][options][0][code]" placeholder="Variant Code"></div>
                        <div class="col-md-2"><input class="form-control" type="text" name="variants[${sectionIndex}][options][0][sub_name]" placeholder="Variant Name"></div>
                        <div class="col-md-2"><input class="form-control" type="text" name="variants[${sectionIndex}][options][0][cost]" placeholder="Variant Cost"></div>
                        <div class="col-md-2"><input class="form-control" type="text" name="variants[${sectionIndex}][options][0][price]" placeholder="Variant Price"></div>
                        <button class="col-md-2 remove-variant-input btn btn-danger btn-sm" type="button">Remove</button>
                    `;
                    variantInputs.appendChild(newVariantInput);
                    sectionIndex++;
                } else if (target.classList.contains('remove-section')) {
                    const variantSection = target.closest('.variant-section');
                    variantSection.remove();
                }
            });

        });
    </script>
@endsection
