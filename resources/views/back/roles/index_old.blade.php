@extends('back.layout.app')

@section('content')
    <!--start page wrapper -->


    <div class="content">
        <div class="container-fluid px-4">
            <div class="d-flex justify-content-between align-items-center">

                <div>
                    <h1 class="mt-5 fs-2">Role Management</h1>
                    <p>Manage your roles</p>

                </div>
                <div class="d-flex gap-4 flex-wrap align-items-baseline my-2 right-btn">

                    <a href="{{ route('roles.create') }}" type="button" class="btn my-btn"><span><svg
                                class="svg-inline--fa fa-plus" aria-hidden="true" focusable="false" data-prefix="fas"
                                data-icon="plus" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"
                                data-fa-i2svg="">
                                <path fill="currentColor"
                                    d="M240 80c0-17.7-14.3-32-32-32s-32 14.3-32 32V224H32c-17.7 0-32 14.3-32 32s14.3 32 32 32H176V432c0 17.7 14.3 32 32 32s32-14.3 32-32V288H384c17.7 0 32-14.3 32-32s-14.3-32-32-32H240V80z">
                                </path>
                            </svg></span>
                        Create New Role</a>
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
                        <table class="table product-table datanew dataTable no-footer" id="DataTables_Table_0"
                            role="grid" aria-describedby="DataTables_Table_0_info">
                            <thead>
                                <tr role="row">
                                    <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1"
                                        colspan="1"><strong>#</strong></th>
                                    <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1"
                                        colspan="1"><strong>Name</strong></th>
                                    <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1"
                                        colspan="1"><strong>Action</strong></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($roles as $key => $role)
                                    <tr class="even">
                                        <td><strong>{{ ++$i }}</strong></td>
                                        <td>{{ $role->name }}</td>
                                        <td>

                                            <a class="me-3 text-decoration-none text-secondary"
                                                href="{{ route('roles.edit', $role->id) }}">
                                                <i class="fa-solid fa-pencil"></i>
                                            </a>
                                            <a class="confirm-text text-danger delete-role" role_id="{{ $role->id }}"
                                                href="javascript:void(0);">
                                                <i class="fa-solid fa-trash"></i>
                                            </a>

                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
            <!-- delete Modal -->

            <div class="modal fade" id="exampleModal-delete" tabindex="-1" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <form class="delete-role-form" action="" method="POST">
                        @csrf
                        @method('DELETE')
                        <div class="modal-content">
                            <div class="modal-header border-0 pb-0 justify-content-center">
                                <h5 class="modal-title" id="exampleModalLabel">
                                    <div class="page-content-header bg-transparent">
                                        <h6 class="fs-2 text-center fw-bold">Are you sure?</h6>
                                    </div>
                                </h5>
                            </div>
                            <input type="hidden" name="role_id" id="role_id_input" value="">

                            <div class="modal-body">
                                <p class="fs-4 text-center fw-bold">You won't be able to revert this!</p>
                            </div>
                            <div class="modal-footer justify-content-center">
                                <button type="button" class="btn cancel-btn" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn confirm-btn">Yes, delete it!</button>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!--end page wrapper -->
@endsection
@section('scripts')
    <script>
        $(document).ready(function() {
            $(document).on('click', '.delete-role', function() {
                let roleId = $(this).attr('role_id');
                $("#role_id_input").val(roleId);

                let deleteRoute = "{{ url('roles') }}/" + roleId;
                $(".delete-role-form").attr('action', deleteRoute);

                $("#exampleModal-delete").modal('show');
            });
        });
    </script>
@endsection
