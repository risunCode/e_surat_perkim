<?php

namespace App\Http\Controllers;

use App\Enums\LetterType;
use App\Models\Disposition;
use App\Models\Letter;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $today = now()->toDateString();
        
        $stats = [
            'incoming_today' => Letter::incoming()->whereDate('created_at', $today)->count(),
            'outgoing_today' => Letter::outgoing()->whereDate('created_at', $today)->count(),
            'disposition_today' => Disposition::whereDate('created_at', $today)->count(),
            'active_users' => User::where('is_active', true)->count(),
        ];

        $stats['incoming_change'] = $this->calculateChange(Letter::incoming(), 'incoming');
        $stats['outgoing_change'] = $this->calculateChange(Letter::outgoing(), 'outgoing');
        $stats['disposition_change'] = $this->calculateDispositionChange();

        // Chart data for the last 7 days
        $chartData = $this->getChartData();

        return view('pages.dashboard', compact('stats', 'chartData'));
    }

    private function calculateChange($query, $type)
    {
        $today = now()->toDateString();
        $yesterday = now()->subDay()->toDateString();
        
        $todayCount = (clone $query)->whereDate('created_at', $today)->count();
        $yesterdayCount = (clone $query)->whereDate('created_at', $yesterday)->count();
        
        if ($yesterdayCount == 0) {
            return $todayCount > 0 ? 100 : 0;
        }
        
        return round((($todayCount - $yesterdayCount) / $yesterdayCount) * 100);
    }

    private function calculateDispositionChange()
    {
        $today = now()->toDateString();
        $yesterday = now()->subDay()->toDateString();
        
        $todayCount = Disposition::whereDate('created_at', $today)->count();
        $yesterdayCount = Disposition::whereDate('created_at', $yesterday)->count();
        
        if ($yesterdayCount == 0) {
            return $todayCount > 0 ? 100 : 0;
        }
        
        return round((($todayCount - $yesterdayCount) / $yesterdayCount) * 100);
    }

    private function getChartData()
    {
        $dates = [];
        $incoming = [];
        $outgoing = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->toDateString();
            $dates[] = now()->subDays($i)->format('d M');
            $incoming[] = Letter::incoming()->whereDate('created_at', $date)->count();
            $outgoing[] = Letter::outgoing()->whereDate('created_at', $date)->count();
        }

        return [
            'labels' => $dates,
            'incoming' => $incoming,
            'outgoing' => $outgoing,
        ];
    }
}
