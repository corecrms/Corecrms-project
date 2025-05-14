@extends('user.layout.app')

@section('title', 'About Us')
@section('style')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.1.3/assets/owl.carousel.min.css" rel="stylesheet"
        type="text/css" />
@endsection

@section('content')
    <!-- Order Status -->
    <section class="">
        <div class="container">
            <h1 class="text-center py-5">Privacy Policy</h1>
        </div>
    </section>
    <!-- End Order Status -->

    <!-- Privacy -->
    <section class="mt-3">
        <div class="container-xxl container-fluid">
            <div class="row align-items-center">
                {!! $setting->privacy_policy ?? '' !!}
            </div>
        </div>
    </section>
    <!-- End Privacy -->


    @include('user.components.brands')
@endsection
