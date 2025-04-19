@extends('layouts.admin-layout')

@section('links')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/custom-datatable.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
@endsection

@section('content')
    @include('admin.user.create')
    @include('admin.user.edit')

    <div class="pc-content">
        <!-- [ breadcrumb ] start -->
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h5 class="m-b-10">User Monitoring</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ table section ] start -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-end mb-3">
                            <button type="button" class="btn text-white" style="background-color: #14BC23;"
                                data-bs-toggle="modal" data-bs-target="#createUserModal">
                                <i class="ti ti-plus"></i> Add User
                            </button>
                        </div>
                        <table id="userTable" class="table table-responsive" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Barangay</th>
                                    <th>Phone</th>
                                    <th>Username</th>
                                    <th>Role</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->barangay }}</td>
                                        <td>{{ $user->phone_number }}</td>
                                        <td>{{ $user->username }}</td>
                                        <td>
                                            @if ($user->is_admin)
                                                <span class="badge bg-success">Admin</span>
                                            @else
                                                <span class="badge bg-primary">User</span>
                                            @endif
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-warning edit-user"
                                                data-id="{{ $user->id }}" data-bs-toggle="modal"
                                                data-bs-target="#editUserModal">
                                                <i class="ti ti-edit"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-danger delete-user"
                                                data-id="{{ $user->id }}">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ table section ] end -->
    </div>
@endsection


@section('scripts')
    <!-- jQuery & DataTables JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
        $(document).ready(function() {
            // Initialize DataTable
            const table = $('#userTable').DataTable({
                responsive: true,
                language: {
                    search: "",
                    searchPlaceholder: "Search records..."
                },
                dom: '<"d-flex justify-content-between align-items-center mb-4"lf>rtip',
                initComplete: function() {
                    $('.dataTables_filter input').addClass('form-control');
                    $('.dataTables_length select').addClass('form-select');
                }
            });

            // Initialize Select2
            $('.select2').select2({
                theme: 'classic',
                width: '100%'
            });

            // Toast Configuration
            toastr.options = {
                "closeButton": true,
                "progressBar": true,
                "positionClass": "toast-top-right",
            };


            // Edit User Button Click
            $('.edit-user').on('click', function() {
                const userId = $(this).data('id');
                $.ajax({
                    url: `/admin/users/${userId}/edit`,
                    type: 'GET',
                    success: function(response) {
                        $('#edit_user_id').val(response.id);
                        $('#edit_name').val(response.name);
                        $('#edit_barangay').val(response.barangay);
                        $('#edit_phone_number').val(response.phone_number);
                        $('#edit_username').val(response.username);
                        $('#edit_role').val(response.is_admin).trigger('change');
                    }
                });
            });
            // Delete User Button Click
            $('.delete-user').on('click', function() {
                if (confirm('Are you sure you want to delete this user?')) {
                    const userId = $(this).data('id');
                    // Get CSRF token from meta tag or blade directive
                    const csrfToken = $('meta[name="csrf-token"]').attr('content') || '{{ csrf_token() }}';
                    $.ajax({
                        url: `/admin/users/${userId}/delete`,
                        type: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken
                        },
                        success: function(response) {
                            toastr.success('User deleted successfully!');
                            location.reload();
                        },
                        error: function(xhr) {
                            toastr.error('Error deleting user!');
                        }
                    });
                }
            });
        });
    </script>
@endsection
