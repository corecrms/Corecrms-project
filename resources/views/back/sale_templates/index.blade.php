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
                    <h1 class="mt-5 fs-3">Sales Template list</h1>
                    <p>View/Search Sales Template</p>
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
                                            Sales Template
                                        </label>
                                        <input class="form-check-input" type="checkbox" value="" id="flexCheckChecked"
                                            checked>

                                    </div>
                                </a></li>
                            <li><a class="dropdown-item" href="#">
                                    <div class="form-check d-flex justify-content-between p-0">
                                        <label class="form-check-label" for="flexCheckChecked">
                                            Sales Template Code
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
                    <a href="{{ route('sale_templates.create') }}" type="button" class="btn my-btn"><span><i
                                class="fas fa-plus"></i></span>
                        Add Sales Template</a>
                </div>
            </div>
            {{-- <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table align-middle mb-0" id="example" >
                        <thead>
                            <tr role="row">
                                <th class="sorting_desc">
                                    <label class="checkboxs">
                                        <input type="checkbox" id="select-all">
                                        <span class="checkmarks"></span>
                                    </label>
                                </th>
                                <th class="sorting" >Sales Template name</th>

                                <th class="sorting">Sales Template Code </th>
                                <th class="sorting">Description</th>
                                <th class="sorting">Created By</th>
                                <th class="sorting">Status</th>
                                <th class="sorting">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($sale_templates as $template)
                                <tr class="even">
                                    <td class="sorting_1">
                                        <label class="checkboxs">
                                            <input type="checkbox">
                                            <span class="checkmarks"></span>
                                        </label>
                                    </td>
                                    <td class="productimgname">
                                        {{ $template->name }}
                                    </td>
                                    <td>{{ $template->code }}</td>
                                    <td>{{ $template->description }}</td>

                                    <td>{{ $template->created_by }}</td>
                                    <td>
                                        <span class="{{ getStatusClass($template->status) }}">
                                            {{ $template->status }}</span>
                                    </td>
                                    <td>
                                        <a class="me-3 text-decoration-none text-secondary btn"
                                            href="{{ route('sale_templates.edit', $template->id) }}">
                                            <i class="fa-solid fa-pencil"></i>
                                        </a>
                                        <form class="delete-template-form" method="post"
                                            action="{{ route('sale_templates.destroy', $template->id) }}">
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
                                    <td colspan="7" class="text-center">No Categories Found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                </div>

            </div>

        </div> --}}
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
                                    <th class="sorting">Name</th>

                                    <th class="sorting">Type </th>
                                    <th class="sorting">Default Template</th>
                                    <th class="sorting">Created By</th>
                                    <th class="sorting">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $i = 0;
                                @endphp
                                @forelse ($sale_templates as $template)
                                    <tr>
                                        <td class="sorting_1">
                                            <label class="checkboxs">
                                                <input type="checkbox">
                                                <span class="checkmarks"></span>
                                            </label>
                                        </td>
                                        <td class="productimgname">
                                            {{ $template->name }}
                                        </td>
                                        <td>{{ $template->type }}</td>
                                        {{-- <td>{{ $template->default_template }}</td> --}}
                                        <td>
                                            <div class="table_switch">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input status" data-id="{{ $template->id }}"
                                                        type="checkbox" id="flexSwitchCheck{{ $template->id }}"
                                                        {{ $template->default_template == 1 ? 'checked' : '' }}>
                                                    <label class="form-check-label"
                                                        for="flexSwitchCheck{{ $template->id }}"></label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $template->created_by }}</td>

                                        <td>
                                            <a class=" text-decoration-none text-secondary btn"
                                                href="{{ route('sale_templates.edit', $template->id) }}">
                                                <i class="fa-solid fa-pencil"></i>
                                            </a>
                                            <form class="d-inline delete-template-form" method="post"
                                                action="{{ route('sale_templates.destroy', $template->id) }}">
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
                                        <td colspan="7" class="text-center">No Templates Found</td>
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
            $(document).on('click', '.delete-template-link', function(e) {
                e.preventDefault();
                $(this).find('.delete-template-form').submit();
            });

            $(".delete-template-form").submit(function() {
                var decision = confirm("Are you sure, You want to Delete this sales template?");
                if (decision) {
                    return true;
                }
                return false;
            });
        });
        $(document).on('change', '.status', function() {
            console.log('status changed');
            var id = $(this).data('id');
            var checkbox = $(this);
            $.ajax({
                type: "put",
                data: {
                    _token: '{{ csrf_token() }}'
                },
                url: "{{ url('/sale_template/status') }}" + "/" + id,
                success: function(response) {
                    console.log(response);
                    toastr.success(response.message);

                    $('.status').prop('checked', false);

                    if (response.default_template == 1) {
                        checkbox.prop('checked', true);
                    } else {
                        checkbox.prop('checked', false);
                    }
                },
                error: function(err) {
                    console.log(err);
                }
            });
        });
    </script>
@endsection
