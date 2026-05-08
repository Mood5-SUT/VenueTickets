<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use App\Models\AuditLog;
use Artisan;

class RolesController extends Controller
{
public function list()
    {
        if(!auth()->user()->hasPermissionTo('manage_roles')) {
            abort(403);
        }
        
        $roles = Role::with('permissions')->get();
        $permissions = Permission::all();
        
        return view('admin.roles.list', compact('roles', 'permissions'));
    }
    
    public function updatePermissions(Request $request, $id)
    {
        if(!auth()->user()->hasPermissionTo('manage_roles')) {
            abort(403);
        }
        
        $this->validate($request, [
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,name'
        ]);
        
        $role = Role::findOrFail($id);
        $role->syncPermissions($request->permissions);
        
        Artisan::call('cache:clear');
        
        return redirect()->back()->with('success', 'Permissions updated successfully.');
    }
    
    public function createStaff(Request $request)
    {
        if(!auth()->user()->hasPermissionTo('manage_roles')) {
            abort(403);
        }
        
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|exists:roles,name'
        ]);
        
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);
        
        $user->assignRole($request->role);
        
        return redirect()->back()->with('success', 'Staff account created successfully.');
    }
    
    public function activityLog()
    {
        if(!auth()->user()->hasPermissionTo('manage_roles')) {
            abort(403);
        }
        
        $logs = AuditLog::with('user')->orderBy('created_at', 'desc')->paginate(50);
        
        return view('admin.roles.activity-log', compact('logs'));
    }
}