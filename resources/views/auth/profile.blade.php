@extends('layouts.master')

@section('title', 'My Profile')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <i class="bi bi-person-circle" style="font-size: 5rem;"></i>
                    <h4 class="mt-2">{{ $user->name }}</h4>
                    <p class="text-muted">{{ $user->email }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Edit Profile</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('profile_update') }}">
                        {{ csrf_field() }}
                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Phone</label>
                            <input type="text" name="phone" class="form-control" value="{{ $user->phone }}">
                        </div>
                        <button type="submit" class="btn btn-primary">Update Profile</button>
                        <a href="{{ route('password_change') }}" class="btn btn-outline-secondary">Change Password</a>
                    </form>
                </div>
            </div>
            
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">My Orders</h5>
                </div>
                <div class="card-body">
                    @if($orders->count() > 0)
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Order #</th>
                                    <th>Event</th>
                                    <th>Amount</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orders as $order)
                                <tr>
                                    <td>{{ $order->order_number }}</td>
                                    <td>{{ $order->event->name ?? 'N/A' }}</td>
                                    <td>${{ number_format($order->total_amount, 2) }}</td>
                                    <td>{{ $order->created_at->format('M d, Y') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p class="text-muted">No orders yet.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection