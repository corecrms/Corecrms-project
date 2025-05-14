@extends('back.layout.app')
@section('content')
    <!--start page wrapper -->

    <div class="content">
        <div class="container-fluid px-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="mt-5 fs-2">Role Edit</h1>
                    <p>Edit role</p>
                </div>

            </div>
            <div class="container-fluid create-product-form rounded">
                <!--breadcrumb-->
                <!--breadcrumb-->
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
                                <h5 class="mb-4">Edit Role</h5>
                                {!! Form::model($role, ['method' => 'PATCH', 'route' => ['roles.update', $role->id], 'class' => 'row', 'g-3']) !!}

                                <div class="col-md-12 mb-3">
                                    <div class="form-group">
                                        <label class="form-label">Name:</label>
                                        {!! Form::text('name', null, ['placeholder' => 'Name', 'class' => 'form-control']) !!}
                                    </div>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <div class="form-group">
                                        <label class="form-label">Permission:</label>
                                        <br />
                                        @foreach ($permission as $value)
                                            <label>{{ Form::checkbox('permission[]', $value->id, in_array($value->id, $rolePermissions) ? true : false, ['class' => 'name']) }}
                                                {{ $value->name }}</label>
                                            <br />
                                        @endforeach
                                    </div>
                                </div>
                                <div class="mt-3 col-md-12 text-end">
                                    <button type="submit" class="btn confirm-btn me-2">Update</button>
                                    <a href="{{ route('roles.index') }}" class="btn cancel-btn">Cancel</a>

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
