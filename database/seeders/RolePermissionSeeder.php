<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Artisan;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        Artisan::call('cache:clear');
        
        // Create Permissions
        $permissions = [
            // Admin Access
            'access_admin',
            
            // Events
            'manage_events',
            'create_events',
            'edit_events',
            'delete_events',
            
            // Venues
            'manage_venues',
            'create_venues',
            'edit_venues',
            
            // Users
            'manage_users',
            'ban_users',
            'unban_users',
            
            // Organizers
            'manage_organizers',
            'approve_organizers',
            
            // Orders
            'manage_orders',
            'refund_orders',
            'void_tickets',
            
            // Tickets
            'scan_tickets',
            
            // Roles
            'manage_roles',
            
            // Promo Codes
            'manage_promo_codes',
            
            // Finance
            'view_finance',
            'process_payouts',
            
            // System
            'manage_system',
            'view_audit_log'
        ];
        
        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission, 'guard_name' => 'web']);
        }
        
        // Create Roles
        $superAdmin = Role::create(['name' => 'super-admin', 'guard_name' => 'web']);
        $superAdmin->syncPermissions(Permission::all());
        
        $admin = Role::create(['name' => 'admin', 'guard_name' => 'web']);
        $admin->syncPermissions([
            'access_admin', 'manage_events', 'create_events', 'edit_events',
            'manage_venues', 'manage_users', 'manage_organizers', 'approve_organizers',
            'manage_orders', 'refund_orders', 'void_tickets', 'scan_tickets',
            'manage_promo_codes', 'view_finance', 'view_audit_log'
        ]);
        
        $organizer = Role::create(['name' => 'organizer', 'guard_name' => 'web']);
        $organizer->syncPermissions([
            'create_events', 'edit_events', 'scan_tickets'
        ]);
        
        $staff = Role::create(['name' => 'staff', 'guard_name' => 'web']);
        $staff->syncPermissions([
            'scan_tickets'
        ]);
        
        $customer = Role::create(['name' => 'customer', 'guard_name' => 'web']);
        
        // Create Super Admin User
        $user = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@venuetickets.com',
            'password' => bcrypt('password'),
            'is_active' => true,
            'email_verified_at' => now()
        ]);
        $user->assignRole('super-admin');
    }
}