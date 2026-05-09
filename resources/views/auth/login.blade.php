@extends('layouts.master')

@section('title', 'Login')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-sm border-0">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <i class="bi bi-ticket-perforated text-primary" style="font-size: 3rem;"></i>
                        <h3 class="mt-2">Welcome Back</h3>
                        <p class="text-muted">Sign in to your account</p>
                    </div>
                    
                    @if($errors->any())
                        <div class="alert alert-danger">
                            @foreach($errors->all() as $error)
                                <small>{{ $error }}</small><br>
                            @endforeach
                        </div>
                    @endif
                    
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    <!-- Social Login Button -->
                    <div class="mb-4">
                        <div class="d-grid">
                            <a href="{{ route('social.google') }}" class="btn btn-outline-danger">
                                <i class="bi bi-google me-2"></i> Continue with Google
                            </a>
                        </div>
                    </div>
                    
                    <div class="text-center mb-4">
                        <span class="text-muted">or sign in with email</span>
                        <hr>
                    </div>
                    
                    <form method="POST" action="{{ route('login_submit') }}">
                        {{ csrf_field() }}
                        
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        
                        <div class="mb-3 form-check">
                            <input type="checkbox" name="remember" class="form-check-input" id="remember">
                            <label class="form-check-label" for="remember">Remember Me</label>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100 mb-3">Sign In</button>
                        
                        <div class="text-center">
                            <a href="{{ route('password.request') }}" class="text-decoration-none small">Forgot Password?</a>
                        </div>
                        
                        <hr>
                        
                        <div class="text-center">
                            <p class="mb-0 small">Don't have an account? <a href="{{ route('register') }}">Register</a></p>
                            <a href="{{ route('organizer.register') }}" class="small text-decoration-none">Become an Organizer</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection