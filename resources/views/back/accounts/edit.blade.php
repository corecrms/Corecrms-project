@extends('back.layout.app')
@section('title', 'Add Category')
@section('content')
    <div class="content">



        <div class="container-fluid pt-4 px-4 mb-5">
            <div class="border-bottom">
                <h3 class="all-adjustment text-center pb-2 mb-0">Edit Accounts</h3>
            </div>

            @include('back.layout.errors')

            <div class="card card-shadow border-0 mt-5 rounded-3">
                <div class="card-header bg-white border-0 rounded-3 p-4">
                    <div class="">
                        <div class="create-product-form rounded">
                            <form action="{{ route('accounts.update', $account->id) }}" method="POST" enctype="multipart/form-data">
                                @method('PUT')
                                @csrf
                                <div class="row mb-3">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="account_name">Account Name</label>
                                            <input type="text" class="form-control" name="name" id="account_name" placeholder=""
                                                value="{{ $account->name }}" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="account_img">Account Logo</label>
                                            <input type="file" class="form-control" name="account_img" id="account_img"
                                                value="{{ $account->account_img }}">
                                        </div>
                                    </div>

                                </div>
                                <div class="form-group mb-4">
                                    <label for="description">Description</label>
                                    <textarea class="form-control" id="description" name="description" rows="3">{{ $account->description }}</textarea>
                                </div>

                                <button type="submit" class="btn save-btn text-white me-2">Submit</button>

                            </form>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
@endsection
