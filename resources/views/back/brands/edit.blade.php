{{-- @extends('back.layout.app')
@section('title', 'Add Brand')
@section('content')
    <div class="content">
        <div class="container-fluid px-4">
            @include('back.layout.errors')

            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="mt-5 fs-2">Product Edit Brand </h1>
                    <p>Edit a product Brand
                    </p>
                </div>
            </div>
            <div class="create-product-form rounded">
                <form action="{{ route('brands.update', $brand->id) }}" method="POST" enctype="multipart/form-data">
                    @method('PUT')
                    @csrf
                    <div class="row mb-3">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="brand_name">Brand Name</label>
                                <input type="text" class="form-control" name="name" id="brand_name" placeholder=""
                                    value="{{ $brand->name }}" required>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="brand_img">Brand Logo</label>
                                <input type="file" class="form-control" name="brand_img" id="brand_img"
                                    value="{{ $brand->brand_img }}">
                            </div>
                        </div>
                    </div>
                    <div class="form-group mb-4">
                        <label for="description">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3">{{ $brand->description }}</textarea>
                    </div>

                    <button type="submit" class="btn confirm-btn me-2">Submit</button>
                    <a href="{{ route('brands.index') }}" class="btn cancel-btn">Back</a>
                </form>
            </div>
        </div>
    </div>
@endsection --}}


@extends('back.layout.app')
@section('title', 'Add Category')
@section('content')
    <div class="content">



        <div class="container-fluid pt-4 px-4 mb-5">
            <div class="border-bottom">
                <h3 class="all-adjustment text-center pb-2 mb-0">Edit Brands</h3>
            </div>

            @include('back.layout.errors')

            <div class="card card-shadow border-0 mt-5 rounded-3">
                <div class="card-header bg-white border-0 rounded-3 p-4">
                    <div class="">
                        <div class="create-product-form rounded">
                            <form action="{{ route('brands.update', $brand->id) }}" method="POST" enctype="multipart/form-data">
                                @method('PUT')
                                @csrf
                                <div class="row mb-3">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="brand_name">Brand Name</label>
                                            <input type="text" class="form-control" name="name" id="brand_name" placeholder=""
                                                value="{{ $brand->name }}" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="brand_img">Brand Logo</label>
                                            <input type="file" class="form-control" name="brand_img" id="brand_img"
                                                value="{{ $brand->brand_img }}">
                                        </div>
                                    </div>

                                </div>
                                <div class="form-group mb-4">
                                    <label for="description">Description</label>
                                    <textarea class="form-control" id="description" name="description" rows="3">{{ $brand->description }}</textarea>
                                </div>

                                <button type="submit" class="btn save-btn text-white me-2">Submit</button>

                            </form>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
@endsection
