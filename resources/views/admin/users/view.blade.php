@extends('layouts.admin')

@section('title', 'User Details')

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-body text-center">
                <i class="bi bi-person-circle" style="font-size: 5rem;"></i>
                <h4 class="mt-2">{{ $user->name }}</h4>
                <p class="text-muted">{{ $user->email }}</p>
                <span class="badge bg-{{ $user->isBanned() ? 'danger' : 'success' }} mb-2">
                    {{ $user->isBanned() ? 'Banned' : 'Active' }}
                </span>
                
                <form method="POST" action="{{ route('admin_users_update_role', $user->id) }}" class="mt-3">
                    {{ csrf_field() }}
                    <label class="form-label">Assign Roles</label>
                    @foreach($roles as $role)
                        <div class="form-check text-start">
                            <input type="checkbox" name="roles[]" value="{{ $role->name }}" 
                                   class="form-check-input" {{ $user->hasRole($role->name) ? 'checked' : '' }}>
                            <label class="form-check-label">{{ ucfirst($role->name) }}</label>
                        </div>
                    @endforeach
                    <button type="submit" class="btn btn-primary btn-sm mt-2 w-100">Update Roles</button>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h5 class="mb-0">Order History</h5>
                @if($user->isBanned())
                    <form method="POST" action="{{ route('admin_users_unban', $user->id) }}">
                        {{ csrf_field() }}
                        <button type="submit" class="btn btn-success btn-sm">Unban User</button>
                    </form>
                @else
                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#banModal">
                        Ban User
                    </button>
                @endif
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Order #</th>
                                <th>Event</th>
                                <th>Amount</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($user->orders as $order)
                            <tr>
                                <td>{{ $order->order_number }}</td>
                                <td>{{ $order->event->name ?? 'N/A' }}</td>
                                <td>${{ number_format($order->total_amount, 2) }}</td>
                                <td>{{ $order->created_at->format('M d, Y') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">No orders</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Ban Modal -->
<div class="modal fade" id="banModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin_users_ban', $user->id) }}">
                {{ csrf_field() }}
                <div class="modal-header">
                    <h5 class="modal-title">Ban User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <label class="form-label">Reason for ban</label>
                    <textarea name="reason" class="form-control" rows="3" required></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Ban User</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection