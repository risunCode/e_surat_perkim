<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

abstract class Controller
{
    /**
     * Maximum records allowed for "show all" option.
     * Prevents memory exhaustion on large datasets.
     */
    protected const MAX_SHOW_ALL = 1000;

    /**
     * Handle pagination with per_page options
     * Supports: 10, 25, 50, 100, or 'all' (with safety limit)
     */
    protected function paginateQuery($query, Request $request, $defaultPerPage = 10)
    {
        $perPage = $request->input('per_page', $defaultPerPage);
        
        // Handle "show all" option with safety limit
        if ($perPage === 'all') {
            // Check total count first to prevent memory issues
            $totalCount = (clone $query)->count();
            
            // If too many records, force pagination
            if ($totalCount > self::MAX_SHOW_ALL) {
                return $query->paginate(100)->withQueryString();
            }
            
            $items = $query->get();
            
            // Create a mock paginator for consistency
            return (object) [
                'items' => $items,
                'totalCount' => $totalCount,
                'showAll' => true
            ];
        }
        
        // Validate and sanitize per_page value
        $perPage = in_array($perPage, [10, 25, 50, 100]) ? (int) $perPage : $defaultPerPage;
        
        return $query->paginate($perPage)->withQueryString();
    }
}
