@extends('layouts.admin')

@section('title', 'Organizers')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">All Organizers</h5>
    </div>
    <div class="card-body">
        <form method="GET" class="row g-3 mb-4">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Search..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-outline-secondary w-100">Filter</button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Company</th>
                        <th>Contact</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Applied</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($organizers as $organizer)
                    <tr>
                        <td>{{ $organizer->company_name }}</td>
                        <td>{{ $organizer->user->name ?? 'N/A' }}</td>
                        <td>{{ $organizer->user->email ?? 'N/A' }}</td>
                        <td>
                            <span class="badge bg-{{ $organizer->status === 'approved' ? 'success' : ($organizer->status === 'pending' ? 'warning' : 'danger') }}">
                                {{ ucfirst($organizer->status) }}
                            </span>
                        </td>
                        <td>{{ $organizer->created_at->format('M d, Y') }}</td>
                        <td>
                            @if($organizer->status === 'pending')
                                <form method="POST" action="{{ route('admin_organizers_approve', $organizer->id) }}" class="d-inline">
                                    {{ csrf_field() }}
                                    <button type="submit" class="btn btn-sm btn-success">Approve</button>
                                </form>
                            @endif
                            @if($organizer->status === 'approved')
                                <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#suspendModal{{ $organizer->id }}">
                                    Suspend
                                </button>
                            @endif
                            <a href="{{ route('admin_organizers_events', $organizer->id) }}" class="btn btn-sm btn-outline-primary">Events</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">No organizers found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{ $organizers->links() }}
    </div>
</div>

@foreach($organizers as $organizer)
<div class="modal fade" id="suspendModal{{ $organizer->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin_organizers_suspend', $organizer->id) }}">
                {{ csrf_field() }}
                <div class="modal-header">
                    <h5 class="modal-title">Suspend Organizer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <label class="form-label">Reason for suspension</label>
                    <textarea name="reason" class="form-control" rows="3" required></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">Suspend</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach
@endsection