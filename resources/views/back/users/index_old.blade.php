@extends('back.layout.app')

@section('content')
    <!--start page wrapper -->
    <div class="content">
        <div class="container-fluid px-4">
            <div class="d-flex justify-content-between align-items-center">

                <div>
                    <h1 class="mt-5 fs-2">User Management</h1>
                    <p>Manage your Users</p>

                </div>
                <div class="d-flex gap-4 flex-wrap align-items-baseline my-2 right-btn">

                    <a href="{{ route('users.create') }}" type="button" class="btn my-btn"><span><svg
                                class="svg-inline--fa fa-plus" aria-hidden="true" focusable="false" data-prefix="fas"
                                data-icon="plus" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"
                                data-fa-i2svg="">
                                <path fill="currentColor"
                                    d="M240 80c0-17.7-14.3-32-32-32s-32 14.3-32 32V224H32c-17.7 0-32 14.3-32 32s14.3 32 32 32H176V432c0 17.7 14.3 32 32 32s32-14.3 32-32V288H384c17.7 0 32-14.3 32-32s-14.3-32-32-32H240V80z">
                                </path>
                            </svg><!-- <i class="fas fa-plus"></i> Font Awesome fontawesome.com --></span>
                        Create New Users</a>
                </div>
            </div>

            <div class="page-content">
                <!--breadcrumb-->
                @if ($message = Session::get('success'))
                    <div class="alert alert-success">
                        <p>{{ $message }}</p>
                    </div>
                @endif
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="example" class="table align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th><strong>#</strong></th>
                                        <th><strong>Name</strong></th>
                                        <th><strong>Email</strong></th>
                                        <th><strong>Roles</strong></th>
                                        <th><strong>Action</strong></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $key => $user)
                                        <tr>
                                            <td><strong>{{ ++$i }}</strong></td>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>
                                                @if (!empty($user->getRoleNames()))
                                                    @foreach ($user->getRoleNames() as $v)
                                                        <span class="">{{ $v }}</span>
                                                    @endforeach
                                                @endif
                                            </td>
                                            {{-- <td>
                                            <div class="dropdown">
                                                <button class="btn btn-link" type="button"
                                                    id="dropdownMenuButton_{{ $user->id }}" data-bs-toggle="dropdown"
                                                    aria-expanded="false">
                                                    <i class='bx bx-dots-horizontal-rounded font-22 text-option'></i>
                                                </button>
                                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton_{{ $user->id }}">
                                                    <li>
                                                        <a class="dropdown-item"
                                                            href="{{ route('users.edit', $user->id) }}">Edit</a>
                                                    </li>
                                                    <li>
                                                        {!! Form::open([
                                                            'method' => 'DELETE',
                                                            'route' => ['users.destroy', $user->id],
                                                            'style' => 'display:inline',
                                                        ]) !!}
                                                        {!! Form::submit('Delete', ['class' => 'dropdown-item']) !!}
                                                        {!! Form::close() !!}
                                                    </li>
                                                </ul>
                                            </div>
                                        </td> --}}
                                            <td>
                                                <a class="me-3 text-decoration-none text-secondary"
                                                    href="{{ route('users.edit', $user->id) }}">
                                                    <i class="fa-solid fa-pencil"></i>
                                                </a>
                                                <a href="javascript:void(0);"
                                                    class="text-decoration-none text-danger delete-user-link">
                                                    {!! Form::open([
                                                        'class' => 'delete-user-form text-decoration-none text-danger',
                                                        'method' => 'DELETE',
                                                        'route' => ['users.destroy', $user->id],
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
    </div>

    <!--end page wrapper -->
@endsection
@section('scripts')
    <script>
        $(document).ready(function() {
            $(document).on('click', '.delete-user-link', function(e) {
                e.preventDefault();
                $(this).find('.delete-user-form').submit();
            });
            $(".delete-user-form").submit(function() {
                var decision = confirm("Are you sure, You want to Delete this user?");
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
