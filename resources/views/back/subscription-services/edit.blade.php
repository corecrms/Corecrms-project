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
                        <div class="container">
                            <h1>Edit Service</h1>
                            <form method="POST"
                                action="{{ route('subscription-services.update', $subscriptionService->id) }}" class="row">
                                @method('PATCH')
                                @csrf
                                <div class="card my-3">
                                    <div class="row py-3">
                                        <div class="col-md-6">
                                            <label class="form-label" for="name">Name <span>*</span></label>
                                            <input type="text" value="{{ $subscriptionService->name }}" name="name"
                                                class="form-control" required>
                                        </div>
                                        <div class="col-md-12">
                                            <label class="form-label" for="duration">Description</label>
                                            <textarea name="description" class="form-control">{{ $subscriptionService->description }}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="text-end my-3">
                                    <button type="submit" class="btn btn-primary"><i
                                            class="fas fa-paper-plane me-1"></i>Update</button>
                                    <a href="{{ route('subscription-services.index') }}" class="btn btn-danger"><i
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
