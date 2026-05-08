@extends('layouts.admin')

@section('title', 'Promo Codes')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Promo Codes</h4>
    <a href="{{ route('admin_promo_codes_create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Create Promo Code
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form method="GET" class="row g-3 mb-4">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Search codes..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">All</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
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
                        <th>Code</th>
                        <th>Type</th>
                        <th>Value</th>
                        <th>Used</th>
                        <th>Expires</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($promoCodes as $code)
                    <tr>
                        <td><strong>{{ $code->code }}</strong></td>
                        <td>{{ $code->type === 'percentage' ? 'Percentage' : 'Fixed Amount' }}</td>
                        <td>{{ $code->type === 'percentage' ? $code->value . '%' : '$' . number_format($code->value, 2) }}</td>
                        <td>{{ $code->used_count }}/{{ $code->max_uses ?? '∞' }}</td>
                        <td>{{ $code->expires_at->format('M d, Y') }}</td>
                        <td>
                            <span class="badge bg-{{ $code->isValid() ? 'success' : 'danger' }}">
                                {{ $code->isValid() ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('admin_promo_codes_edit', $code->id) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                            <a href="{{ route('admin_promo_codes_usage', $code->id) }}" class="btn btn-sm btn-outline-info">Usage</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">No promo codes found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{ $promoCodes->links() }}
    </div>
</div>
@endsection