@extends('back.layout.app')
@section('title', 'Add Account')
@section('content')
    <div class="content">
        <div class="container-fluid px-4">
            @include('back.layout.errors')

            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="mt-5 fs-2">Add Account </h1>
                    <p>Add Account
                    </p>
                </div>
            </div>
            <div class="create-account-form rounded">

                <form action="{{ route('accounts.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="acc_no">Account Number</label>
                                <input type="text" class="form-control" name="acc_no" id="acc_no"
                                    placeholder="" required>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="name">Account Name</label>
                                <input type="text" class="form-control" name="name" id="name"
                                    placeholder="" required>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="name">Select Customer</label>
                                <select name="customer_id" id="customer_id" class="form-control">
                                    @foreach ($customers as $customer)
                                        <option value="{{$customer->id}}">{{$customer->user->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="init_balance">Initial Balance</label>
                                <input type="number" class="form-control" name="init_balance" id="init_balance"
                                    placeholder="" required>
                            </div>
                        </div>

                    </div>
                    <div class="form-group mb-4">
                        <label for="exampleFormControlTextarea1">Description</label>
                        <textarea class="form-control" id="exampleFormControlTextarea1" name="description" rows="3"></textarea>
                    </div>

                    <button type="submit" class="btn confirm-btn me-2">Submit</button>
                    <a href="{{ route('accounts.index') }}" class="btn cancel-btn">Back</a>
                </form>
            </div>
        </div>
    </div>
@endsection
