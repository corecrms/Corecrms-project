@extends('user.dashboard-layout.app')


@section('content')
    <div class="container-xxl container-fluid pt-4 px-4 mb-5">
        <div class="border-bottom">
            <h3 class="all-adjustment text-center pb-2 mb-0 w-25">
                Import Orders
            </h3>
        </div>

        {{-- @include('back.layout.errors') --}}

        <!-- Input Fields -->
        <div class="card card-shadow rounded-3 border-0 mt-4 p-2 pt-0 px-0">

            <div class="card-body">
                <div class="">
                    <span class="fw-bold">
                        If you know the sku codes of the products you would like to order, you can use our helpful Quick Order function by simply creating CSV file with product sku(s), warehouse name
                        and quantities which you want to buy & submit the file.
                    </span>
                </div>

                <div class="text-end">
                    <a href="{{asset('sample-files/quick-orders.xlsx')}}" class="btn rounded-3 mt-2 excel-btn">
                        Download Sample <i class="bi bi-file-earmark-text"></i>
                    </a>
                </div>

            </div>
            <div class="card-footer border-0 bg-transparent">
                <form action="{{ route('import-orders') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="exampleFormControlSelect1" class="mb-1 fw-bold">Import CSV file for Quick Order</label>
                        <input type="file" class="form-control subheading" id="exampleFormControlInput1" name="file" />
                        <span class="max_span text-danger">.xlsx(max 100Mb)</span>

                    </div>
                    <div class="mt-2">
                        <button class="btn text-white save-btn">Submit</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Input Fields End -->
    </div>
@endsection
