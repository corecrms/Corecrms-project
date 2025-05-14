@extends('back.layout.app')
@section('content')
    <!--start page wrapper -->

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
                <div class="row">
                    <div class="col-12">
                        <div class="COnftainer_boxes">
                            <h1>Edit Subscription Package</h1>
                            <form method="POST"
                                action="{{ route('subscription-packages.update', $subscriptionPackage->id) }}" class="row">
                                @method('PATCH')
                                @csrf
                                <div class="card my-3">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label class="form-label" for="name">Name <span>*</span></label>
                                            <input type="text" value="{{ $subscriptionPackage->name }}" name="name"
                                                class="form-control" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label" for="price">Price <span>*</span></label>
                                            <input type="number" value="{{ $subscriptionPackage->price }}" name="price"
                                                class="form-control" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label" for="duration">Duration (in months)
                                                <span>*</span></label>
                                            <input type="number" value="{{ $subscriptionPackage->duration }}"
                                                name="duration" class="form-control" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label" for="stripe_product">Stripe Product ID
                                                <span>*</span></label>
                                            <input type="text" value="{{ $subscriptionPackage->stripe_product }}"
                                                name="stripe_product" class="form-control" required>
                                        </div>
                                        <div class="col-md-12">
                                            <label class="form-label" for="description">Description</label>
                                            <textarea name="description" class="form-control">{{ $subscriptionPackage->description }}</textarea>
                                        </div>
                                    </div>
                                </div>


                                <div class="card my-3">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for=""></label>
                                            <div class="col-xs-12 col-sm-12 col-md-12">
                                                <div class="form-group">
                                                    <strong>Subscription Services:</strong>
                                                    <br />
                                                    @foreach ($subscriptionServices as $service)
                                                        <label>
                                                            <input type="checkbox" name="subscriptionServices[]"
                                                                value="{{ $service->id }}"
                                                                {{ $subscriptionPackage->subscriptionServices->contains($service->id) ? 'checked' : '' }} />
                                                            {{ $service->name }}
                                                        </label>
                                                        <br />
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="text-end my-3">
                                    <button type="submit" class="btn theme_btn_blue"><i
                                            class="fas fa-paper-plane me-1"></i>Update</button>
                                    <a href="{{ route('subscription-packages.index') }}" class="btn btn-danger"><i
                                            class="fas fa-times me-1"></i>Cancel</a>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
    <!--end page wrapper -->
@endsection
