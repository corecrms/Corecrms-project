@extends('back.layout.app')
@section('style')
    <link href="{{ asset('back/assets/js/datatable/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" />
    <style>
        .dataTables_paginate {
            display: none;
        }

        .dataTables_length {
            display: none;
        }

        .dataTables_info {
            display: none;
        }
    </style>
@endsection
@section('title', 'Create Role')
@section('content')

    <div class="content">

        <div class="container-fluid pt-4 px-4 mb-5">
            <div class="border-bottom">
                <h3 class="all-adjustment text-center pb-2 mb-0 w-25">
                    Create Permissions
                </h3>
            </div>

            @include('back.layout.errors')

            <div class="card card-shadow border-0 mt-3 rounded-3 p-2">
                <div class="card-header bg-white border-0 rounded-3">
                    <div class="row my-4">
                        {!! Form::open(['route' => 'permissions.store', 'method' => 'POST', 'class' => 'row', 'g-3']) !!}
                                <div class=" col-md-12 mb-3">
                                    <div class="form-group">
                                        <label class="form-label">Name:</label>
                                        {!! Form::text('name', null, ['placeholder' => 'Name', 'class' => 'form-control']) !!}
                                    </div>
                                </div>
                                <div class=" col-md-12 mb-3">
                                    <div class="form-group">
                                        <label class="form-label">Description:</label>
                                        <textarea class="form-control" name="description" id="description" cols="30" rows="4"></textarea>
                                    </div>
                                </div>

                                <div class=" mt-3 col-md-12">
                                    <button type="submit" class="btn save-btn text-white">Submit</button>
                                </div>
                        {!! Form::close() !!}
                    </div>
                </div>

            </div>
        </div>
    </div>




@endsection
