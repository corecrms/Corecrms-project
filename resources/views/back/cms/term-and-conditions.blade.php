@extends('back.layout.app')


@section('style')
    {{-- CKEditor CDN --}}
    <script src="https://cdn.ckeditor.com/ckeditor5/23.0.0/classic/ckeditor.js"></script>

    {{-- <link type="text/css" rel="stylesheet" href="{{ asset('back/assets/css/image-uploader.css') }}"> --}}

    <style>
        .ck-editor__editable_inline {
            min-height: 350px;
        }

    </style>
    
@endsection

@section('content')
    <!--start page wrapper -->
@section('title', 'Add Customer')
<div class="content">
    <div class="container-fluid px-4 mt-3">
        <div class="border-bottom">
            <h3 class="all-adjustment text-center pb-2 mb-0">Term And Conditions</h3>
        </div>

        <div class="shadow p-4 mt-3" style="margin-bottom: 190px">
            <div class="container-fluid create-product-form rounded">
                @include('back.layout.errors')

                <!--end breadcrumb-->
                <form action="{{ route('admin.term-and-condition.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="exampleFormControlSelect1" class="mb-1">Term And Conditions <span
                                        class="text-danger">*</span></label>
                                <textarea class="form-control subheading" id="term-and-conditions" name="terms_and_conditions">{{$setting->terms_and_conditions ?? ''}}</textarea>
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
        ClassicEditor.create(document.querySelector('#term-and-conditions'))
            .catch(error => {
                console.error(error);
            });
    </script>
@endsection
