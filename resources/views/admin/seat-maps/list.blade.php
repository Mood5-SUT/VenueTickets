@extends('layouts.admin')

@section('title', 'Seat Maps')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Seat Maps</h4>
    <a href="{{ route('admin_seat_maps_create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Create Seat Map
    </a>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Venue</th>
                        <th>Total Seats</th>
                        <th>Zones</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($seatMaps as $seatMap)
                    <tr>
                        <td>{{ $seatMap->name }}</td>
                        <td>{{ $seatMap->venue->name ?? 'N/A' }}</td>
                        <td>{{ number_format($seatMap->total_seats) }}</td>
                        <td>{{ $seatMap->zones->count() }}</td>
                        <td>
                            <span class="badge bg-{{ $seatMap->is_active ? 'success' : 'danger' }}">
                                {{ $seatMap->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('admin_seat_maps_edit', $seatMap->id) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">No seat maps found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{ $seatMaps->links() }}
    </div>
</div>
@endsection