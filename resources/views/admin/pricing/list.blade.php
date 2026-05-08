@extends('layouts.admin')

@section('title', 'Pricing Tiers - ' . $event->name)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Ticket Pricing Tiers for {{ $event->name }}</h4>
    <a href="{{ route('admin_events_list') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left me-1"></i> Back to Events
    </a>
</div>

<!-- Add Tier Form -->
<div class="card mb-4">
    <div class="card-header bg-light">
        <h5 class="mb-0">Add New Pricing Tier (e.g., VIP, Standard, Balcony)</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin_events_pricing_save', $event->id) }}">
            @csrf
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Tier Name</label>
                    <input type="text" name="name" class="form-control" placeholder="e.g., VIP Front Row" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Price ($)</label>
                    <input type="number" step="0.01" name="price" class="form-control" placeholder="150.00" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Total Capacity</label>
                    <input type="number" name="quantity" class="form-control" placeholder="Leave empty for unlimited">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Min/Order</label>
                    <input type="number" name="min_per_order" class="form-control" value="1" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Max/Order</label>
                    <input type="number" name="max_per_order" class="form-control" placeholder="Max limit">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Start Sales At</label>
                    <input type="datetime-local" name="starts_at" class="form-control" value="{{ \Carbon\Carbon::now()->format('Y-m-d\TH:i') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">End Sales At</label>
                    <input type="datetime-local" name="ends_at" class="form-control" value="{{ \Carbon\Carbon::parse($event->event_date)->format('Y-m-d\TH:i') }}" required>
                </div>
                <div class="col-12">
                    <label class="form-label">Description (optional)</label>
                    <textarea name="description" class="form-control" rows="2" placeholder="Describe what is included with this ticket..."></textarea>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-plus-circle me-1"></i> Add Tier</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- List Existing Tiers -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Existing Tiers</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Capacity</th>
                        <th>Sales Window</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($event->pricingTiers as $tier)
                    <tr>
                        <td>
                            <strong>{{ $tier->name }}</strong><br>
                            <small class="text-muted">{{ Str::limit($tier->description, 50) }}</small>
                        </td>
                        <td class="fw-bold">${{ number_format($tier->price, 2) }}</td>
                        <td>{{ $tier->quantity ? $tier->sold_count . ' / ' . $tier->quantity : 'Unlimited' }}</td>
                        <td>
                            <small>
                                Start: {{ \Carbon\Carbon::parse($tier->starts_at)->format('M d, Y h:i A') }}<br>
                                End: {{ \Carbon\Carbon::parse($tier->ends_at)->format('M d, Y h:i A') }}
                            </small>
                        </td>
                        <td>
                            <span class="badge bg-{{ $tier->is_active ? 'success' : 'secondary' }}">
                                {{ $tier->is_active ? 'Active' : 'Paused' }}
                            </span>
                        </td>
                        <td>
                            <form action="{{ route('admin_events_pricing_delete', [$event->id, $tier->id]) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this pricing tier?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            <i class="bi bi-ticket-perforated fs-1 d-block mb-2 opacity-50"></i>
                            No pricing tiers defined yet.<br>
                            Users will see the "Base Ticket Price" as standard admission.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
