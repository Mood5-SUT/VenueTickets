@extends('layouts.admin')

@section('title', 'Events')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">All Events</h4>
    <a href="{{ route('admin_events_create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Create Event
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form method="GET" class="row g-3 mb-4">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Search events..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
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
                        <th>Name</th>
                        <th>Date</th>
                        <th>Venue</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($events as $event)
                    <tr>
                        <td>{{ $event->name }}</td>
                        <td>{{ $event->event_date->format('M d, Y') }}</td>
                        <td>{{ $event->venue->name ?? 'N/A' }}</td>
                        <td>
                            <span class="badge bg-{{ $event->status === 'published' ? 'success' : ($event->status === 'draft' ? 'warning' : 'danger') }}">
                                {{ ucfirst($event->status) }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('admin_events_edit', $event->id) }}" class="btn btn-sm btn-outline-primary" title="Edit Event">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <a href="{{ route('admin_events_pricing_list', $event->id) }}" class="btn btn-sm btn-outline-success" title="Manage Pricing Tiers">
                                <i class="bi bi-tags"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">No events found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{ $events->links() }}
    </div>
</div>
@endsection