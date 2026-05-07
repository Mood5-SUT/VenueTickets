<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AuditLog;
use App\Models\User;
use Carbon\Carbon;

class AuditController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:web');
    }
    
    public function list(Request $request)
    {
        if(!auth()->user()->hasPermissionTo('manage_system')) {
            abort(403);
        }
        
        $query = AuditLog::with('user');
        
        if ($request->user_id) {
            $query->where('user_id', $request->user_id);
        }
        
        if ($request->action) {
            $query->where('action', $request->action);
        }
        
        if ($request->model_type) {
            $query->where('model_type', $request->model_type);
        }
        
        if ($request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        if ($request->search) {
            $query->where('description', 'like', '%' . $request->search . '%');
        }
        
        $logs = $query->orderBy('created_at', 'desc')->paginate(50);
        $users = User::orderBy('name')->get(['id', 'name']);
        
        $actions = AuditLog::distinct()->pluck('action');
        $modelTypes = AuditLog::distinct()->whereNotNull('model_type')->pluck('model_type');
        
        return view('admin.audit.list', compact('logs', 'users', 'actions', 'modelTypes'));
    }
    
    public function view($id)
    {
        if(!auth()->user()->hasPermissionTo('manage_system')) {
            abort(403);
        }
        
        $log = AuditLog::with('user')->findOrFail($id);
        
        return view('admin.audit.view', compact('log'));
    }
    
    public function userActivity($userId)
    {
        if(!auth()->user()->hasPermissionTo('manage_system')) {
            abort(403);
        }
        
        $user = User::findOrFail($userId);
        $logs = AuditLog::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->paginate(50);
        
        return view('admin.audit.user-activity', compact('user', 'logs'));
    }
    
    public static function log($action, $model = null, $description = null, $oldValues = null, $newValues = null)
    {
        $logData = [
            'user_id' => auth()->id(),
            'action' => $action,
            'model_type' => $model ? get_class($model) : null,
            'model_id' => $model ? $model->id : null,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'description' => $description
        ];
        
        return AuditLog::create($logData);
    }
    
    public function export(Request $request)
    {
        if(!auth()->user()->hasPermissionTo('manage_system')) {
            abort(403);
        }
        
        $logs = AuditLog::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(1000)
            ->get();
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="audit_log_' . date('Y-m-d') . '.csv"',
        ];
        
        $callback = function() use ($logs) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'User', 'Action', 'Model', 'Description', 'IP Address', 'Date']);
            
            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->id,
                    $log->user ? $log->user->name : 'System',
                    $log->action,
                    $log->model_type . ' #' . $log->model_id,
                    $log->description,
                    $log->ip_address,
                    $log->created_at->format('Y-m-d H:i:s')
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}