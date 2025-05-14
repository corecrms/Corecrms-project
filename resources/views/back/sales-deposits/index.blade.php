@extends('back.layout.app')
@section('title', 'Categories')
@section('style')
    <link href="{{ asset('back/assets/js/datatable/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" />
@endsection

@section('content')
    <div class="content">
        <div class="container-fluid px-4">
            @include('back.layout.errors')

            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <div>
                    <h1 class="mt-5 fs-3">Sales Deposits list</h1>
                    <p>Manage Credit Notes </p>
                    {{-- <p>View/Search Sales Payment</p> --}}
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
                                            Sales Deposits
                                        </label>
                                        <input class="form-check-input" type="checkbox" value="" id="flexCheckChecked"
                                            checked>

                                    </div>
                                </a></li>
                            <li><a class="dropdown-item" href="#">
                                    <div class="form-check d-flex justify-content-between p-0">
                                        <label class="form-check-label" for="flexCheckChecked">
                                            Sales Deposits
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
                    <a href="{{ route('sales-deposits.create') }}" type="button" class="btn my-btn"><span><i
                                class="fas fa-plus"></i></span>
                        Add Sales Deposit</a>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="example" class="table align-middle mb-0">

                            <thead>
                                <tr>
                                    <th class="sorting_desc">
                                        <label class="checkboxs">
                                            <input type="checkbox" id="select-all">
                                            <span class="checkmarks"></span>
                                        </label>
                                    </th>
                                    <th class="sorting">Invoice # </th>
                                    <th class="sorting">Customer </th>
                                    <th class="sorting">Amount</th>
                                    <th class="sorting">Payment Method</th>
                                    <th class="sorting">Description</th>
                                    <th class="sorting">Created On</th>
                                    <th class="sorting">Created By</th>
                                    <th class="sorting">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($salesInvoiceCreditNotes as $item)
                                    <tr>
                                        <td class="sorting_1">
                                            <label class="checkboxs">
                                                <input type="checkbox">
                                                <span class="checkmarks"></span>
                                            </label>
                                        </td>
                                        <td class="productimgname">
                                            {{ $item->salesInvoice->invoice_number }}
                                        </td>
                                        <td>{{ $item->salesInvoice->customer->user->name }}</td>
                                        <td>{{ $item->amount }}</td>
                                        <td>{{ $item->payment_method }}</td>
                                        <td> {{ Str::limit($item->description, 15) }}</td>
                                        <td>{{ $item->created_at }}</td>
                                        <td>{{ $item->createdBy->name }}</td>

                                        <td>
                                            <form class="d-inline delete-payment-form" method="post"
                                                action="{{ route('sales-payments.destroy', $item) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn text-danger btn-outline-light">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">No Deposites Found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
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
        });

        $(document).ready(function() {
            $(document).on('click', '.delete-payment-link', function(e) {
                e.preventDefault();
                $(this).find('.delete-payment-form').submit();
            });

            $(".delete-payment-form").submit(function() {
                var decision = confirm("Are you sure, You want to Delete this sales payment?");
                if (decision) {
                    return true;
                }
                return false;
            });
        });
    </script>
@endsection
