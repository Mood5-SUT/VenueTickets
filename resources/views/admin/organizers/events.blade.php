@extends('layouts.admin')

@section('title', 'Organizer Events')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1">{{ $organizerDetail->company_name }} - Events</h4>
        <p class="text-muted mb-0">{{ $organizerDetail->user->name }} | {{ $organizerDetail->user->email }}</p>
    </div>
    <a href="{{ route('admin_organizers_list') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Back to Organizers
    </a>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Events ({{ $events->total() }})</h5>
        <a href="{{ route('admin_organizers_revenue', $organizerDetail->id) }}" class="btn btn-sm btn-outline-success">
            <i class="bi bi-graph-up me-1"></i> View Revenue
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Event Name</th>
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
                        <td>{{ $event->event_date->format('M d, Y H:i') }}</td>
                        <td>{{ $event->venue->name ?? 'N/A' }}</td>
                        <td>
                            <span class="badge bg-{{ $event->status === 'published' ? 'success' : ($event->status === 'draft' ? 'warning' : 'danger') }}">
                                {{ ucfirst($event->status) }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('admin_events_edit', $event->id) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                            <a href="{{ route('admin_events_sales', $event->id) }}" class="btn btn-sm btn-outline-success">
                                <i class="bi bi-bar-chart"></i> Sales
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">No events found for this organizer</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{ $events->links() }}
    </div>
</div>
@endsection