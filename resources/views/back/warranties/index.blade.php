@extends('back.layout.app')
@section('title', 'Warranties')
@section('style')
    <link href="{{ asset('back/assets/js/datatable/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" />
@endsection


@section('content')
    <div class="content">
        <div class="container-fluid px-4">
            @include('back.layout.errors')

            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <div>
                    <h1 class="mt-5 fs-3">Product Warranty list</h1>
                    <p>View/Search product Warranty</p>
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
                                            Warranty
                                        </label>
                                        <input class="form-check-input" type="checkbox" value="" id="flexCheckChecked"
                                            checked>

                                    </div>
                                </a></li>
                            <li><a class="dropdown-item" href="#">
                                    <div class="form-check d-flex justify-content-between p-0">
                                        <label class="form-check-label" for="flexCheckChecked">
                                            Warranty Code
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
                    <a href="{{ route('warranties.create') }}" type="button" class="btn my-btn"><span><i
                                class="fas fa-plus"></i></span>
                        Add Warranty</a>
                </div>
            </div>
            <div class="card">
                <div class="card-body">

                    <div class="table-responsive">
                        <table class="table product-table datanew dataTable no-footer" id="example" role="grid"
                            aria-describedby="DataTables_Table_0_info">
                            <thead>
                                <tr role="row">
                                    <th class="sorting_desc" tabindex="0" aria-controls="DataTables_Table_0"
                                        rowspan="1" colspan="1" aria-label=": activate to sort column ascending"
                                        style="width: 41.6719px;" aria-sort="descending">
                                        <label class="checkboxs">
                                            <input type="checkbox" id="select-all">
                                            <span class="checkmarks"></span>
                                        </label>
                                    </th>
                                    <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1"
                                        colspan="1" aria-label="Product Name: activate to sort column ascending"
                                        style="width: 138.25px;">Name</th>

                                    <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1"
                                        colspan="1" aria-label="Warranty : activate to sort column ascending"
                                        style="width: 67.2031px;">Type </th>
                                    <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1"
                                        colspan="1" aria-label="Warranty : activate to sort column ascending"
                                        style="width: 67.2031px;">Period </th>
                                    <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1"
                                        colspan="1" aria-label="Action: activate to sort column ascending"
                                        style="width: 94.3438px;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($warranties as $warranty)
                                    <tr class="even">
                                        <td class="sorting_1">
                                            <label class="checkboxs">
                                                <input type="checkbox">
                                                <span class="checkmarks"></span>
                                            </label>
                                        </td>
                                        <td class="productimgname">
                                            {{ $warranty->warranty_name }}
                                        </td>
                                        <td>{{ $warranty->warranty_type }}</td>

                                        <td>{{ $warranty->warranty_period }}</td>

                                        <td>
                                            <a class="text-decoration-none text-secondary btn"
                                                href="{{ route('warranties.edit', $warranty->id) }}">
                                                <i class="fa-solid fa-pencil"></i>
                                            </a>
                                            <form class="d-inline delete-brand-form" method="post"
                                                action="{{ route('warranties.destroy', $warranty->id) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn text-danger btn-outline-light">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty

                                @endforelse
                            </tbody>
                        </table>

                    </div>

                </div>

            </div>
        </div>

        {{-- <div class="container-fluid pt-4 px-4 mb-5">
            <div class="border-bottom">
                <h3 class="all-adjustment text-center pb-2 mb-0">Warranty</h3>
            </div>

            @include('back.layout.errors')

            <div class="card card-shadow border-0 mt-5 rounded-3">
                <div class="card-header bg-white border-0 rounded-3">
                    <div class="row my-3">
                        <div class="col-md-4 col-12">
                            <div class="input-search position-relative">
                                <input type="text" placeholder="Search Table"
                                    class="form-control rounded-3 subheading" />
                                <span class="fa fa-search search-icon text-secondary"></span>
                            </div>
                        </div>

                        <div class="col-md-8 col-12 text-end">
                            <button class="btn create-btn rounded-3 mt-2" data-bs-target="#exampleModalToggle"
                                data-bs-toggle="modal">
                                Create <i class="bi bi-plus-lg"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="table-responsive p-2">
                    <table class="table product-table datanew dataTable no-footer" id="example" role="grid"
                        aria-describedby="DataTables_Table_0_info">
                        <thead>
                            <tr role="row">
                                <th class="sorting_desc" tabindex="0" aria-controls="DataTables_Table_0"
                                    rowspan="1" colspan="1" aria-label=": activate to sort column ascending"
                                    style="width: 41.6719px;" aria-sort="descending">
                                    <label class="checkboxs">
                                        <input type="checkbox" id="select-all">
                                        <span class="checkmarks"></span>
                                    </label>
                                </th>
                                <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1"
                                    colspan="1" aria-label="Product Name: activate to sort column ascending"
                                    style="width: 138.25px;">Name</th>

                                <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1"
                                    colspan="1" aria-label="Warranty : activate to sort column ascending"
                                    style="width: 67.2031px;">Type </th>
                                <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1"
                                    colspan="1" aria-label="Warranty : activate to sort column ascending"
                                    style="width: 67.2031px;">Period </th>
                                <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1"
                                    colspan="1" aria-label="Action: activate to sort column ascending"
                                    style="width: 94.3438px;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($warranties as $warranty)
                                <tr class="even">
                                    <td class="sorting_1">
                                        <label class="checkboxs">
                                            <input type="checkbox">
                                            <span class="checkmarks"></span>
                                        </label>
                                    </td>
                                    <td class="productimgname">
                                        {{ $warranty->warranty_name }}
                                    </td>
                                    <td>{{ $warranty->warranty_type }}</td>

                                    <td>{{ $warranty->warranty_period }}</td>

                                    <td>
                                        <div class="d-flex justify-content-start">
                                            <a class=" text-decoration-none btn edit-category-btn" data-bs-toggle="modal" data-bs-target="#editWarrantyModel{{$warranty->id}}"
                                                data-cate-name="{{$category->name}}"
                                                data-cate-code="{{$category->code}}"
                                                data-cate-desc="{{$category->description}}">
                                                <img src="{{ asset('back/assets/dasheets/img/edit-2.svg') }}"
                                                    class="p-0 me-2 ms-0" alt="" />
                                            </a>

                                            <form class="d-inline delete-category-form" method="post"
                                                action="{{ route('categories.destroy', $warranty->id) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn text-danger btn-outline-light">
                                                    <img src="{{ asset('back/assets/dasheets/img/plus-circle.svg') }}"
                                                        class="p-0" data-bs-target="#exampleModalToggle2"
                                                        data-bs-toggle="modal" alt="" />
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty

                            @endforelse
                        </tbody>
                    </table>

                </div>

            </div>
        </div> --}}
    </div>

     <!-- Create Modal STart -->
     <div class="modal fade" id="exampleModalToggle" aria-hidden="true" aria-labelledby="exampleModalToggleLabel"
     tabindex="-1">
     <div class="modal-dialog modal-dialog-centered">
         <div class="modal-content">
             <div class="modal-header border-0">
                 <h3 class="all-adjustment text-center pb-2 mb-0" style="width: 57%;">
                     Create Warranty
                 </h3>
                 <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
             </div>
             <div class="modal-body">
                 <form action="{{ route('categories.store') }}" method="POST">
                     @csrf
                     <div class="form-group">
                         <label for="exampleFormControlInput1">Warranty Name</label>
                         <input type="text" class="form-control subheading" name="name"
                             id="exampleFormControlInput1" placeholder="Name" required>
                     </div>

                     <div class="form-group mt-2">
                         <label for="exampleFormControlInput1">Warranty Period</label>
                         <input type="text" class="form-control subheading" name="code"
                             id="exampleFormControlInput1" placeholder="" required>
                     </div>

                     <div class="form-group mt-2">
                         <label for="exampleFormControlTextarea1">Description</label>
                         <textarea class="form-control subheading" id="exampleFormControlTextarea1" name="description" rows="3" required></textarea>
                     </div>

                     <button class="btn save-btn text-white mt-4">Done</button>
                 </form>
             </div>
         </div>
     </div>
 </div>
 <!-- Modal End -->
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
            $(document).on('click', '.delete-brand-link', function(e) {
                e.preventDefault();
                $(this).find('.delete-brand-form').submit();
            });

            $(".delete-brand-form").submit(function() {
                var decision = confirm("Are you sure, You want to Delete this brand?");
                if (decision) {
                    return true;
                }
                return false;
            });
        });
    </script>
@endsection
