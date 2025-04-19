@extends('layouts.admin-layout')

@section('content')
    <div class="pc-content">
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title d-flex justify-content-between align-items-center">
                            <h5 class="m-b-10">Audit Log Details</h5>
                            <a href="{{ route('admin.audit-logs.index') }}" class="btn btn-primary btn-sm">
                                <i class="ti ti-arrow-left"></i> Back
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <h6 class="card-subtitle mb-2 text-muted">Basic Information</h6>
                                <table class="table table-borderless">
                                    <tr>
                                        <th width="150">User</th>
                                        <td>{{ $auditLog->user->name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Action</th>
                                        <td>
                                            @if ($auditLog->action == 'created')
                                                <span class="badge bg-success">Created</span>
                                            @elseif($auditLog->action == 'updated')
                                                <span class="badge bg-warning">Updated</span>
                                            @else
                                                <span class="badge bg-danger">Deleted</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Module</th>
                                        <td>{{ $auditLog->module }}</td>
                                    </tr>
                                    <tr>
                                        <th>Description</th>
                                        <td>{{ $auditLog->description }}</td>
                                    </tr>
                                    <tr>
                                        <th>IP Address</th>
                                        <td>{{ $auditLog->ip_address }}</td>
                                    </tr>
                                    <tr>
                                        <th>User Agent</th>
                                        <td>{{ $auditLog->user_agent }}</td>
                                    </tr>
                                    <tr>
                                        <th>Date</th>
                                        <td>{{ $auditLog->created_at->format('Y-m-d H:i:s') }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        @if ($auditLog->old_values || $auditLog->new_values)
                            <div class="row">
                                <div class="col-md-12">
                                    <h6 class="card-subtitle mb-2 text-muted">Changes</h6>
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Field</th>
                                                    <th>Old Value</th>
                                                    <th>New Value</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($auditLog->new_values as $field => $value)
                                                    @if ($field !== 'updated_at' && $field !== 'created_at')
                                                        <tr>
                                                            <td>{{ ucfirst(str_replace('_', ' ', $field)) }}</td>
                                                            <td>{{ $auditLog->old_values[$field] ?? '-' }}</td>
                                                            <td>{{ $value ?? '-' }}</td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
