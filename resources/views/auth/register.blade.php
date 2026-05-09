@extends('layouts.master')

@section('title', 'Register')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <i class="bi bi-person-plus text-success" style="font-size: 3rem;"></i>
                        <h3 class="mt-2">Create Account</h3>
                        <p class="text-muted">Join VenueTickets today</p>
                    </div>
                    
                    @if($errors->any())
                        <div class="alert alert-danger">
                            @foreach($errors->all() as $error)
                                <small>{{ $error }}</small><br>
                            @endforeach
                        </div>
                    @endif
                    
                    <!-- Social Register Button -->
                    <div class="mb-4">
                        <div class="d-grid">
                            <a href="{{ route('social.google') }}" class="btn btn-outline-danger">
                                <i class="bi bi-google me-2"></i> Sign up with Google
                            </a>
                        </div>
                    </div>
                    
                    <div class="text-center mb-4">
                        <span class="text-muted">or register with email</span>
                        <hr>
                    </div>
                    
                    <form method="POST" action="{{ route('register_submit') }}">
                        {{ csrf_field() }}
                        
                        <div class="mb-3">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Phone (Optional)</label>
                            <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" required>
                            <small class="text-muted">Min 8 characters</small>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Confirm Password</label>
                            <input type="password" name="password_confirmation" class="form-control" required>
                        </div>
                        
                        <div class="mb-3 form-check">
                            <input type="checkbox" name="terms" class="form-check-input" id="terms" required>
                            <label class="form-check-label small" for="terms">I agree to Terms of Service</label>
                        </div>
                        
                        <button type="submit" class="btn btn-success w-100 mb-3">Create Account</button>
                        
                        <hr>
                        
                        <div class="text-center">
                            <p class="mb-0 small">Already have an account? <a href="{{ route('login') }}">Login</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection