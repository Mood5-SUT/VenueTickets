<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserBan;
use App\Models\Order;
use Spatie\Permission\Models\Role;
use Artisan;

class UsersController extends Controller
{
public function list(Request $request)
    {
        if(!auth()->user()->hasPermissionTo('manage_users')) {
            abort(403);
        }
        
        $query = User::with('roles');
        
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }
        
        if ($request->role) {
            $query->whereHas('roles', function($q) use ($request) {
                $q->where('name', $request->role);
            });
        }
        
        if ($request->status === 'banned') {
            $query->whereHas('bans', function($q) {
                $q->where('is_active', true);
            });
        }
        
        $users = $query->orderBy('created_at', 'desc')->paginate(20);
        $roles = Role::all();
        
        return view('admin.users.list', compact('users', 'roles'));
    }
    
    public function view($id)
    {
        if(!auth()->user()->hasPermissionTo('manage_users')) {
            abort(403);
        }
        
        $user = User::with(['roles', 'orders.event', 'tickets.event', 'bans'])->findOrFail($id);
        $roles = Role::all();
        
        return view('admin.users.view', compact('user', 'roles'));
    }
    
    public function updateRole(Request $request, $id)
    {
        if(!auth()->user()->hasPermissionTo('manage_users')) {
            abort(403);
        }
        
        $this->validate($request, [
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,name'
        ]);
        
        $user = User::findOrFail($id);
        $user->syncRoles($request->roles);
        
        Artisan::call('cache:clear');
        
        return redirect()->back()->with('success', 'User roles updated successfully.');
    }
    
    public function ban(Request $request, $id)
    {
        if(!auth()->user()->hasPermissionTo('manage_users')) {
            abort(403);
        }
        
        $this->validate($request, [
            'reason' => 'required|string|min:10'
        ]);
        
        $user = User::findOrFail($id);
        
        UserBan::create([
            'user_id' => $user->id,
            'banned_by' => auth()->id(),
            'reason' => $request->reason,
            'banned_at' => now()
        ]);
        
        return redirect()->back()->with('success', 'User banned successfully.');
    }
    
    public function unban(Request $request, $id)
    {
        if(!auth()->user()->hasPermissionTo('manage_users')) {
            abort(403);
        }
        
        $user = User::findOrFail($id);
        
        UserBan::where('user_id', $user->id)
            ->where('is_active', true)
            ->update([
                'is_active' => false,
                'unbanned_at' => now(),
                'unbanned_by' => auth()->id()
            ]);
        
        return redirect()->back()->with('success', 'User unbanned successfully.');
    }
    
    public function export()
    {
        if(!auth()->user()->hasPermissionTo('manage_users')) {
            abort(403);
        }
        
        $users = User::with('roles')->get();
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="users_export_' . date('Y-m-d') . '.csv"',
        ];
        
        $callback = function() use ($users) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Name', 'Email', 'Phone', 'Roles', 'Created At', 'Status']);
            
            foreach ($users as $user) {
                fputcsv($file, [
                    $user->id,
                    $user->name,
                    $user->email,
                    $user->phone,
                    $user->roles->pluck('name')->implode(', '),
                    $user->created_at->format('Y-m-d H:i:s'),
                    $user->isBanned() ? 'Banned' : 'Active'
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}