@extends('layouts.admin')

@section('title', 'Venues')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">All Venues</h4>
    <a href="{{ route('admin_venues_create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Add Venue
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form method="GET" class="row g-3 mb-4">
            <div class="col-md-6">
                <input type="text" name="search" class="form-control" placeholder="Search venues..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-outline-secondary w-100">Search</button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>City</th>
                        <th>Country</th>
                        <th>Capacity</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($venues as $venue)
                    <tr>
                        <td>{{ $venue->name }}</td>
                        <td>{{ $venue->city }}</td>
                        <td>{{ $venue->country }}</td>
                        <td>{{ number_format($venue->capacity) }}</td>
                        <td>
                            <span class="badge bg-{{ $venue->is_active ? 'success' : 'danger' }}">
                                {{ $venue->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('admin_venues_edit', $venue->id) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">No venues found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{ $venues->links() }}
    </div>
</div>
@endsection