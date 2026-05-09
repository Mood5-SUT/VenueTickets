@extends('layouts.admin')

@section('title', 'Stripe Balance')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Stripe Balance</h4>
    <a href="{{ route('admin_finance_index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Back to Finance
    </a>
</div>

<div class="row g-3">
    <div class="col-md-6">
        <div class="card bg-success text-white">
            <div class="card-body text-center py-4">
                <h1>${{ number_format($balance['available'], 2) }}</h1>
                <small>Available Balance</small>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card bg-warning text-white">
            <div class="card-body text-center py-4">
                <h1>${{ number_format($balance['pending'], 2) }}</h1>
                <small>Pending Balance</small>
            </div>
        </div>
    </div>
</div>
@endsection