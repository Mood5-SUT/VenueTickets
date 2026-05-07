@extends('layouts.master')

@section('title', 'Become an Organizer')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0 rounded-lg">
                <div class="card-header bg-warning text-dark text-center py-4">
                    <h3 class="mb-0">
                        <i class="bi bi-building-add me-2"></i>Become an Organizer
                    </h3>
                    <p class="small mb-0 mt-2">Create events and sell tickets on VenueTickets</p>
                </div>
                
                <div class="card-body p-5">
                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    
                    <form method="POST" action="{{ route('organizer.register_submit') }}">
                        {{ csrf_field() }}
                        
                        <h5 class="mb-3 text-primary">
                            <i class="bi bi-person me-2"></i>Account Information
                        </h5>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Full Name</label>
                                <input type="text" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name') }}" 
                                       required>
                                @error('name')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" 
                                       class="form-control @error('email') is-invalid @enderror" 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email') }}" 
                                       required>
                                @error('email')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" 
                                       class="form-control @error('password') is-invalid @enderror" 
                                       id="password" 
                                       name="password" 
                                       required>
                                @error('password')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="password_confirmation" class="form-label">Confirm Password</label>
                                <input type="password" 
                                       class="form-control" 
                                       id="password_confirmation" 
                                       name="password_confirmation" 
                                       required>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="text" 
                                   class="form-control @error('phone') is-invalid @enderror" 
                                   id="phone" 
                                   name="phone" 
                                   value="{{ old('phone') }}">
                        </div>
                        
                        <hr class="my-4">
                        
                        <h5 class="mb-3 text-primary">
                            <i class="bi bi-building me-2"></i>Business Information
                        </h5>
                        
                        <div class="mb-3">
                            <label for="company_name" class="form-label">Company/Organization Name</label>
                            <input type="text" 
                                   class="form-control @error('company_name') is-invalid @enderror" 
                                   id="company_name" 
                                   name="company_name" 
                                   value="{{ old('company_name') }}" 
                                   required>
                            @error('company_name')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="business_phone" class="form-label">Business Phone</label>
                                <input type="text" 
                                       class="form-control" 
                                       id="business_phone" 
                                       name="business_phone" 
                                       value="{{ old('business_phone') }}">
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="website" class="form-label">Website</label>
                                <input type="url" 
                                       class="form-control" 
                                       id="website" 
                                       name="website" 
                                       value="{{ old('website') }}" 
                                       placeholder="https://">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="tax_id" class="form-label">Tax ID / VAT Number</label>
                            <input type="text" 
                                   class="form-control" 
                                   id="tax_id" 
                                   name="tax_id" 
                                   value="{{ old('tax_id') }}">
                        </div>
                        
                        <div class="mb-4">
                            <label for="description" class="form-label">About Your Business</label>
                            <textarea class="form-control" 
                                      id="description" 
                                      name="description" 
                                      rows="3"
                                      placeholder="Tell us about your business and the types of events you organize">{{ old('description') }}</textarea>
                        </div>
                        
                        <div class="mb-4 form-check">
                            <input type="checkbox" class="form-check-input @error('terms') is-invalid @enderror" id="terms" name="terms" required>
                            <label class="form-check-label" for="terms">
                                I agree to the 
                                <a href="#" class="text-decoration-none">Organizer Terms of Service</a> 
                                and 
                                <a href="#" class="text-decoration-none">Platform Agreement</a>
                            </label>
                            @error('terms')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-warning btn-lg">
                                <i class="bi bi-send-check me-2"></i>Submit Application
                            </button>
                        </div>
                        
                        <div class="text-center mt-3">
                            <small class="text-muted">
                                Your application will be reviewed by our team. You will be notified via email once approved.
                            </small>
                        </div>
                        
                        <hr class="my-4">
                        
                        <div class="text-center">
                            <p class="mb-0">Already have an account? 
                                <a href="{{ route('login') }}" class="fw-bold text-decoration-none">
                                    Sign in here
                                </a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection