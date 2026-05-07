@extends('layouts.master')

@section('title', 'Welcome')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">
            <h1 class="display-4 fw-bold mb-4">Welcome to VenueTickets</h1>
            <p class="lead mb-4">
                Your one-stop platform for event ticketing and seat reservations.
            </p>
            
            @guest
                <div class="d-flex justify-content-center gap-3">
                    <a href="{{ route('login') }}" class="btn btn-primary btn-lg px-4">
                        <i class="bi bi-box-arrow-in-right me-2"></i>Login
                    </a>
                    <a href="{{ route('register') }}" class="btn btn-success btn-lg px-4">
                        <i class="bi bi-person-plus me-2"></i>Register
                    </a>
                </div>
            @else
                <div class="alert alert-success">
                    <h4>Welcome back, {{ auth()->user()->name }}!</h4>
                    <p class="mb-0">You are logged in as 
                        <span class="badge bg-primary">{{ auth()->user()->roles->first()->name ?? 'User' }}</span>
                    </p>
                </div>
                
                <div class="row g-3 mt-4">
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body text-center">
                                <i class="bi bi-calendar-event fs-1 text-primary"></i>
                                <h5 class="mt-2">Browse Events</h5>
                                <p class="text-muted small">Discover upcoming events near you</p>
                                <a href="#" class="btn btn-outline-primary btn-sm">View Events</a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body text-center">
                                <i class="bi bi-ticket-perforated fs-1 text-success"></i>
                                <h5 class="mt-2">My Tickets</h5>
                                <p class="text-muted small">View your purchased tickets</p>
                                <a href="#" class="btn btn-outline-success btn-sm">View Tickets</a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body text-center">
                                <i class="bi bi-person-circle fs-1 text-warning"></i>
                                <h5 class="mt-2">My Profile</h5>
                                <p class="text-muted small">Manage your account settings</p>
                                <a href="{{ route('profile') }}" class="btn btn-outline-warning btn-sm">Edit Profile</a>
                            </div>
                        </div>
                    </div>
                </div>
                
                @if(auth()->user()->hasRole(['super-admin', 'admin']))
                    <div class="mt-4">
                        <a href="{{ route('admin_dashboard') }}" class="btn btn-danger">
                            <i class="bi bi-speedometer2 me-2"></i>Go to Admin Dashboard
                        </a>
                    </div>
                @endif
            @endguest
        </div>
    </div>
</div>
@endsection