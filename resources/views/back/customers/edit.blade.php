@extends('back.layout.app')
@section('content')
    <!--start page wrapper -->
@section('title', 'Update Customer')

<div class="content">
    <div class="container-fluid px-4 mt-3">
        <div class="border-bottom">
            <h3 class="all-adjustment text-center pb-2 mb-0">Edit Customer</h3>
        </div>

        <div class="shadow p-2 pt-0">
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

                        <div class=" p-4">
                            <h5 class="mb-4">Edit Customer</h5>
                            {!! Form::model($user, [
                                'method' => 'PUT',
                                'route' => ['customers.update', $user->id],
                                'class' => 'row',
                                'g-3',
                            ]) !!}


                            <div class="col-md-6 mb-3">
                                <label class="form-label">Name:</label>
                                {!! Form::text('name', null, ['placeholder' => 'Name', 'class' => 'form-control']) !!}

                            </div>
                            <div class="col-md-6 mb-3">

                                <label class="form-label">Email:</label>
                                {!! Form::text('email', null, ['placeholder' => 'Email', 'class' => 'form-control']) !!}

                            </div>
                            <div class="col-md-6 mb-3">

                                <label class="form-label">Tier</label>
                                <select name="tier_id" id="tier" class="form-select">
                                    <option value="">Select Tier</option>
                                    @foreach ($tiers as $tier)
                                        <option value="{{ $tier->id }}"
                                            {{ $tier->id == $customerResource->tiers->id ? 'selected' : '' }}>
                                            {{ $tier->name }}</option>
                                    @endforeach
                                </select>

                            </div>
                            <div class="col-md-6 mb-3">

                                <label class="form-label">Password:</label>
                                {!! Form::password('password', ['placeholder' => 'Password', 'class' => 'form-control']) !!}

                            </div>
                            <div class="col-md-6 mb-3">

                                <label class="form-label">Confirm Password:</label>
                                {!! Form::password('confirm-password', ['placeholder' => 'Confirm Password', 'class' => 'form-control']) !!}

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
