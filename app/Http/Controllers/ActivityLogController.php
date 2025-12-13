<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $query = ActivityLog::with('user')->latest();

        // Filter by action
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        // Filter by model type
        if ($request->filled('model_type')) {
            $query->where('model_type', $request->model_type);
        }

        // Filter by user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('user_name', 'like', "%{$search}%");
            });
        }

        $logs = $query->paginate(20)->withQueryString();

        // Get unique values for filters
        $actions = ActivityLog::distinct()->pluck('action');
        $modelTypes = ActivityLog::distinct()->pluck('model_type');
        $users = ActivityLog::distinct()->whereNotNull('user_id')
            ->select('user_id', 'user_name')
            ->get()
            ->unique('user_id');

        return view('pages.activity-log.index', compact('logs', 'actions', 'modelTypes', 'users'));
    }

    public function show(ActivityLog $activityLog)
    {
        return view('pages.activity-log.show', compact('activityLog'));
    }
}
