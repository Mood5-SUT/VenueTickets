@extends('layouts.admin')

@section('title', isset($venue) ? 'Edit Venue' : 'Add Venue')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">{{ isset($venue) ? 'Edit Venue' : 'Add New Venue' }}</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin_venues_save', $venue->id ?? null) }}">
            {{ csrf_field() }}
            
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Venue Name *</label>
                    <input type="text" name="name" class="form-control" value="{{ $venue->name ?? old('name') }}" required>
                </div>
                
                <div class="col-md-6">
                    <label class="form-label">Capacity *</label>
                    <input type="number" name="capacity" class="form-control" value="{{ $venue->capacity ?? old('capacity') }}" required>
                </div>
                
                <div class="col-12">
                    <label class="form-label">Address *</label>
                    <textarea name="address" class="form-control" rows="2" required>{{ $venue->address ?? old('address') }}</textarea>
                </div>
                
                <div class="col-md-4">
                    <label class="form-label">City *</label>
                    <input type="text" name="city" class="form-control" value="{{ $venue->city ?? old('city') }}" required>
                </div>
                
                <div class="col-md-4">
                    <label class="form-label">State</label>
                    <input type="text" name="state" class="form-control" value="{{ $venue->state ?? old('state') }}">
                </div>
                
                <div class="col-md-4">
                    <label class="form-label">Country *</label>
                    <input type="text" name="country" class="form-control" value="{{ $venue->country ?? old('country') }}" required>
                </div>
                
                <div class="col-md-4">
                    <label class="form-label">Postal Code</label>
                    <input type="text" name="postal_code" class="form-control" value="{{ $venue->postal_code ?? old('postal_code') }}">
                </div>
                
                <div class="col-md-4">
                    <label class="form-label">Phone</label>
                    <input type="text" name="phone" class="form-control" value="{{ $venue->phone ?? old('phone') }}">
                </div>
                
                <div class="col-md-4">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ $venue->email ?? old('email') }}">
                </div>
                
                <div class="col-md-6">
                    <label class="form-label">Website</label>
                    <input type="url" name="website" class="form-control" value="{{ $venue->website ?? old('website') }}" placeholder="https://">
                </div>
                
                <div class="col-md-6">
                    <label class="form-label">Status</label>
                    <select name="is_active" class="form-select">
                        <option value="1" {{ ($venue->is_active ?? true) ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ ($venue->is_active ?? true) ? '' : 'selected' }}>Inactive</option>
                    </select>
                </div>
                
                <div class="col-12">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="3">{{ $venue->description ?? old('description') }}</textarea>
                </div>
            </div>
            
            <div class="mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg me-1"></i> Save Venue
                </button>
                <a href="{{ route('admin_venues_list') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection