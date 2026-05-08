@extends('layouts.admin')

@section('title', 'Organizer Revenue')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1">{{ $organizerDetail->company_name }} - Revenue</h4>
        <p class="text-muted mb-0">{{ $organizerDetail->user->name }} | {{ $organizerDetail->user->email }}</p>
    </div>
    <div>
        <a href="{{ route('admin_organizers_events', $organizerDetail->id) }}" class="btn btn-outline-secondary me-2">
            <i class="bi bi-calendar-event me-1"></i> View Events
        </a>
        <a href="{{ route('admin_organizers_list') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Back
        </a>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card bg-success text-white h-100">
            <div class="card-body text-center">
                <h3>${{ number_format($totalRevenue, 2) }}</h3>
                <small>Total Revenue</small>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-info text-white h-100">
            <div class="card-body text-center">
                <h3>{{ $organizerDetail->status }}</h3>
                <small>Status</small>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-primary text-white h-100">
            <div class="card-body text-center">
                <h3>{{ $organizerDetail->created_at->format('M d, Y') }}</h3>
                <small>Member Since</small>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Revenue Summary</h5>
    </div>
    <div class="card-body">
        <p class="text-muted">Total revenue from all completed orders for this organizer's events.</p>
    </div>
</div>
@endsection