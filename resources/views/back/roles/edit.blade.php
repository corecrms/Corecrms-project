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
                    Edit Roles
                </h3>
            </div>

            @include('back.layout.errors')

            <div class="card card-shadow border-0 mt-3 rounded-3 p-2">
                <div class="card-header bg-white border-0 rounded-3">
                    <div class="row my-4">
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
                                
                                <div class="row">
                                    @foreach ($permission as $value)
                                        <div class="col-md-3">
                                            <label for="checkbox_{{ $value->id }}" class="checkbox" style="cursor: pointer;">
                                                <input class="checkbox__input" name="permission[]" value="{{ $value->id }}" type="checkbox" id="checkbox_{{ $value->id }}" {{ in_array($value->id, $rolePermissions) ? 'checked' : '' }} />
                                                <svg class="checkbox__icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 22 22">
                                                    <rect width="21" height="21" x=".5" y=".5" fill="#FFF" stroke="rgba(76, 73, 227, 1)" rx="3" />
                                                    <path class="tick" stroke="rgba(76, 73, 227, 1)" fill="none" stroke-linecap="round" stroke-width="3" d="M4 10l5 5 9-9" />
                                                </svg>
                                                {{ $value->name }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>

                            </div>
                        </div>
                        <div class="mt-3 col-md-12 ">
                            <button type="submit" class="btn save-btn text-white me-2">Update</button>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>

            </div>
        </div>
    </div>




@endsection
