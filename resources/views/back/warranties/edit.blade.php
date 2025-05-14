@extends('back.layout.app')
@section('title', 'Add Warranty')
@section('content')
    <div class="content">
        <div class="container-fluid px-4">
            @include('back.layout.errors')

            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="mt-5 fs-2">Product Edit Warranty </h1>
                    <p>Edit a product Warranty
                    </p>
                </div>
            </div>
            <div class="create-product-form rounded">
                <form action="{{ route('warranties.update', $warranty->id) }}" method="POST" enctype="multipart/form-data">
                    @method('PUT')
                    @csrf
                    <div class="row mb-3">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="warranty_name">Warranty Name</label>
                                <input type="text" class="form-control" name="warranty_name" id="warranty_name"
                                    placeholder="" value="{{ $warranty->warranty_name }}" required>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="warranty_type">Warranty Type</label>
                                <select name="warranty_type" id="warranty_type" class="form-control">
                                    <option value="guarenteed"
                                        {{ $warranty->warranty_type == 'guarenteed' ? 'selected' : '' }}>Guarenteed</option>
                                    <option value="warranteed"
                                        {{ $warranty->warranty_type == 'warranteed' ? 'selected' : '' }}>Warranteed</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    @php
                        $warrantyPeriodParts = explode(' ', $warranty->warranty_period);
                        $warrantyPeriodQuantity = $warrantyPeriodParts[0];
                        $warrantyPeriodUnit = $warrantyPeriodParts[1];
                    @endphp

                    <div class="col-lg-3">
                        <div class="form-group">
                            <label for="warranty_period_quantity">Warranty Period</label>
                            <div class="input-group">
                                <input type="number" class="form-control" name="warranty_period_quantity"
                                    id="warranty_period_quantity" min="1" value="{{ $warrantyPeriodQuantity }}"
                                    required>
                                <select class="form-select" aria-label="Default select example" name="warranty_period_unit">
                                    <option value="months" {{ $warrantyPeriodUnit == 'months' ? 'selected' : '' }}>Months
                                    </option>
                                    <option value="years" {{ $warrantyPeriodUnit == 'years' ? 'selected' : '' }}>Years
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group mb-4">
                        <label for="description">Description</label>
                        <textarea class="form-control" id="description" name="warranty_description" rows="3">{{ $warranty->warranty_description }}</textarea>
                    </div>

                    <button type="submit" class="btn confirm-btn me-2">Submit</button>
                    <a href="{{ route('warranties.index') }}" class="btn cancel-btn">Back</a>
                </form>
            </div>
        </div>
    </div>
@endsection
