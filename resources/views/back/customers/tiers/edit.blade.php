@extends('back.layout.app')
@section('title', 'Add Unit')
@section('content')
    <div class="content">
        <div class="container-fluid px-4">
            @include('back.layout.errors')

            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="mt-5 fs-2">Edit Tier </h1>
                    <p>Edit a Tier
                    </p>
                </div>
            </div>
            <div class="create-product-form rounded">
                <form action="{{route('tiers.update',$tier->id)}}" method="post" enctype="multipart/form-data" class="mt-4">
                    @csrf
                    @method('PUT')
                    <div class="form-group mt-1">
                        <label for="name">Tier Name</label>
                        <input type="text" name="name" id="name" class="form-control mt-2" value="{{$tier->name}}">
                    </div>
                    <div class="form-group mt-1">
                        <label for="discount">Discount (%)</label>
                        <input type="text" name="discount" id="discount" class="form-control mt-2" value="{{$tier->discount}}">
                    </div>
                  
                    
                    <button type="submit" class="btn btn-primary mt-2">Save</button>
                </form>
            </div>
        </div>
    </div>
@endsection
