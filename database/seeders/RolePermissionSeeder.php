<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Clear cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        
        // Create Permissions
        $permissions = [
            'access_admin',
            'manage_events',
            'create_events',
            'edit_events',
            'delete_events',
            'manage_venues',
            'create_venues',
            'edit_venues',
            'manage_users',
            'ban_users',
            'unban_users',
            'manage_organizers',
            'approve_organizers',
            'manage_orders',
            'refund_orders',
            'void_tickets',
            'scan_tickets',
            'manage_roles',
            'manage_promo_codes',
            'view_finance',
            'process_payouts',
            'manage_system',
            'view_audit_log'
        ];
        
        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
        
        // Create Roles
        $superAdmin = Role::create(['name' => 'super-admin']);
        $superAdmin->syncPermissions(Permission::all());
        
        $admin = Role::create(['name' => 'admin']);
        $admin->syncPermissions([
            'access_admin', 'manage_events', 'create_events', 'edit_events',
            'manage_venues', 'manage_users', 'manage_organizers', 'approve_organizers',
            'manage_orders', 'refund_orders', 'void_tickets', 'scan_tickets',
            'manage_promo_codes', 'view_finance', 'view_audit_log'
        ]);
        
        $organizer = Role::create(['name' => 'organizer']);
        $organizer->syncPermissions([
            'create_events', 'edit_events', 'scan_tickets'
        ]);
        
        $staff = Role::create(['name' => 'staff']);
        $staff->syncPermissions(['scan_tickets']);
        
        $customer = Role::create(['name' => 'customer']);
        
        // Create Super Admin User
        User::create([
            'name' => 'Super Admin',
            'email' => 'admin@venuetickets.com',
            'password' => bcrypt('password'),
            'is_active' => true,
            'email_verified_at' => now()
        ])->assignRole('super-admin');
    }
}