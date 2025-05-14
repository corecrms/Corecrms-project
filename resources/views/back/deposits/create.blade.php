@extends('back.layout.app')
@section('title', 'Add Deposit Category')
@section('content')
    <div class="content">
        <div class="container-fluid pt-4 px-4 mb-5">
            @include('back.layout.errors')

            <div class="border-bottom">
                <h3 class="all-adjustment text-center pb-2 mb-0">Create Deposit</h3>
              </div>
            <div class="card card-shadow rounded-3 border-0 mt-4 p-2">

                <form action="{{ route('deposits.store') }}" method="POST" class="card-body">
                    @csrf
                    <div class="row">
                        <div class="col-md-4">
                          <div class="form-group">
                            <label for="date" class="mb-1 fw-bold">Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control subheading" value="{{date('Y-m-d')}}" id="date" name="date" required>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label for="account_id" class="mb-1 fw-bold">Account</label>
                            <select class="form-control form-select subheading" name="account_id" id="account_id" required>
                                <option disabled>Choose Account</option>
                              {{-- @forelse ($accounts as $account)
                                  <option value="{{ $account->id }}">{{ $account->name }}</option>
                                @empty
                                  <option disabled>No Account Found</option>
                              @endforelse --}}
                                @foreach ($accounts as $account)
                                    <option value="{{ $account->id }}">{{ $account->name }}</option>
                                @endforeach
                            </select>
                          </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                              <label for="exampleFormControlSelect1" class="mb-1 fw-bold">Warehouse <span class="text-danger">*</span></label>
                              <select class="form-control form-select subheading" name="warehouse_id" id="warehouse_id" required>
                                  <option disabled >Choose Warehouse</option>
                                @forelse ($warehouses as $warehouse)
                                    <option value="{{ $warehouse->id }}">{{ $warehouse->users->name }}</option>
                                  @empty
                                    <option disabled>No Warehouse Found</option>
                                @endforelse
                              </select>
                            </div>
                          </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-4">
                            <div class="form-group">
                              <label for="exampleFormControlSelect1" class="mb-1 fw-bold">Deposit Category <span class="text-danger">*</span></label>
                              <select class="form-control form-select subheading" name="deposit_category_id" id="deposit_category_id" required>
                                  <option disabled>Choose Deposit Category</option>

                                @foreach ($depositCategories as $depositCategory )
                                  <option value="{{ $depositCategory->id }}">{{ $depositCategory->name }}</option>
                                @endforeach
                              </select>
                            </div>
                          </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label for="amount" class="mb-1 fw-bold">Amount <span class="text-danger">*</span></label>
                            <input type="number" placeholder="Amount" class="form-control subheading" id="amount" name="amount" required>
                          </div>
                        </div>
                    </div>
                    <div class="form-group mb-4 mt-2">
                        <label class="mb-1 fw-bold" for="description">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>
                    {{-- <input type="hidden" name="warehouse_id" value="{{ session('selected_warehouse_id') }}"> --}}
                    <button type="submit" class="btn save-btn text-white mt-3">Submit</button>

                </form>
            </div>
        </div>
    </div>
@endsection
