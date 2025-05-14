@extends('back.layout.app')
@section('content')
    <!--start page wrapper -->

    <div class="content">
        <div class="container-fluid px-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="mt-5 fs-2">Users Add</h1>
                    <p>Create new Users</p>
                </div>

            </div>
            <div class="container-fluid create-product-form rounded">

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

                <!--end breadcrumb-->
                <div class="row">
                    <div class="col-xl-12 mx-auto">
                        <div class="card">
                            <div class="card-body p-4">
                                <h5 class="mb-4">Create New User</h5>
                                {!! Form::open(['route' => 'users.store', 'method' => 'POST', 'class' => 'row', 'g-3']) !!}

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Name:</label>
                                    {!! Form::text('name', null, ['placeholder' => 'Name', 'class' => 'form-control']) !!}

                                </div>
                                <div class="col-md-6 mb-3">

                                    <label class="form-label">Email:</label>
                                    {!! Form::text('email', null, ['placeholder' => 'Email', 'class' => 'form-control']) !!}

                                </div>
                                <div class="col-md-6 mb-3">

                                    <label class="form-label">Password:</label>
                                    {!! Form::password('password', ['placeholder' => 'Password', 'class' => 'form-control']) !!}

                                </div>
                                <div class="col-md-6 mb-3">

                                    <label class="form-label">Confirm Password:</label>
                                    {!! Form::password('confirm-password', ['placeholder' => 'Confirm Password', 'class' => 'form-control']) !!}

                                </div>
                                <div class="col-md-12 mb-3">

                                    <label class="form-label">Role:</label>
                                    {!! Form::select('roles[]', $roles, [], ['class' => 'form-control', 'multiple']) !!}

                                </div>
                                <div class="col-xs-12 col-sm-12 mt-5 col-md-12 text-end">
                                    <button type="submit" class="btn confirm-btn">Submit</button>
                                </div>
                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--end page wrapper -->
@endsection
