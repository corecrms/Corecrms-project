@extends('back.layout.app')
@section('title', 'Add Warranty')
@section('content')
    <div class="content">
        <div class="container-fluid px-4">
            @include('back.layout.errors')

            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="mt-5 fs-2">Product Add Warranty </h1>
                    <p>Add a product Warranty
                    </p>
                </div>
            </div>
            <div class="create-product-form rounded">


                <form action="{{ route('warranties.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="exampleFormControlInput1">Warranty Name</label>
                                <input type="text" class="form-control" name="warranty_name"
                                    id="exampleFormControlInput1" placeholder="" required>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="warranty_type">Warranty Type</label>
                                <select name="warranty_type" id="warranty_type" class="form-control">
                                    <option value="guarenteed">Guarenteed</option>
                                    <option value="warranteed">Warranteed</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="form-group">
                            <label for="warranty_period_quantity">Warranty Period</label>
                            <div class="input-group">
                                <input type="number" class="form-control" name="warranty_period_quantity"
                                    id="warranty_period_quantity" min="1" required>
                                <select class="form-select" aria-label="Default select example" name="warranty_period_unit">
                                    <option value="months">Months</option>
                                    <option value="years">Years</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-9">
                        <div class="form-group mb-4">
                            <label for="exampleFormControlTextarea1">Description</label>
                            <textarea class="form-control" id="exampleFormControlTextarea1" name="warranty_description" rows="3"></textarea>
                        </div>
                    </div>


                    <button type="submit" class="btn confirm-btn me-2">Submit</button>
                    <a href="{{ route('warranties.index') }}" class="btn cancel-btn">Back</a>
                </form>
            </div>
        </div>
    </div>
@endsection
