@extends('layouts.master')

@section('title', 'Verify OTP')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-sm border-0">
                <div class="card-body p-5 text-center">
                    <i class="bi bi-shield-check text-primary" style="font-size: 4rem;"></i>
                    <h3 class="mt-3">Verify Your Email</h3>
                    <p class="text-muted">
                        We've sent a 6-digit code to <strong>{{ session('email') ?? auth()->user()->email ?? '' }}</strong>
                    </p>
                    
                    @if($errors->any())
                        <div class="alert alert-danger text-start">
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
                    
                    <form method="POST" action="{{ route('verify_otp_submit') }}">
                        {{ csrf_field() }}
                        
                        <div class="mb-3">
                            <label class="form-label">Enter OTP Code</label>
                            <input type="text" 
                                   name="otp" 
                                   class="form-control form-control-lg text-center" 
                                   placeholder="000000" 
                                   maxlength="6" 
                                   pattern="[0-9]{6}" 
                                   required 
                                   autofocus 
                                   style="font-size: 2rem; letter-spacing: 10px;">
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100 mb-3">
                            <i class="bi bi-check-circle me-1"></i> Verify OTP
                        </button>
                        
                        <div class="text-center">
                            <p class="mb-1">
                                Didn't receive the code? 
                                <form method="POST" action="{{ route('resend_otp') }}" class="d-inline">
                                    {{ csrf_field() }}
                                    <button type="submit" class="btn btn-link p-0">Resend OTP</button>
                                </form>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection