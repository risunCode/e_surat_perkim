<?php

namespace App\Http\Controllers;

use App\Models\Letter;
use App\Models\Attachment;
use App\Models\Classification;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    public function incoming(Request $request)
    {
        $query = Letter::incoming()
            ->with(['attachments', 'user', 'classification'])
            ->whereHas('attachments');

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('reference_number', 'like', "%{$search}%")
                  ->orWhere('from', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by classification
        if ($request->filled('classification')) {
            $query->where('classification_code', $request->classification);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('letter_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('letter_date', '<=', $request->date_to);
        }

        // Stats
        $totalFiles = Attachment::whereHas('letter', fn($q) => $q->incoming())->count();

        $letters = $query->latest()->paginate(12)->withQueryString();
        $classifications = Classification::orderBy('code')->get();

        return view('pages.gallery.incoming', compact('letters', 'classifications', 'totalFiles'));
    }

    public function outgoing(Request $request)
    {
        $query = Letter::outgoing()
            ->with(['attachments', 'user', 'classification'])
            ->whereHas('attachments');

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('reference_number', 'like', "%{$search}%")
                  ->orWhere('to', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by classification
        if ($request->filled('classification')) {
            $query->where('classification_code', $request->classification);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('letter_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('letter_date', '<=', $request->date_to);
        }

        // Stats
        $totalFiles = Attachment::whereHas('letter', fn($q) => $q->outgoing())->count();

        $letters = $query->latest()->paginate(12)->withQueryString();
        $classifications = Classification::orderBy('code')->get();

        return view('pages.gallery.outgoing', compact('letters', 'classifications', 'totalFiles'));
    }
}
