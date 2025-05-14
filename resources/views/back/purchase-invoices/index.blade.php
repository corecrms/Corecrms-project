@extends('back.layout.app')
@section('title', 'Products')
@section('style')
    <link href="{{ asset('back/assets/js/datatable/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" />
@endsection

@section('content')
    <div class="content">
        <div class="container-fluid px-4">
            @include('back.layout.errors')

            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <div>
                    <h1 class="mt-5 fs-3">Sales invoices list</h1>
                    <p>View/Search invoice</p>
                </div>
                <div class="d-flex gap-4 flex-wrap align-items-baseline my-2 right-btn">
                    <div class="dropdown">
                        <button class="btn btn-white bg-transparent border-0 dropdown-toggle btn-filter" type="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="me-2"><img src="./dashassets/images/filter-lines.svg" alt=""></span>
                            Filters
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#">
                                    <div class="form-check  d-flex justify-content-between p-0">
                                        <label class="form-check-label" for="flexCheckDefault">
                                            All
                                        </label>
                                        <input class="form-check-input" type="checkbox" value=""
                                            id="flexCheckDefault">
                                    </div>

                                </a></li>
                            <li><a class="dropdown-item" href="#">
                                    <div class="form-check d-flex justify-content-between p-0">
                                        <label class="form-check-label" for="flexCheckChecked">
                                            Product
                                        </label>
                                        <input class="form-check-input" type="checkbox" value="" id="flexCheckChecked"
                                            checked>

                                    </div>
                                </a></li>
                            <li><a class="dropdown-item" href="#">
                                    <div class="form-check d-flex justify-content-between p-0">
                                        <label class="form-check-label" for="flexCheckChecked">
                                            Product Code
                                        </label>
                                        <input class="form-check-input" type="checkbox" value="" id="flexCheckChecked"
                                            checked>

                                    </div>
                                </a></li>

                            <li class="float-end me-3 my-3">
                                <a href="" class="text-decoration-none rounded text-white  my-2"
                                    style="background-color:
                    rgba(127, 99, 244, 1);padding: 7px 10px;">Apply</a>
                            </li>
                        </ul>

                    </div>
                    <a href="{{ route('purchase-invoices.create') }}" type="button" class="btn my-btn"><span><i
                                class="fas fa-plus"></i></span>
                        Add Invoice</a>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table product-table datanew dataTable no-footer" id="example" role="grid"
                            aria-describedby="DataTables_Table_0_info">
                            <thead>
                                <tr>
                                    <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1"
                                        colspan="1" aria-label="Select: activate to sort column ascending"
                                        style="width: 20.3438px;">Select</th>
                                    <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1"
                                        colspan="1" aria-label="Product Name: activate to sort column ascending"
                                        style="width: 94.3438px;">Invoice</th>

                                    <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1"
                                        colspan="1" aria-label="Unit Cost: activate to sort column ascending"
                                        style="width: 94.3438px;">Vendor</th>
                                    <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1"
                                        colspan="1" aria-label="Price Ex Tax: activate to sort column ascending"
                                        style="width: 94.3438px;">Order Date</th>
                                    <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1"
                                        colspan="1" aria-label="Unit: activate to sort column ascending"
                                        style="width: 94.3438px;">Exp. Delivery Date</th>
                                    <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1"
                                        colspan="1" aria-label="Product Type: activate to sort column ascending"
                                        style="width: 94.3438px;">Total</th>
                                    {{-- <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1"
                                    colspan="1" aria-label="Category: activate to sort column ascending"
                                    style="width: 94.3438px;">Status</th> --}}
                                    <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1"
                                        colspan="1" aria-label="Action: activate to sort column ascending"
                                        style="width: 94.3438px;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($purchaseInvoices as $invoice)
                                    <tr>
                                        <td class="sorting_1">
                                            <label class="checkboxs">
                                                <input type="checkbox">
                                                <span class="checkmarks"></span>
                                            </label>
                                        </td>
                                        <td class="productimgname">
                                            <a href="{{ route('purchase-invoices.show', $invoice->id) }}">
                                                <strong>{{ $invoice->invoice_number }}</strong>
                                            </a>
                                        </td>
                                        {{-- <td>{{ $invoice->customer->user->name }}</td> --}}
                                        <td>{{ $invoice->vendor->user->name }}</td>
                                        <td>{{ $invoice->order_date }}</td>
                                        <td>{{ $invoice->delivery_date }}</td>
                                        <td>{{ $invoice->total }}</td>
                                        {{-- <td>{{ $invoice->status }}</td> --}}

                                        <td>
                                            {{-- <a class="me-3 text-decoration-none text-dark "
                                            href="{{ route('products.show', $product->id) }}">
                                            <i class="fa-solid fa-eye"></i>
                                        </a> --}}
                                            <a class="me-3 text-decoration-none text-secondary"
                                                href="{{ route('purchase-invoices.edit', $invoice->id) }}">
                                                <i class="fa-solid fa-pencil"></i>
                                            </a>
                                            <form action="{{ route('purchase-invoices.destroy', $invoice->id) }}"
                                                method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">No purchase invoice Found</td>
                                    </tr>
                                @endforelse

                            </tbody>
                        </table>

                    </div>
                    {{-- <div class="d-flex justify-content-between align-items-center">
                    <!-- Show entries dropdown -->
                    <div class="form-group">
                        <label for="entriesPerPage">Show entries:</label>
                        <select class="form-control" id="entriesPerPage">
                            <option>5</option>
                            <option>10</option>
                            <option>20</option>
                        </select>
                    </div>
                    <nav aria-label="Page navigation example ">
                        <ul class="pagination justify-content-end">
                            <li class="page-item">
                                <a class="page-link" href="#" aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                            </li>
                            <li class="page-item"><a class="page-link" href="#">1</a></li>
                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                            <li class="page-item">
                                <a class="page-link" href="#" aria-label="Next">
                                    <span aria-hidden="true">&raquo;</span>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div> --}}
                </div>

            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('back/assets/js/datatable/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('back/assets/js/datatable/js/dataTables.bootstrap5.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            $('#example').DataTable();
            // $('.dataTables_length').addClass('bs-select');
        });

        $(document).ready(function() {
            $(document).on('click', '.delete-product-link', function(e) {
                e.preventDefault();
                $(this).find('.delete-product-form').submit();
            });

            $(".delete-product-form").submit(function() {
                var decision = confirm("Are you sure, You want to Delete this product?");
                if (decision) {
                    return true;
                }
                return false;
            });
        });
    </script>
@endsection
