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
            $query->where('status', $request->status);
        }

        $alerts = $query->paginate(20)->withQueryString();

        $stats = [
            'total' => AttackAlert::count(),
            'active' => AttackAlert::active()->count(),
            'mitigated' => AttackAlert::mitigated()->count(),
            'archived' => AttackAlert::archived()->count(),
            'today' => AttackAlert::whereDate('created_at', today())->count(),
            'critical' => AttackAlert::where('severity', 'critical')->active()->count(),
        ];

        return view('pages.admin.attack-alerts.index', compact('alerts', 'stats'));
    }

    public function show(AttackAlert $attackAlert)
    {
        $attackAlert->load(['user', 'dismissedByUser', 'mitigatedByUser', 'archivedByUser']);
        
        // Get related attacks from same IP
        $relatedAttacks = AttackAlert::where('ip_address', $attackAlert->ip_address)
            ->where('id', '!=', $attackAlert->id)
            ->latest()
            ->limit(10)
            ->get();

        return view('pages.admin.attack-alerts.show', compact('attackAlert', 'relatedAttacks'));
    }

    // Mark as mitigated (in progress)
    public function mitigate(Request $request, AttackAlert $attackAlert)
    {
        $request->validate([
            'notes' => 'nullable|string|max:1000',
        ]);

        $attackAlert->markAsMitigated(auth()->id(), $request->notes);

        return back()->with('success', 'Alert ditandai sebagai mitigated.');
    }

    // Archive single alert
    public function archive(AttackAlert $attackAlert)
    {
        $attackAlert->archive(auth()->id());

        return back()->with('success', 'Alert berhasil di-archive.');
    }

    // Delete single alert
    public function destroy(AttackAlert $attackAlert)
    {
        $attackAlert->delete();

        return back()->with('success', 'Alert berhasil dihapus.');
    }

    // Block IP address
    public function blockIp(AttackAlert $attackAlert)
    {
        $ip = $attackAlert->ip_address;
        
        // Check if already blocked
        if (\App\Models\BlockedIp::isBlocked($ip)) {
            return back()->with('error', "IP {$ip} sudah diblokir.");
        }
        
        // Create block record
        \App\Models\BlockedIp::create([
            'ip_address' => $ip,
            'reason' => "Blocked due to {$attackAlert->attack_type} attack",
            'blocked_by' => auth()->id(),
            'is_permanent' => true,
            'attack_alert_id' => $attackAlert->id,
        ]);
        
        // Archive the alert
        $attackAlert->archive(auth()->id());
        
        return back()->with('success', "IP {$ip} berhasil diblokir.");
    }

    // Legacy dismiss (now archives)
    public function dismiss(AttackAlert $attackAlert)
    {
        $attackAlert->archive(auth()->id());

        return back()->with('success', 'Alert berhasil di-archive.');
    }

    // Bulk actions
    public function mitigateAll(Request $request)
    {
        $request->validate([
            'notes' => 'nullable|string|max:1000',
        ]);

        AttackAlert::active()->update([
            'status' => AttackAlert::STATUS_MITIGATED,
            'mitigated_by' => auth()->id(),
            'mitigated_at' => now(),
            'mitigation_notes' => $request->notes,
        ]);

        return back()->with('success', 'Semua alert aktif ditandai sebagai mitigated.');
    }

    public function archiveAll()
    {
        AttackAlert::notArchived()->update([
            'status' => AttackAlert::STATUS_ARCHIVED,
            'archived_by' => auth()->id(),
            'archived_at' => now(),
            'is_dismissed' => true,
            'dismissed_by' => auth()->id(),
            'dismissed_at' => now(),
        ]);

        return back()->with('success', 'Semua alert berhasil di-archive.');
    }

    public function dismissAll()
    {
        // Legacy - now archives all
        return $this->archiveAll();
    }

    public function deleteAll()
    {
        AttackAlert::archived()->delete();

        return redirect()->route('admin.attack-alerts.index')->with('success', 'Semua alert archived berhasil dihapus.');
    }

    // API endpoint for checking active/mitigated alerts (used by frontend modal & console)
    public function getActiveAlerts(Request $request)
    {
        // Get recent alerts (active or mitigated, not archived)
        $query = AttackAlert::notArchived()
            ->recent(1) // Last 1 hour
            ->latest();
        
        // If since_id provided, only get newer alerts (for console polling)
        if ($request->filled('since_id')) {
            $query->where('id', '>', $request->since_id);
        }
        
        $alerts = $query->limit(20)->get();

        return response()->json([
            'count' => $alerts->count(),
            'active_count' => AttackAlert::active()->recent(1)->count(),
            'mitigated_count' => AttackAlert::mitigated()->recent(1)->count(),
            'alerts' => $alerts->map(fn($a) => [
                'id' => $a->id,
                'type' => $a->attack_type,
                'severity' => $a->severity,
                'status' => $a->status,
                'ip' => $a->ip_address,
                'url' => $a->target_url,
                'time' => $a->created_at->diffForHumans(),
                'timestamp' => $a->created_at->toIso8601String(),
                'user_agent' => $a->user_agent,
            ]),
        ]);
    }
}
