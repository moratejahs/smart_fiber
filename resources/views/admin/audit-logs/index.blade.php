@extends('layouts.admin-layout')

@section('links')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/custom-datatable.css') }}">
@endsection

@section('content')
    <div class="pc-content">
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h5 class="m-b-10">Audit Trail</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="userTable" class="table" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>User</th>
                                        <th>Action</th>
                                        <th>Module</th>
                                        <th>Description</th>
                                        <th>IP Address</th>
                                        <th>Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($auditLogs as $log)
                                        <tr>
                                            <td>{{ $log->user->name }}</td>
                                            <td>
                                                @if ($log->action == 'created')
                                                    <span class="badge bg-success">Created</span>
                                                @elseif($log->action == 'updated')
                                                    <span class="badge bg-warning">Updated</span>
                                                @else
                                                    <span class="badge bg-danger">Deleted</span>
                                                @endif
                                            </td>
                                            <td>{{ $log->module }}</td>
                                            <td>{{ $log->description }}</td>
                                            <td>{{ $log->ip_address }}</td>
                                            <td>{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                                            <td>
                                                <a href="{{ route('admin.audit-logs.show', $log) }}"
                                                    class="btn btn-sm btn-info">
                                                    <i class="ti ti-eye"></i>
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
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#userTable').DataTable({
                responsive: true,
                language: {
                    search: "",
                    searchPlaceholder: "Search records..."
                },
                dom: '<"d-flex justify-content-between align-items-center mb-4"lf>rtip',
                order: [
                    [5, 'desc']
                ],
                initComplete: function() {
                    $('.dataTables_filter input').addClass('form-control');
                    $('.dataTables_length select').addClass('form-select');
                }
            });
        });
    </script>
@endsection
