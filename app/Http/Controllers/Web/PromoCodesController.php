<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PromoCode;
use App\Models\Event;

class PromoCodesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:web');
    }
    
    public function list(Request $request)
    {
        if(!auth()->user()->hasPermissionTo('manage_promo_codes')) {
            abort(403);
        }
        
        $query = PromoCode::withCount('usage');
        
        if ($request->status === 'active') {
            $query->where('is_active', true)
                  ->where('expires_at', '>', now());
        } elseif ($request->status === 'expired') {
            $query->where('expires_at', '<=', now());
        }
        
        $promoCodes = $query->orderBy('created_at', 'desc')->paginate(20);
        
        return view('admin.promo-codes.list', compact('promoCodes'));
    }
    
    public function edit($id = null)
    {
        if(!auth()->user()->hasPermissionTo('manage_promo_codes')) {
            abort(403);
        }
        
        $promoCode = $id ? PromoCode::findOrFail($id) : null;
        $events = Event::where('status', 'published')->get();
        
        return view('admin.promo-codes.edit', compact('promoCode', 'events'));
    }
    
    public function save(Request $request, $id = null)
    {
        if(!auth()->user()->hasPermissionTo('manage_promo_codes')) {
            abort(403);
        }
        
        $this->validate($request, [
            'code' => 'required|string|max:50|unique:promo_codes,code,' . $id,
            'type' => 'required|in:percentage,fixed_amount',
            'value' => 'required|numeric|min:0',
            'min_order_amount' => 'nullable|numeric|min:0',
            'max_discount_amount' => 'nullable|numeric|min:0',
            'max_uses' => 'nullable|integer|min:1',
            'max_uses_per_user' => 'required|integer|min:1',
            'starts_at' => 'nullable|date',
            'expires_at' => 'required|date|after:starts_at',
            'scope' => 'required|in:global,event_specific',
            'description' => 'nullable|string',
            'applicable_events' => 'nullable|array',
            'applicable_tiers' => 'nullable|array'
        ]);
        
        $promoCode = $id ? PromoCode::findOrFail($id) : new PromoCode();
        $promoCode->fill($request->all());
        $promoCode->save();
        
        return redirect()->route('admin_promo_codes_edit', $promoCode->id)
            ->with('success', 'Promo code saved successfully.');
    }
    
    public function deactivate($id)
    {
        if(!auth()->user()->hasPermissionTo('manage_promo_codes')) {
            abort(403);
        }
        
        $promoCode = PromoCode::findOrFail($id);
        $promoCode->is_active = false;
        $promoCode->save();
        
        return redirect()->back()->with('success', 'Promo code deactivated.');
    }
    
    public function usageLog($id)
    {
        if(!auth()->user()->hasPermissionTo('manage_promo_codes')) {
            abort(403);
        }
        
        $promoCode = PromoCode::findOrFail($id);
        $usage = $promoCode->usage()->with(['user', 'order'])->orderBy('used_at', 'desc')->paginate(20);
        
        return view('admin.promo-codes.usage', compact('promoCode', 'usage'));
    }
}