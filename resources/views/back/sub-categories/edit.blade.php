@extends('back.layout.app')
@section('title', 'Add Category')
@section('content')
    <div class="content">
        <div class="container-fluid px-4">
            @include('back.layout.errors')

            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="mt-5 fs-2">Product Edit Sub Category </h1>
                    <p>Edit a product Sub Category
                    </p>
                </div>
            </div>
            <div class="create-product-form rounded">
                <form action="{{ route('sub-categories.update', $subCategory->id) }}" method="POST">
                    @method('PUT')
                    @csrf
                    <div class="row mb-3">
                        <div class="col-lg-4  col-md-6">
                            <div class="form-group">
                                <label for="exampleFormControlSelect1">Parent Category</label>
                                <select class="form-control form-select" name="category_id"
                                    aria-label="Default select example" id="exampleFormControlSelect1">
                                    <option>Choose Category</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ $category->id == $subCategory->category_id ? 'selected' : '' }}>
                                            {{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="exampleFormControlInput1">Category Name</label>
                                <input type="text" class="form-control" name="name" id="exampleFormControlInput1"
                                    placeholder="" value="{{ $subCategory->name }}" required>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="exampleFormControlInput1">Category Code</label>
                                <input type="text" class="form-control" name="code" id="exampleFormControlInput1"
                                    placeholder="" value="{{ $subCategory->code }}" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group mb-4">
                        <label for="exampleFormControlTextarea1">Description</label>
                        <textarea class="form-control" id="exampleFormControlTextarea1" name="description" rows="3">{{ $subCategory->description }}</textarea>
                    </div>

                    <button type="submit" class="btn confirm-btn me-2">Submit</button>
                    <a href="{{ route('sub-categories.index') }}" class="btn cancel-btn">Back</a>
                </form>
            </div>
        </div>
    </div>
@endsection
