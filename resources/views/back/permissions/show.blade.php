@extends('back.layout.app')

@section('content')
    <div class="content">
        <div class="container-fluid px-4">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <div class="page-title-right">

                        </div>
                        <h4 class="page-title">Show Permission</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->
            <div class="col-12">
                @if ($message = Session::get('success'))
                    <div class="alert alert-success">
                        <p>{{ $message }}</p>
                    </div>
                @endif
                <div class="card-box">
                    <h4 class="header-title text-right">
                        <a class="btn btn-success" href="{{ route('permissions.index') }}">Back</a>
                    </h4>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Name:</strong>
                                {{ $permission->name }}
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Guard Name:</strong>
                                {{ $permission->guard_name }}
                            </div>
                        </div>
                    </div>
                </div>

            </div>


        </div>
    </div>
@endsection
