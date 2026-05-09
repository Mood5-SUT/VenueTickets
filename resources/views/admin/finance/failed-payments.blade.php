@extends('layouts.admin')

@section('title', 'Failed Payments')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Failed Payments</h4>
    <a href="{{ route('admin_finance_index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Back to Finance
    </a>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="card bg-danger text-white">
            <div class="card-body text-center">
                <h3>{{ $totalFailed }}</h3>
                <small>Total Failures</small>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card bg-warning text-white">
            <div class="card-body text-center">
                <h3>${{ number_format($totalAmount, 2) }}</h3>
                <small>Total Amount Lost</small>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User</th>
                        <th>Provider</th>
                        <th>Amount</th>
                        <th>Error</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($failedPayments as $payment)
                    <tr>
                        <td>#{{ $payment->id }}</td>
                        <td>{{ $payment->user->name ?? 'N/A' }}</td>
                        <td>{{ ucfirst($payment->payment_provider) }}</td>
                        <td>${{ number_format($payment->amount, 2) }}</td>
                        <td><small class="text-danger">{{ Str::limit($payment->error_message, 40) }}</small></td>
                        <td>{{ $payment->created_at->format('M d, Y H:i') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">No failed payments</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{ $failedPayments->links() }}
    </div>
</div>
@endsection