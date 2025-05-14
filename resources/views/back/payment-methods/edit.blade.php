@extends('back.layout.app')
@section('title', 'Add Category')
@section('content')
    <div class="content">
        


        <div class="container-fluid pt-4 px-4 mb-5">
            <div class="border-bottom">
                <h3 class="all-adjustment text-center pb-2 mb-0">Edit Category</h3>
            </div>

            @include('back.layout.errors')

            <div class="card card-shadow border-0 mt-5 rounded-3">
                <div class="card-header bg-white border-0 rounded-3 p-4">
                    <div class="">
                        <div class="create-product-form rounded">
                            <form action="{{ route('categories.update', $category->id) }}" method="POST">
                                @method('PUT')
                                @csrf
                                <div class="row mb-3">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="exampleFormControlInput1">Category Name</label>
                                            <input type="text" class="form-control" name="name" id="exampleFormControlInput1"
                                                placeholder="" value="{{ $category->name }}" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="exampleFormControlInput1">Category Code</label>
                                            <input type="text" class="form-control" name="code" id="exampleFormControlInput1"
                                                placeholder="" value="{{ $category->code }}" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group mb-4">
                                    <label for="exampleFormControlTextarea1">Description</label>
                                    <textarea class="form-control" id="exampleFormControlTextarea1" name="description" rows="3">{{ $category->description }}</textarea>
                                </div>

                                <button type="submit" class="btn confirm-btn  save-btn  text-white me-2">Submit</button>
                                <a href="{{ route('categories.index') }}" class="btn cancel-btn">Back</a>
                            </form>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
@endsection
