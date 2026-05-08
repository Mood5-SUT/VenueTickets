@extends('layouts.admin')

@section('title', isset($seatMap) ? 'Edit Seat Map' : 'Create Seat Map')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">{{ isset($seatMap) ? 'Edit Seat Map' : 'Create New Seat Map' }}</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin_seat_maps_save', $seatMap->id ?? null) }}">
            {{ csrf_field() }}
            
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Seat Map Name *</label>
                    <input type="text" name="name" class="form-control" value="{{ $seatMap->name ?? old('name') }}" required>
                </div>
                
                <div class="col-md-6">
                    <label class="form-label">Venue</label>
                    <select name="venue_id" class="form-select">
                        <option value="">Select Venue</option>
                        @foreach($venues as $venue)
                            <option value="{{ $venue->id }}" {{ ($seatMap->venue_id ?? '') == $venue->id ? 'selected' : '' }}>
                                {{ $venue->name }} ({{ number_format($venue->capacity) }} seats)
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="col-12">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="2">{{ $seatMap->description ?? old('description') }}</textarea>
                </div>
                
                <div class="col-12">
                    <label class="form-label">Total Seats</label>
                    <input type="number" name="total_seats" class="form-control" 
                           value="{{ $seatMap->total_seats ?? old('total_seats') }}" required>
                </div>
            </div>
            
            @if(isset($seatMap) && $seatMap->zones->count() > 0)
            <div class="mt-4">
                <h5>Zones ({{ $seatMap->zones->count() }})</h5>
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Zone</th>
                                <th>Color</th>
                                <th>Price</th>
                                <th>Rows x Cols</th>
                                <th>Capacity</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($seatMap->zones as $zone)
                            <tr>
                                <td>{{ $zone->name }}</td>
                                <td>
                                    <span class="badge" style="background-color: {{ $zone->color }}">{{ $zone->color }}</span>
                                </td>
                                <td>${{ number_format($zone->default_price, 2) }}</td>
                                <td>{{ $zone->rows }} x {{ $zone->columns }}</td>
                                <td>{{ $zone->capacity }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif
            
            <div class="mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg me-1"></i> Save Seat Map
                </button>
                <a href="{{ route('admin_seat_maps_list') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection