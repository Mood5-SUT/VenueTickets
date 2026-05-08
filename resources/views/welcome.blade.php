@extends('layouts.master')

@section('title', 'Home')

@section('content')
<div class="py-5 bg-primary text-white">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-3">Find Amazing Events</h1>
                <p class="lead mb-4">Discover and book tickets for concerts, sports, theater, and more.</p>
                @guest
                    <div class="d-flex gap-2">
                        <a href="{{ route('register') }}" class="btn btn-light btn-lg">Get Started</a>
                        <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg">Login</a>
                    </div>
                @else
                    <h4>Welcome back, {{ auth()->user()->name }}!</h4>
                    @if(auth()->user()->hasRole(['super-admin', 'admin']))
                        <a href="{{ route('admin_dashboard') }}" class="btn btn-light btn-lg mt-2">Admin Dashboard</a>
                    @endif
                @endguest
            </div>
            <div class="col-lg-6 text-center">
                <i class="bi bi-ticket-perforated" style="font-size: 12rem;"></i>
            </div>
        </div>
    </div>
</div>

<div class="container py-5">
    <div class="row g-4">
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm text-center p-4">
                <div class="card-body">
                    <i class="bi bi-search text-primary" style="font-size: 3rem;"></i>
                    <h4 class="mt-3">Find Events</h4>
                    <p class="text-muted">Browse thousands of events and find the perfect one for you.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm text-center p-4">
                <div class="card-body">
                    <i class="bi bi-credit-card text-success" style="font-size: 3rem;"></i>
                    <h4 class="mt-3">Buy Tickets</h4>
                    <p class="text-muted">Secure checkout with instant digital ticket delivery.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm text-center p-4">
                <div class="card-body">
                    <i class="bi bi-emoji-smile text-warning" style="font-size: 3rem;"></i>
                    <h4 class="mt-3">Enjoy</h4>
                    <p class="text-muted">Show your digital ticket and enjoy the experience!</p>
                </div>
            </div>
        </div>
    </div>
    
    @auth
    <div class="row g-4 mt-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <i class="bi bi-calendar-event text-primary" style="font-size: 2rem;"></i>
                    <h5 class="mt-2">Browse Events</h5>
                    <a href="#" class="btn btn-outline-primary btn-sm">View Events</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <i class="bi bi-ticket-perforated text-success" style="font-size: 2rem;"></i>
                    <h5 class="mt-2">My Tickets</h5>
                    <a href="#" class="btn btn-outline-success btn-sm">View Tickets</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <i class="bi bi-person-circle text-warning" style="font-size: 2rem;"></i>
                    <h5 class="mt-2">My Profile</h5>
                    <a href="{{ route('profile') }}" class="btn btn-outline-warning btn-sm">Edit Profile</a>
                </div>
            </div>
        </div>
    </div>
    @endauth
</div>
@endsection