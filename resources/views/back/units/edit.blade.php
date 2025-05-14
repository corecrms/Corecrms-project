@extends('back.layout.app')
@section('title', 'Add Unit')
@section('content')
    <div class="content">


        <div class="container-fluid pt-4 px-4 mb-5">
            <div class="border-bottom">
                <h3 class="all-adjustment text-center pb-2 mb-0">Edit Units</h3>
            </div>

            @include('back.layout.errors')

            <div class="card card-shadow border-0 mt-5 rounded-3">
                <div class="card-header bg-white border-0 rounded-3 p-4">
                    <div class="">
                        <div class="create-product-form rounded">
                            <form action="{{route('units.update',$unit->id)}}" method="post" enctype="multipart/form-data" class="mt-4">
                                @csrf
                                @method('PUT')
                                <div class="form-group mt-1">
                                    <label for="name">Unit Name</label>
                                    <input type="text" name="name" id="name" class="form-control mt-2" value="{{$unit->name}}">
                                </div>
                                <div class="form-group mt-1">
                                    <label for="short_name">Short Name</label>
                                    <input type="text" name="short_name" id="short_name" class="form-control mt-2" value="{{$unit->short_name}}">
                                </div>
                                <div class="form-group mt-2">
                                    <label for="base_unit" class="form-label">Base Unit</label>
                                    <select name="base_unit" id="base_unit" class="form-select mt-2">
                                        <option value="">-Select Unit-</option>
                                        @foreach ($parentUnit as $punit )
                                            <option value="{{$punit->id}}" {{$punit->id == $unit->parent_id ? 'selected': ''}}>{{$punit->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div id="hiddenFeilds" >
                                    <div class="form-group mt-1">
                                        <label for="operator">Operator</label>
                                        <select name="operator" id="operator" class="form-select">
                                            <option value="*" {{$unit->operator == "*" ? 'selected' : ''}}>Multiply (*)</option>
                                            <option value="/" {{$unit->operator == "/" ? 'selected' : ''}}>Divide (/)</option>
                                        </select>
                                    </div>
                                    <div class="form-group mt-1">
                                        <label for="operator_value">Operator Value</label>
                                        <input type="text" name="operator_value" id="operator_value" class="form-control mt-2" value="{{$unit->operator_value}}">
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary mt-2">Update</button>
                            </form>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>

@endsection
