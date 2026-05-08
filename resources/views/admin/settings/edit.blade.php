@extends('layouts.admin')

@section('title', 'Settings')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Platform Settings</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin_settings_save') }}">
                    {{ csrf_field() }}
                    
                    <h6 class="text-primary mb-3">General Settings</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label">Site Name</label>
                            <input type="text" name="settings[0][key]" value="site_name" hidden>
                            <input type="text" name="settings[0][value]" class="form-control" 
                                   value="{{ \App\Models\PlatformSetting::get('site_name', 'VenueTickets') }}">
                            <input type="text" name="settings[0][type]" value="string" hidden>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Support Email</label>
                            <input type="text" name="settings[1][key]" value="support_email" hidden>
                            <input type="email" name="settings[1][value]" class="form-control"
                                   value="{{ \App\Models\PlatformSetting::get('support_email', 'support@venuetickets.com') }}">
                            <input type="text" name="settings[1][type]" value="string" hidden>
                        </div>
                    </div>
                    
                    <h6 class="text-primary mb-3">Fee Settings</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label">Service Fee (%)</label>
                            <input type="text" name="settings[2][key]" value="service_fee_percentage" hidden>
                            <input type="number" name="settings[2][value]" class="form-control" step="0.01"
                                   value="{{ \App\Models\PlatformSetting::get('service_fee_percentage', 5) }}">
                            <input type="text" name="settings[2][type]" value="float" hidden>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Organizer Commission (%)</label>
                            <input type="text" name="settings[3][key]" value="organizer_commission" hidden>
                            <input type="number" name="settings[3][value]" class="form-control" step="0.01"
                                   value="{{ \App\Models\PlatformSetting::get('organizer_commission', 95) }}">
                            <input type="text" name="settings[3][type]" value="float" hidden>
                        </div>
                    </div>
                    
                    <h6 class="text-primary mb-3">Limits</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label">Max Tickets Per Order</label>
                            <input type="text" name="settings[4][key]" value="max_tickets_per_order" hidden>
                            <input type="number" name="settings[4][value]" class="form-control"
                                   value="{{ \App\Models\PlatformSetting::get('max_tickets_per_order', 10) }}">
                            <input type="text" name="settings[4][type]" value="integer" hidden>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Default Resale Price Cap (%)</label>
                            <input type="text" name="settings[5][key]" value="resale_price_cap_default" hidden>
                            <input type="number" name="settings[5][value]" class="form-control"
                                   value="{{ \App\Models\PlatformSetting::get('resale_price_cap_default', 120) }}">
                            <input type="text" name="settings[5][type]" value="float" hidden>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg me-1"></i> Save Settings
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Maintenance Mode</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin_settings_maintenance') }}">
                    {{ csrf_field() }}
                    <p class="text-muted">Enable maintenance mode to prevent users from accessing the site.</p>
                    <input type="hidden" name="maintenance_mode" value="{{ app()->isDownForMaintenance() ? '0' : '1' }}">
                    <button type="submit" class="btn btn-{{ app()->isDownForMaintenance() ? 'success' : 'warning' }} w-100">
                        {{ app()->isDownForMaintenance() ? 'Disable Maintenance' : 'Enable Maintenance' }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection