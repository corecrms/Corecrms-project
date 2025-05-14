@extends('back.layout.app')
@section('title', 'Add Deposit Category')
@section('content')
    <div class="content">
        <div class="container-fluid px-4">
            @include('back.layout.errors')

            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="mt-5 fs-2">Product Add Deposit Category </h1>
                    <p>Add a product Deposit Category
                    </p>
                </div>
            </div>
            <div class="create-product-form rounded">

                <form action="{{ route('deposit-categories.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="exampleFormControlInput1">Deposit Category Name</label>
                                <input type="text" class="form-control" name="name" id="exampleFormControlInput1"
                                    placeholder="" required>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="exampleFormControlInput1">Deposit Category Logo</label>
                                <input type="file" class="form-control" name="deposit-category_img" id="exampleFormControlInput1"
                                    placeholder="" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group mb-4">
                        <label for="exampleFormControlTextarea1">Description</label>
                        <textarea class="form-control" id="exampleFormControlTextarea1" name="description" rows="3"></textarea>
                    </div>

                    <button type="submit" class="btn confirm-btn me-2">Submit</button>
                    <a href="{{ route('deposit-categories.index') }}" class="btn cancel-btn">Back</a>
                </form>
            </div>
        </div>
    </div>
@endsection
