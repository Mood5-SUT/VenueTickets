@extends('layouts.master')

@section('title', 'Become an Organizer')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <i class="bi bi-building-add text-warning" style="font-size: 3rem;"></i>
                        <h3 class="mt-2">Become an Organizer</h3>
                        <p class="text-muted">Create events and sell tickets</p>
                    </div>
                    
                    @if($errors->any())
                        <div class="alert alert-danger">
                            @foreach($errors->all() as $error)
                                <small>{{ $error }}</small><br>
                            @endforeach
                        </div>
                    @endif
                    
                    <form method="POST" action="{{ route('organizer.register_submit') }}">
                        {{ csrf_field() }}
                        
                        <h5 class="mb-3">Account Information</h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Full Name</label>
                                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Confirm Password</label>
                                <input type="password" name="password_confirmation" class="form-control" required>
                            </div>
                        </div>
                        
                        <hr>
                        
                        <h5 class="mb-3">Business Information</h5>
                        <div class="mb-3">
                            <label class="form-label">Company Name</label>
                            <input type="text" name="company_name" class="form-control" value="{{ old('company_name') }}" required>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Business Phone</label>
                                <input type="text" name="business_phone" class="form-control" value="{{ old('business_phone') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Website</label>
                                <input type="url" name="website" class="form-control" value="{{ old('website') }}" placeholder="https://">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">About Your Business</label>
                            <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                        </div>
                        
                        <div class="mb-3 form-check">
                            <input type="checkbox" name="terms" class="form-check-input" id="terms" required>
                            <label class="form-check-label small" for="terms">I agree to Organizer Terms</label>
                        </div>
                        
                        <button type="submit" class="btn btn-warning w-100">Submit Application</button>
                        
                        <div class="text-center mt-3">
                            <small class="text-muted">Your application will be reviewed by our team.</small>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection