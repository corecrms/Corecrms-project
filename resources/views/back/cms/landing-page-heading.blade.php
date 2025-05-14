@extends('back.layout.app')
@section('content')
    <!--start page wrapper -->
@section('title', 'Add Customer')
<div class="content">
    <div class="container-fluid px-4 mt-3">
        <div class="border-bottom">
            <h3 class="all-adjustment text-center pb-2 mb-0">Heading</h3>
        </div>

        <div class="shadow p-4 mt-3" style="margin-bottom: 190px">
            <div class="container-fluid create-product-form rounded">
                @include('back.layout.errors')

                <!--end breadcrumb-->
                <form action="{{ route('landing-page-heading.update',1) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row mt-3">
                        <div class="col-md-6 mt-2">
                            <div class="form-group">
                                <label for="exampleFormControlSelect1" class="mb-1">Top Selling Product <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control subheading" id="exampleFormControlInput1"
                                    placeholder="Heading" required name="top_selling_product"
                                    value="{{ $headings->top_selling_product ?? '' }}" />
                            </div>
                        </div>
                        <div class="col-md-6 mt-2">
                            <div class="form-group">
                                <label for="exampleFormControlSelect1" class="mb-1">Our Recomandation <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control subheading" id="exampleFormControlInput1"
                                    placeholder="Our Recomandation Heading" required name="our_recomandation"
                                    value="{{ $headings->our_recomandation ?? '' }}" />
                            </div>
                        </div>
                        <div class="col-md-6 mt-2">
                            <div class="form-group">
                                <label for="exampleFormControlSelect1" class="mb-1">Feature Category<span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control subheading" id="exampleFormControlInput1"
                                    name="feature_category" value="{{$headings->feature_category ?? ''}}" placeholder="Feature Category"/>
                            </div>
                        </div>
                        <div class="col-md-6 mt-2">
                            <div class="form-group">
                                <label for="exampleFormControlSelect1" class="mb-1">Shop By Brand<span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control subheading" id="exampleFormControlInput1"
                                    name="shop_by_brands" value="{{$headings->shop_by_brands ?? ''}}" placeholder="Shop By Brand"/>
                            </div>
                        </div>
                        <div class="col-md-6 mt-2">
                            <div class="form-group">
                                <label for="exampleFormControlSelect1" class="mb-1">Free Shipping Heading<span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control subheading" id="exampleFormControlInput1"
                                    name="free_shipping_heading" value="{{$headings->free_shipping_heading ?? ''}}" placeholder="Free Shipping Heading"/>
                            </div>
                        </div>
                        <div class="col-md-6 mt-2">
                            <div class="form-group">
                                <label for="exampleFormControlSelect1" class="mb-1">Free Shipping Description<span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control subheading" id="exampleFormControlInput1"
                                    name="free_shipping_desc" value="{{$headings->free_shipping_desc ?? ''}}" placeholder="Free Shipping Description"/>
                            </div>
                        </div>
                        <div class="col-md-6 mt-2">
                            <div class="form-group">
                                <label for="exampleFormControlSelect1" class="mb-1">Money Returns Heading<span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control subheading" id="exampleFormControlInput1"
                                    name="money_returns_heading" value="{{$headings->money_returns_heading ?? ''}}" placeholder="Money Returns Heading"/>
                            </div>
                        </div>
                        <div class="col-md-6 mt-2">
                            <div class="form-group">
                                <label for="exampleFormControlSelect1" class="mb-1">Money Returns Description<span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control subheading" id="exampleFormControlInput1"
                                    name="money_returns_desc" value="{{$headings->money_returns_desc ?? ''}}" placeholder="Money Returns Description"/>
                            </div>
                        </div>
                        <div class="col-md-6 mt-2">
                            <div class="form-group">
                                <label for="exampleFormControlSelect1" class="mb-1">Secure Payment Heading<span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control subheading" id="exampleFormControlInput1"
                                    name="secure_payment_heading" value="{{$headings->secure_payment_heading ?? ''}}" placeholder="Secure Payment Heading"/>
                            </div>
                        </div>
                        <div class="col-md-6 mt-2">
                            <div class="form-group">
                                <label for="exampleFormControlSelect1" class="mb-1">Secure Payment Description<span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control subheading" id="exampleFormControlInput1"
                                    name="secure_payment_desc" value="{{$headings->secure_payment_desc ?? ''}}" placeholder="Secure Payment Description"/>
                            </div>
                        </div>
                        <div class="col-md-6 mt-2">
                            <div class="form-group">
                                <label for="exampleFormControlSelect1" class="mb-1">Support Heading<span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control subheading" id="exampleFormControlInput1"
                                    name="support_heading" value="{{$headings->support_heading ?? ''}}" placeholder="Support Heading"/>
                            </div>
                        </div>
                        <div class="col-md-6 mt-2">
                            <div class="form-group">
                                <label for="exampleFormControlSelect1" class="mb-1">Support Description<span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control subheading" id="exampleFormControlInput1"
                                    name="support_desc" value="{{$headings->support_desc ?? ''}}" placeholder="Support Description"/>
                            </div>
                        </div>


                    </div>




                    <button class="btn save-btn text-white mt-4" type="submit">Submit</button>
                </form>

            </div>
        </div>
    </div>
</div>
<!--end page wrapper -->
@endsection

@section('scripts')
<script>

</script>
@endsection
