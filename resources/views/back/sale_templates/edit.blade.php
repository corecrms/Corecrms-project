@extends('back.layout.app')
@section('title', 'Add Sales Template')
@section('content')
    <div class="content">
        <div class="container-fluid px-4">
            @include('back.layout.errors')

            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="mt-5 fs-2">Edit Sales Template </h1>
                    <p>Edit Sales Template
                    </p>
                </div>
            </div>
            <div class="create-product-form rounded">
                <form action="{{ route('sale_templates.update', $sale_template->id) }}" method="POST">
                    @method('PUT')
                    @csrf
                    <div class="row mb-3">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="exampleFormControlInput1">Name</label>
                                <input type="text" class="form-control" name="name" id="exampleFormControlInput1"
                                    placeholder="" value="{{ $sale_template->name }}" required>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="exampleFormControlInput2">Type</label>
                                <select class="form-control" name="type" id="exampleFormControlInput2" required>
                                    <option value="">Select Type</option>
                                    <option value="invoice" {{ $sale_template->type == 'invoice' ? 'selected' : '' }}>
                                        Invoice</option>
                                    <option value="quotation" {{ $sale_template->type == 'quotation' ? 'selected' : '' }}>
                                        Quotation</option>
                                </select>
                            </div>
                        </div>
                        {{-- <div class="col-lg-6">
                        <div class="form-group mb-4">
                            <label for="exampleFormControlInput3">Default Template</label>
                            <input type="text" class="form-control" name="default_template" id="exampleFormControlInput3" value="{{ $sale_template->default_template }}"/>
                        </div>
                    </div> --}}

                    </div>


                    <button type="submit" class="btn confirm-btn me-2">Submit</button>
                    <a href="{{ route('sale_templates.index') }}" class="btn cancel-btn">Back</a>
                </form>
            </div>
        </div>
    </div>
@endsection
