<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlockedIp;
use Illuminate\Http\Request;

class BlockedIpController extends Controller
{
    public function index(Request $request)
    {
        // Clean up expired blocks (older than 3 days)
        BlockedIp::where('is_permanent', false)
            ->where('blocked_until', '<', now())
            ->delete();

        $query = BlockedIp::with(['blockedByUser', 'attackAlert'])->latest();

        if ($request->filled('search')) {
            $query->where('ip_address', 'like', '%' . $request->search . '%');
        }

        $blockedIps = $query->paginate(20)->withQueryString();

        $stats = [
            'total' => BlockedIp::count(),
            'permanent' => BlockedIp::where('is_permanent', true)->count(),
            'temporary' => BlockedIp::where('is_permanent', false)->where('blocked_until', '>', now())->count(),
        ];

        return view('pages.admin.blocked-ips.index', compact('blockedIps', 'stats'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'ip_address' => 'required|ip',
            'reason' => 'nullable|string|max:255',
            'duration' => 'required|in:3days,permanent',
        ]);

        if (BlockedIp::isBlocked($request->ip_address)) {
            return back()->with('error', 'IP sudah diblokir.');
        }

        BlockedIp::create([
            'ip_address' => $request->ip_address,
            'reason' => $request->reason ?? 'Manually blocked by admin',
            'blocked_by' => auth()->id(),
            'is_permanent' => $request->duration === 'permanent',
            'blocked_until' => $request->duration === '3days' ? now()->addDays(3) : null,
        ]);

        return back()->with('success', "IP {$request->ip_address} berhasil diblokir.");
    }

    public function destroy(BlockedIp $blockedIp)
    {
        $ip = $blockedIp->ip_address;
        $blockedIp->delete();

        return back()->with('success', "IP {$ip} berhasil di-unblock.");
    }

    public function unblockAll()
    {
        BlockedIp::truncate();

        return back()->with('success', 'Semua IP berhasil di-unblock.');
    }
}
