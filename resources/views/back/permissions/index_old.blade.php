@extends('back.layout.app')

@section('content')
    <!--start page wrapper -->
    <div class="content">
        <div class="container-fluid px-4">
            <div class="d-flex justify-content-between align-items-center">

                <div>
                    <h1 class="mt-5 fs-2">Permission Management</h1>
                    <p>Manage your Permissions</p>

                </div>
                <div class="d-flex gap-4 flex-wrap align-items-baseline my-2 right-btn">

                    <a href="{{ route('permissions.create') }}" type="button" class="btn my-btn"><span><svg
                                class="svg-inline--fa fa-plus" aria-hidden="true" focusable="false" data-prefix="fas"
                                data-icon="plus" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"
                                data-fa-i2svg="">
                                <path fill="currentColor"
                                    d="M240 80c0-17.7-14.3-32-32-32s-32 14.3-32 32V224H32c-17.7 0-32 14.3-32 32s14.3 32 32 32H176V432c0 17.7 14.3 32 32 32s32-14.3 32-32V288H384c17.7 0 32-14.3 32-32s-14.3-32-32-32H240V80z">
                                </path>
                            </svg><!-- <i class="fas fa-plus"></i> Font Awesome fontawesome.com --></span>
                        Create New Permission</a>
                </div>
            </div>

            <div class="card">
                @if ($message = Session::get('success'))
                    <div class="alert alert-success">
                        <p>{{ $message }}</p>
                    </div>
                @endif
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="example" class="table product-table datanew dataTable no-footer">
                            <thead>
                                <tr role="row">
                                    <th><strong>#</strong></th>
                                    <th><strong>Name</strong></th>
                                    <th><strong>Action</strong></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($permissions as $key => $permission)
                                    <tr class="even">
                                        <td><strong>{{ $loop->iteration }}</strong></td>
                                        <td>{{ $permission->name }}</td>
                                        <td>
                                            <a class="me-3 text-decoration-none text-secondary"
                                                href="{{ route('permissions.edit', $permission->id) }}">
                                                <i class="fa-solid fa-pencil"></i>
                                            </a>
                                            <a href="javascript:void(0);"
                                                class="text-decoration-none text-danger delete-permission-link">
                                                {!! Form::open([
                                                    'class' => 'delete-permission-form text-decoration-none text-danger',
                                                    'method' => 'DELETE',
                                                    'route' => ['permissions.destroy', $permission->id],
                                                    'style' => 'display:inline',
                                                ]) !!}
                                                <i class="fa-solid fa-trash"></i>
                                                {!! Form::submit('', ['class' => 'dropdown-item']) !!}
                                                {!! Form::close() !!}
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--end page wrapper -->
@endsection
@section('scripts')
    <script>
        $(document).ready(function() {
            $(document).on('click', '.delete-permission-link', function(e) {
                e.preventDefault();
                $(this).find('.delete-permission-form').submit();
            });
            $(".delete-permission-form").submit(function() {
                var decision = confirm("Are you sure, You want to Delete this permission?");
                if (decision) {
                    return true;
                }
                return false;
            });
            var table = $('#example').DataTable({
                "processing": true,
                "serverSide": false,
                "search": true,
                // Additional DataTable options can go here
            });
        });
    </script>
@endsection
