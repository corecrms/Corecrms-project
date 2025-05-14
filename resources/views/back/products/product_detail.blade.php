@extends('back.layout.app')
@section('title', 'Product Details')
@section('style')

    </style>
    <link href="{{ asset('back/assets/js/datatable/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" />

@endsection

@section('content')
    <div class="content ">
        @include('back.layout.errors')



        <div class="container-fluid py-5 px-4">
            <div class="border-bottom">
              <h3 class="all-adjustment text-center pb-2 mb-0">Product Detail</h3>
            </div>
            <div class="card rounded-3 border-0 card-shadow mt-5 p-0">
              <div class="card-header p-3 border-0">
                <button class="btn create-btn py-2 px-3" id="printButton"><i class="fa-solid fa-print me-2"></i>Print</button>
              </div>
              <div class="card-body p-3" id="contentToPrint">
                <div class="row">
                  <div class="col-md-9">
                    <div class="table-responsive">
                      <table class="table table-hover table-bordered">
                        <tr>
                          <td>Type</td>
                          <td class="fw-bold">{{$product->product_type ?? ''}}</td>
                        </tr>
                        <tr>
                          <td>Code Product</td>
                          <td class="fw-bold">{{$product->sku ?? ''}}</td>
                        </tr>
                        <tr>
                          <td>Product</td>
                          <td class="fw-bold">{{$product->name ?? ''}}</td>
                        </tr>
                        <tr>
                          <td>Brand</td>
                          <td class="fw-bold">{{$product->brand->name ?? ''}}</td>
                        </tr>
                        <tr>
                          <td>Category</td>
                          <td class="fw-bold">{{$product->category->name ?? ''}}</td>
                        </tr>
                        <tr>
                          <td>Sub Category</td>
                          <td class="fw-bold">{{$product->sub_category->name ?? ''}}</td>
                        </tr>
                        <tr>
                          <td>Unit</td>
                          <td class="fw-bold">{{$product->unit->name ?? 'N/A'}}</td>
                        </tr>
                        <tr>
                          <td>Tax</td>
                          <td class="fw-bold">{{$product->order_tax ?? ''}} %</td>
                        </tr>
                        <tr>
                          <td>Tax</td>
                          <td class="fw-bold">{{$product->tax->name ?? ''}}</td>
                        </tr>
                      </table>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <!-- <img src="dasheets/img/pepsi.svg" class="img-fluid w-100 align-middle" alt=""> -->
                    <div id="carouselExample" class="carousel slide">
                      <div class="carousel-inner">
                        {{-- <div class="carousel-item active">
                          <img src="dasheets/img/pepsi.svg" class="img-fluid w-100 align-middle" alt="">
                        </div> --}}
                        @forelse($product->images as $image)
                            <div class="carousel-item @if($loop->iteration == 1) active @endif">
                                <img src="{{asset('/storage'.(isset($image->img_path) ? $image->img_path : '') )}}" class="img-fluid w-100 align-middle" alt="No Image">
                            </div>
                        @empty
                            <div class="carousel-item active">
                                <img src="{{ asset('back/assets/image/no-image.png') }}" alt="" class="img-fluid w-100 align-middle" />
                            </div>
                        @endforelse

                      </div>
                      <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
                        <!-- <span class="carousel-control-prev-icon" aria-hidden="true"></span> -->
                        <i class="fa-solid fa-chevron-left text-dark fw-bold fs-2"></i>
                        <span class="visually-hidden">Previous</span>
                      </button>
                      <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
                        <!-- <span class="carousel-control-next-icon" aria-hidden="true"></span> -->
                        <i class="fa-solid fa-chevron-right text-dark fw-bold fs-2"></i>
                        <span class="visually-hidden">Next</span>
                      </button>
                    </div>
                    {{-- <div class=" mt-3 bg-white d-flex justify-content-center py-2">
                        {!! DNS1D::getBarcodeHTML($product->sku ?? '78797979', 'C128A') !!}
                    </div> --}}
                  </div>
                </div>

                <div class="row">
                  @if($product->variants->isNotEmpty())
                    <div class="col-md-5">
                        <div class="table-responsive">
                        <table class="table mt-3">
                            <thead class="fw-bold border-top">
                            <tr>
                                <th>Varient Code</th>
                                <th>Varient Name</th>
                                <th>Varient Cost</th>
                                <th>Varient Price</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach($product->variants as $variants)
                                    @foreach($variants->options as $option)
                                        <tr>
                                            <td>{{$option->sub_name ?? ''}}</td>
                                            <td>{{$option->code ?? ''}}</td>
                                            <td>$ {{$option->cost ?? ''}}</td>
                                            <td>$ {{$option->price ?? ''}}</td>
                                        </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                        </div>
                    </div>
                  @endif
                  @if ($product->product_warehouses->isNotEmpty())
                    <div class="col-md-7">
                        <div class="table-responsive">
                        <table class="table mt-3">
                            <thead class="fw-bold border-top">
                            <tr>
                                <th>Warehouse</th>
                                <th>Quantity</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach ($product->product_warehouses as $warehouse )
                                    <tr>
                                        <td>{{$warehouse->warehouse->users->name ?? ''}}</td>
                                        <td>{{$warehouse->quantity ?? ''}} {{$warehouse->product->unit?->short_name ?? ''}}</td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                        </div>
                    </div>
                  @endif
                </div>
              </div>


            </div>
          </div>
    </div>
@endsection


@section('scripts')


<!-- Add this HTML code at the bottom of your page -->
{{-- <script>
    // Function to print the specific section
    function printSpecificSection() {
        // Get the content to print
        const contentToPrint = document.getElementById('contentToPrint').innerHTML;

        // Open a new window and write the content to it
        const printWindow = window.open('', '_blank');
        printWindow.document.write('<html><head><title>Print</title>');
        // printWindow.document.write('link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" >');
        // printWindow.document.write('<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css">');
        printWindow.document.write('<link rel="stylesheet" href="{{ asset('back/assets/css/bootstrap.min.css') }}">');
        printWindow.document.write('<link rel="stylesheet" href="{{ asset('back/assets/css/Printstyle.css') }}">');
        printWindow.document.write('<link rel="stylesheet" href="{{ asset('back/assets/css/style.css') }}">');
        printWindow.document.write('</head><body>');
        printWindow.document.write(contentToPrint);
        printWindow.document.write('</body></html>');
        // Print the content in the new window
        // printWindow.print();
    }

    // Attach a click event listener to the print button
    document.getElementById('printButton').addEventListener('click', printSpecificSection);
</script> --}}

<script>
    // Function to print the specific section
    function printSpecificSection() {
        // Get the content to print
        const contentToPrint = document.getElementById('contentToPrint').innerHTML;

        // Open a new window and write the content to it
        const printWindow = window.open('', '_blank');
        printWindow.document.write('<html><head><title>Print</title>');
        printWindow.document.write('<link rel="stylesheet" href="{{ asset('back/assets/css/bootstrap.min.css') }}">');
        printWindow.document.write('<link rel="stylesheet" href="{{ asset('back/assets/css/Printstyle.css') }}">');
        printWindow.document.write('<link rel="stylesheet" href="{{ asset('back/assets/css/style.css') }}">');
        printWindow.document.write('</head><body>');
        printWindow.document.write(contentToPrint);
        printWindow.document.write('</body></html>');

        // Wait for 2 seconds before triggering the print command
        setTimeout(function() {
            printWindow.print()
        }, 2000);
    }
    // Attach a click event listener to the print button
    document.getElementById('printButton').addEventListener('click', printSpecificSection);
</script>



@endsection
