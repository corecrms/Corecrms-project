@extends('back.layout.app')
@section('content')
    <div class="page-wrapper">
        <div class="content">
            <div class="container-fluid px-4">
                @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <strong>Whoops!</strong> There were some problems with your input.<br><br>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (session()->has('error'))
                    <div class="alert alert-danger">
                        {{ session()->get('error') }}
                    </div>
                @endif

                @if (session()->has('success'))
                    <div class="alert alert-success">
                        {{ session()->get('success') }}
                    </div>
                @endif

                <div class="row">
                    <div class="col-12">

                        <div class="row">
                            <form action="{{ route('profile.update') }}" method="post" autocomplete="off"
                                enctype="multipart/form-data">
                                @csrf
                                @method('put')
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h2 class="text-black main-title">USER SETTINGS</h2>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-6">
                                                    <div class="form-group">
                                                        <label class="font-w600">Email <span
                                                                class="text-danger">*</span></label>
                                                        <input class="form-control" id="email" name="email"
                                                            placeholder="" value="{{ $user->email }}" type="eamil"
                                                            readonly>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="form-group">
                                                        <label class="font-w600">Name <span
                                                                class="text-danger">*</span></label>
                                                        <input class="form-control" id="name" name="name"
                                                            placeholder="" value="{{ $user->name }}" type="text"
                                                            required>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="form-group">
                                                        <label class="font-w600">Image <span
                                                                class="text-danger"></span></label>
                                                        <input class="form-control" id="image" name="image"
                                                            placeholder="" value="{{ $user->image }}" type="file">
                                                    </div>
                                                </div>
                                            </div>

                                            <button type="submit" class="btn btn-outline-primary">Save
                                                Changes</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <form action="{{ route('profile.password.update') }}" method="post" autocomplete="off">
                                @csrf
                                @method('put')
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h2 class="text-black main-title">UPDATE PASSWORD</h2>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-6">
                                                    <div class="form-group">
                                                        <label class="font-w600">Current Password <span
                                                                class="text-danger">*</span></label>
                                                        <input class="form-control" id="current_password"
                                                            name="current_password" placeholder="Enter Current Password"
                                                            type="password" required>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="form-group">
                                                        <label class="font-w600">Password <span
                                                                class="text-danger">*</span></label>
                                                        <input class="form-control" id="password" name="password"
                                                            placeholder="Enter Password" type="password" required>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="form-group">
                                                        <label class="font-w600">Confirm Password <span
                                                                class="text-danger">*</span></label>
                                                        <input class="form-control" id="password_confirmation"
                                                            name="password_confirmation" placeholder="Enter Password"
                                                            type="password" required>
                                                    </div>
                                                </div>
                                            </div>

                                            <button type="submit" class="btn  btn-outline-primary">Update
                                                Password</button>
                                        </div>
                                    </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
