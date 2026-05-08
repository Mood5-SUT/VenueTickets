@extends('layouts.admin')

@section('title', 'Tickets')

@section('content')
<div class="card">
    <div class="card-body">
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body text-center">
                        <h3>{{ $stats['active'] ?? 0 }}</h3>
                        <small>Active Tickets</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body text-center">
                        <h3>{{ $stats['used'] ?? 0 }}</h3>
                        <small>Used</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-danger text-white">
                    <div class="card-body text-center">
                        <h3>{{ $stats['voided'] ?? 0 }}</h3>
                        <small>Voided</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body text-center">
                        <h3>{{ $stats['total'] ?? 0 }}</h3>
                        <small>Total</small>
                    </div>
                </div>
            </div>
        </div>

        <form method="GET" class="row g-3 mb-4">
            <div class="col-md-3">
                <input type="text" name="search" class="form-control" placeholder="Ticket number..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="used" {{ request('status') == 'used' ? 'selected' : '' }}>Used</option>
                    <option value="voided" {{ request('status') == 'voided' ? 'selected' : '' }}>Voided</option>
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
                        <th>Ticket #</th>
                        <th>Event</th>
                        <th>Buyer</th>
                        <th>Seat</th>
                        <th>Price</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tickets as $ticket)
                    <tr>
                        <td>{{ $ticket->ticket_number }}</td>
                        <td>{{ $ticket->event->name ?? 'N/A' }}</td>
                        <td>{{ $ticket->user->name ?? 'N/A' }}</td>
                        <td>{{ $ticket->seat_number ?? 'N/A' }}</td>
                        <td>${{ number_format($ticket->price, 2) }}</td>
                        <td>
                            <span class="badge bg-{{ $ticket->status === 'active' ? 'success' : ($ticket->status === 'used' ? 'primary' : 'danger') }}">
                                {{ ucfirst($ticket->status) }}
                            </span>
                        </td>
                        <td>{{ $ticket->created_at->format('M d, Y') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">No tickets found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{ $tickets->links() }}
    </div>
</div>
@endsection