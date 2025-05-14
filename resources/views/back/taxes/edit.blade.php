@extends('back.layout.app')
@section('title', 'Add Unit')
@section('content')
    <div class="content">
        <div class="container-fluid px-4">
            @include('back.layout.errors')

            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="mt-5 fs-2">Product Edit Unit </h1>
                    <p>Edit a product Unit
                    </p>
                </div>
            </div>
            <div class="create-product-form rounded">

                <form action="{{route('taxes.update',$tax->id)}}" method="post" enctype="multipart/form-data" class="mt-4">
                    @csrf
                    @method('PUT')
                    <div class="form-group mt-1">
                        <label for="name">Tax Type</label>
                        <input type="text" name="name" id="name" class="form-control mt-2" value="{{$tax->name}}">
                    </div>
                    <div class="form-group mt-1">
                        <label for="information">Tax Information</label>
                        <input type="text" name="information" id="information" class="form-control mt-2" value="{{$tax->information}}">
                    </div>
                    <button type="submit" class="btn btn-primary mt-2">Save</button>
                </form>
            </div>
        </div>
    </div>
@endsection
