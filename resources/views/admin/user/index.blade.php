@extends('layouts.admin-layout')

@section('links')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/custom-datatable.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
@endsection

@section('content')
    @include('admin.user.create')


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


<!-- Edit User Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editUserForm">
                <input type="hidden" id="edit_user_id" name="user_id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_barangay" class="form-label">Barangay</label>
                        <select class="form-select select2" id="edit_barangay" name="barangay" required>
                            <option value="">Select Barangay</option>
                            @foreach ($barangays as $barangay)
                                <option value="{{ $barangay->name }}">{{ $barangay->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_phone_number" class="form-label">Phone Number</label>
                        <input type="text" class="form-control" id="edit_phone_number" name="phone_number" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="edit_username" name="username" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_password" class="form-label">Password (leave blank if unchanged)</label>
                        <input type="password" class="form-control" id="edit_password" name="password">
                    </div>
                    <div class="mb-3">
                        <label for="edit_role" class="form-label">Role</label>
                        <select class="form-select select2" id="edit_role" name="is_admin" required>
                            <option value="0">User</option>
                            <option value="1">Admin</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update User</button>
                </div>
            </form>
        </div>
    </div>
</div>

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

            // Update User Form Submit
            $('#editUserForm').on('submit', function(e) {
                e.preventDefault();
                const userId = $('#edit_user_id').val();
                $.ajax({
                    url: `/admin/users/${userId}`,
                    type: 'PUT',
                    data: $(this).serialize(),
                    success: function(response) {
                        $('#editUserModal').modal('hide');
                        toastr.success('User updated successfully!');
                        location.reload();
                    },
                    error: function(xhr) {
                        toastr.error('Error updating user!');
                    }
                });
            });

            // Delete User Button Click
            $('.delete-user').on('click', function() {
                if (confirm('Are you sure you want to delete this user?')) {
                    const userId = $(this).data('id');
                    $.ajax({
                        url: `/admin/users/${userId}`,
                        type: 'DELETE',
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
