<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PlatformSetting;
use Artisan;

class SettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:web');
    }
    
    public function edit()
    {
        if(!auth()->user()->hasPermissionTo('manage_system')) {
            abort(403);
        }
        
        $settings = PlatformSetting::all()->groupBy('group');
        
        return view('admin.settings.edit', compact('settings'));
    }
    
    public function save(Request $request)
    {
        if(!auth()->user()->hasPermissionTo('manage_system')) {
            abort(403);
        }
        
        $this->validate($request, [
            'settings' => 'required|array',
            'settings.*.key' => 'required|string',
            'settings.*.value' => 'required',
            'settings.*.type' => 'required|in:string,integer,boolean,json,float'
        ]);
        
        foreach ($request->settings as $settingData) {
            PlatformSetting::set(
                $settingData['key'],
                $settingData['value'],
                $settingData['type']
            );
        }
        
        Artisan::call('cache:clear');
        
        return redirect()->back()->with('success', 'Settings saved successfully.');
    }
    
    public function toggleMaintenance(Request $request)
    {
        if(!auth()->user()->hasPermissionTo('manage_system')) {
            abort(403);
        }
        
        $this->validate($request, [
            'maintenance_mode' => 'required|boolean'
        ]);
        
        if ($request->maintenance_mode) {
            Artisan::call('down', [
                '--secret' => $request->secret ?? 'admin-bypass',
                '--retry' => 60
            ]);
            PlatformSetting::set('maintenance_mode', 'true', 'boolean');
        } else {
            Artisan::call('up');
            PlatformSetting::set('maintenance_mode', 'false', 'boolean');
        }
        
        return redirect()->back()->with('success', 'Maintenance mode updated.');
    }
    
    public function initializeDefaultSettings()
    {
        if(!auth()->user()->hasPermissionTo('manage_system')) {
            abort(403);
        }
        
        $defaultSettings = [
            // General
            ['key' => 'site_name', 'value' => 'VenueTickets', 'type' => 'string', 'group' => 'general', 'label' => 'Site Name'],
            ['key' => 'support_email', 'value' => 'support@venuetickets.com', 'type' => 'string', 'group' => 'general', 'label' => 'Support Email'],
            ['key' => 'currency', 'value' => 'USD', 'type' => 'string', 'group' => 'general', 'label' => 'Currency'],
            ['key' => 'timezone', 'value' => 'UTC', 'type' => 'string', 'group' => 'general', 'label' => 'Timezone'],
            
            // Fees
            ['key' => 'service_fee_percentage', 'value' => '5', 'type' => 'float', 'group' => 'fees', 'label' => 'Service Fee (%)'],
            ['key' => 'service_fee_fixed', 'value' => '2.50', 'type' => 'float', 'group' => 'fees', 'label' => 'Service Fee (Fixed)'],
            ['key' => 'organizer_commission', 'value' => '95', 'type' => 'float', 'group' => 'fees', 'label' => 'Organizer Commission (%)'],
            ['key' => 'resale_platform_fee', 'value' => '10', 'type' => 'float', 'group' => 'fees', 'label' => 'Resale Platform Fee (%)'],
            
            // Limits
            ['key' => 'max_tickets_per_order', 'value' => '10', 'type' => 'integer', 'group' => 'limits', 'label' => 'Max Tickets Per Order'],
            ['key' => 'resale_price_cap_default', 'value' => '120', 'type' => 'float', 'group' => 'limits', 'label' => 'Default Resale Price Cap (%)'],
            ['key' => 'payout_minimum', 'value' => '50', 'type' => 'float', 'group' => 'limits', 'label' => 'Minimum Payout Amount'],
            ['key' => 'max_events_per_organizer', 'value' => '50', 'type' => 'integer', 'group' => 'limits', 'label' => 'Max Events Per Organizer'],
            
            // Notifications
            ['key' => 'email_notifications', 'value' => 'true', 'type' => 'boolean', 'group' => 'notifications', 'label' => 'Email Notifications'],
            ['key' => 'admin_notification_email', 'value' => 'admin@venuetickets.com', 'type' => 'string', 'group' => 'notifications', 'label' => 'Admin Notification Email'],
            ['key' => 'notify_new_organizer', 'value' => 'true', 'type' => 'boolean', 'group' => 'notifications', 'label' => 'Notify New Organizer Applications'],
            ['key' => 'notify_refund_requests', 'value' => 'true', 'type' => 'boolean', 'group' => 'notifications', 'label' => 'Notify Refund Requests'],
        ];
        
        foreach ($defaultSettings as $setting) {
            PlatformSetting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
        
        Artisan::call('cache:clear');
        
        return redirect()->back()->with('success', 'Default settings initialized successfully.');
    }
}