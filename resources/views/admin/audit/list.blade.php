@extends('layouts.admin')

@section('title', 'Audit Log')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Activity Log</h5>
        <a href="{{ route('admin_audit_log_export') }}" class="btn btn-outline-success btn-sm">
            <i class="bi bi-download me-1"></i> Export
        </a>
    </div>
    <div class="card-body">
        <form method="GET" class="row g-3 mb-4">
            <div class="col-md-3">
                <input type="text" name="search" class="form-control" placeholder="Search..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <select name="user_id" class="form-select">
                    <option value="">All Users</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="action" class="form-select">
                    <option value="">All Actions</option>
                    @foreach($actions as $action)
                        <option value="{{ $action }}" {{ request('action') == $action ? 'selected' : '' }}>
                            {{ ucfirst($action) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
            </div>
            <div class="col-md-2">
                <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
            </div>
            <div class="col-md-1">
                <button type="submit" class="btn btn-outline-secondary w-100">Filter</button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover table-sm">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User</th>
                        <th>Action</th>
                        <th>Description</th>
                        <th>IP</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                    <tr>
                        <td>{{ $log->id }}</td>
                        <td>{{ $log->user->name ?? 'System' }}</td>
                        <td>
                            <span class="badge bg-{{ $log->action === 'created' ? 'success' : ($log->action === 'deleted' ? 'danger' : 'info') }}">
                                {{ $log->action }}
                            </span>
                        </td>
                        <td>{{ $log->description ?? '-' }}</td>
                        <td><small>{{ $log->ip_address }}</small></td>
                        <td><small>{{ $log->created_at->format('M d, Y H:i') }}</small></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">No activity logs found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{ $logs->links() }}
    </div>
</div>
@endsection