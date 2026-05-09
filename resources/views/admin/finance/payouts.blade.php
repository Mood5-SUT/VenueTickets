@extends('layouts.admin')

@section('title', 'Payout Queue')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Payout Queue</h4>
    <a href="{{ route('admin_finance_index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Back to Finance
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form method="GET" class="row g-3 mb-4">
            <div class="col-md-4">
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
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
                        <th>ID</th>
                        <th>Organizer</th>
                        <th>Amount</th>
                        <th>Period</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payouts as $payout)
                    <tr>
                        <td>#{{ $payout->id }}</td>
                        <td>{{ $payout->organizer->name ?? 'N/A' }}</td>
                        <td>${{ number_format($payout->amount, 2) }}</td>
                        <td>{{ $payout->period_start->format('M d') }} - {{ $payout->period_end->format('M d, Y') }}</td>
                        <td>
                            <span class="badge bg-{{ $payout->status === 'completed' ? 'success' : ($payout->status === 'pending' ? 'warning' : ($payout->status === 'failed' ? 'danger' : 'info')) }}">
                                {{ ucfirst($payout->status) }}
                            </span>
                        </td>
                        <td>{{ $payout->created_at->format('M d, Y') }}</td>
                        <td>
                            @if($payout->status === 'pending')
                                <form method="POST" action="{{ route('admin_finance_process_payout', $payout->id) }}" class="d-inline">
                                    {{ csrf_field() }}
                                    <button type="submit" class="btn btn-sm btn-success">Process</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">No payouts found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{ $payouts->links() }}
    </div>
</div>
@endsection