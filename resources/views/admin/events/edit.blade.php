@extends('layouts.admin')

@section('title', isset($event) ? 'Edit Event' : 'Create Event')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">{{ isset($event) ? 'Edit Event' : 'Create New Event' }}</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin_events_save', $event->id ?? null) }}">
            {{ csrf_field() }}
            
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Event Name</label>
                    <input type="text" name="name" class="form-control" value="{{ $event->name ?? old('name') }}" required>
                </div>
                
                <div class="col-md-6">
                    <label class="form-label">Event Type</label>
                    <select name="event_type" class="form-select">
                        <option value="">Select Type</option>
                        <option value="concert" {{ ($event->event_type ?? '') == 'concert' ? 'selected' : '' }}>Concert</option>
                        <option value="sports" {{ ($event->event_type ?? '') == 'sports' ? 'selected' : '' }}>Sports</option>
                        <option value="theater" {{ ($event->event_type ?? '') == 'theater' ? 'selected' : '' }}>Theater</option>
                        <option value="conference" {{ ($event->event_type ?? '') == 'conference' ? 'selected' : '' }}>Conference</option>
                    </select>
                </div>
                
                <div class="col-md-6">
                    <label class="form-label">Organizer</label>
                    <select name="organizer_id" class="form-select" required>
                        <option value="">Select Organizer</option>
                        @foreach($organizers as $organizer)
                            <option value="{{ $organizer->id }}" {{ ($event->organizer_id ?? '') == $organizer->id ? 'selected' : '' }}>
                                {{ $organizer->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="col-md-6">
                    <label class="form-label">Venue</label>
                    <select name="venue_id" class="form-select">
                        <option value="">Select Venue</option>
                        @foreach($venues as $venue)
                            <option value="{{ $venue->id }}" {{ ($event->venue_id ?? '') == $venue->id ? 'selected' : '' }}>
                                {{ $venue->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="col-md-6">
                    <label class="form-label">Event Date</label>
                    <input type="datetime-local" name="event_date" class="form-control" 
                           value="{{ isset($event) ? $event->event_date->format('Y-m-d\TH:i') : old('event_date') }}" required>
                </div>
                
                <div class="col-md-6">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select" required>
                        <option value="draft" {{ ($event->status ?? 'draft') == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="published" {{ ($event->status ?? '') == 'published' ? 'selected' : '' }}>Published</option>
                        <option value="cancelled" {{ ($event->status ?? '') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                
                <div class="col-12">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="4">{{ $event->description ?? old('description') }}</textarea>
                </div>
            </div>
            
            <div class="mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg me-1"></i> Save Event
                </button>
                <a href="{{ route('admin_events_list') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection