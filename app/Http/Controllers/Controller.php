<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

abstract class Controller
{
    /**
     * Handle pagination with per_page options
     * Supports: 10, 25, 50, 100, or 'all'
     */
    protected function paginateQuery($query, Request $request, $defaultPerPage = 10)
    {
        $perPage = $request->input('per_page', $defaultPerPage);
        
        // Handle "show all" option
        if ($perPage === 'all') {
            $items = $query->get();
            $totalCount = $items->count();
            
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
