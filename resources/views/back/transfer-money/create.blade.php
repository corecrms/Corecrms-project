@extends('back.layout.app')
@section('title', 'Add Expense Category')
@section('content')
    <div class="content">
        <div class="container-fluid pt-4 px-4 mb-5">
            @include('back.layout.errors')

            <div class="border-bottom">
                <h3 class="all-adjustment text-center pb-2 mb-0">Create Expense</h3>
              </div>
            <div class="card card-shadow rounded-3 border-0 mt-4 p-2">

                <form action="{{ route('transfer-money.store') }}" method="POST" class="card-body">
                    @csrf
                    <div class="row">
                        <div class="col-md-4">
                          <div class="form-group">
                            <label for="date" class="mb-1 fw-bold">Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control subheading" id="date" name="date" required>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label for="account_id" class="mb-1 fw-bold">Account</label>
                            <select class="form-control form-select subheading" name="account_id" id="account_id" required>
                                <option disabled>Choose Account</option>
                              @forelse ($accounts as $account)
                                  <option value="{{ $account->id }}">{{ $account->name }}</option>
                                @empty
                                  <option disabled>No Account Found</option>
                              @endforelse
                            </select>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label for="exampleFormControlSelect1" class="mb-1 fw-bold">Expense Category <span class="text-danger">*</span></label>
                            <select class="form-control form-select subheading" name="expense_category_id" id="expense_category_id" required>
                                <option disabled >Choose Expense Category</option>
                              @forelse ($expenseCategories as $expenseCategory)
                                  <option value="{{ $expenseCategory->id }}">{{ $expenseCategory->name }}</option>
                                @empty
                                  <option disabled>No Category Found</option>
                              @endforelse
                            </select>
                          </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-4">
                          <div class="form-group">
                            <label for="amount" class="mb-1 fw-bold">Amount <span class="text-danger">*</span></label>
                            <input type="number" placeholder="Amount" class="form-control subheading" id="amount" name="amount">
                          </div>
                        </div>
                    </div>
                    <div class="form-group mb-4 mt-2">
                        <label class="mb-1 fw-bold" for="description">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>
                    <input type="hidden" name="warehouse_id" value="{{ session('selected_warehouse_id') }}">
                    <button type="submit" class="btn save-btn text-white mt-3">Submit</button>
                    
                </form>
            </div>
        </div>
    </div>
@endsection
