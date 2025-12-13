<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AttackAlert;
use Illuminate\Http\Request;

class AttackAlertController extends Controller
{
    public function index(Request $request)
    {
        $query = AttackAlert::with('user')->latest();

        if ($request->filled('type')) {
            $query->where('attack_type', $request->type);
        }

        if ($request->filled('severity')) {
            $query->where('severity', $request->severity);
        }

        if ($request->filled('status')) {
            $query->where('is_dismissed', $request->status === 'dismissed');
        }

        $alerts = $query->paginate(20);

        $stats = [
            'total' => AttackAlert::count(),
            'active' => AttackAlert::active()->count(),
            'today' => AttackAlert::whereDate('created_at', today())->count(),
            'critical' => AttackAlert::where('severity', 'critical')->active()->count(),
        ];

        return view('pages.admin.attack-alerts.index', compact('alerts', 'stats'));
    }

    public function show(AttackAlert $attackAlert)
    {
        $attackAlert->load(['user', 'dismissedByUser']);
        
        // Get related attacks from same IP
        $relatedAttacks = AttackAlert::where('ip_address', $attackAlert->ip_address)
            ->where('id', '!=', $attackAlert->id)
            ->latest()
            ->limit(10)
            ->get();

        return view('pages.admin.attack-alerts.show', compact('attackAlert', 'relatedAttacks'));
    }

    public function dismiss(AttackAlert $attackAlert)
    {
        $attackAlert->dismiss(auth()->id());

        return back()->with('success', 'Alert berhasil di-dismiss.');
    }

    public function dismissAll()
    {
        AttackAlert::active()->update([
            'is_dismissed' => true,
            'dismissed_by' => auth()->id(),
            'dismissed_at' => now(),
        ]);

        return back()->with('success', 'Semua alert berhasil di-dismiss.');
    }

    // API endpoint for checking active alerts (used by frontend modal)
    public function getActiveAlerts()
    {
        $alerts = AttackAlert::active()
            ->recent(1) // Last 1 hour
            ->latest()
            ->limit(5)
            ->get();

        return response()->json([
            'count' => $alerts->count(),
            'alerts' => $alerts->map(fn($a) => [
                'id' => $a->id,
                'type' => $a->attack_type,
                'severity' => $a->severity,
                'ip' => $a->ip_address,
                'url' => $a->target_url,
                'time' => $a->created_at->diffForHumans(),
                'user_agent' => $a->user_agent,
            ]),
        ]);
    }
}
