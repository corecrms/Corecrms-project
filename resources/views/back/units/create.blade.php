@extends('back.layout.app')
@section('title', 'Add Unit')
@section('content')
    <div class="content">
        <div class="container-fluid px-4">
            @include('back.layout.errors')

            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="mt-5 fs-2">Product Add Unit </h1>
                    <p>Add a product Unit
                    </p>
                </div>
            </div>
            <div class="create-product-form rounded">

                <form action="{{ route('units.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="exampleFormControlInput1">Unit Name</label>
                                <input type="text" class="form-control" name="unit_name" id="exampleFormControlInput1"
                                    placeholder="" required>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="exampleFormControlInput1">Unit Code</label>
                                <input type="text" class="form-control" name="unit_code" id="exampleFormControlInput1"
                                    placeholder="" required>
                            </div>
                        </div>
                    </div>


                    <button type="submit" class="btn confirm-btn me-2">Submit</button>
                    <a href="{{ route('units.index') }}" class="btn cancel-btn">Back</a>
                </form>
            </div>
        </div>
    </div>
@endsection
