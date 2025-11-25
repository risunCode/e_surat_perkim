<?php

namespace App\Http\Controllers;

use App\Enums\LetterType;
use App\Models\Letter;
use Illuminate\Http\Request;

class AgendaController extends Controller
{
    public function incoming(Request $request)
    {
        $query = Letter::incoming()->with(['user', 'classification']);

        if ($request->filled('month')) {
            $query->whereMonth('letter_date', $request->month);
        }

        if ($request->filled('year')) {
            $query->whereYear('letter_date', $request->year);
        }

        $letters = $query->orderBy('letter_date', 'desc')->paginate(15);

        return view('pages.agenda.incoming', compact('letters'));
    }

    public function outgoing(Request $request)
    {
        $query = Letter::outgoing()->with(['user', 'classification']);

        if ($request->filled('month')) {
            $query->whereMonth('letter_date', $request->month);
        }

        if ($request->filled('year')) {
            $query->whereYear('letter_date', $request->year);
        }

        $letters = $query->orderBy('letter_date', 'desc')->paginate(15);

        return view('pages.agenda.outgoing', compact('letters'));
    }

    public function printIncoming(Request $request)
    {
        $query = Letter::incoming()->with(['user', 'classification']);

        if ($request->filled('month')) {
            $query->whereMonth('letter_date', $request->month);
        }

        if ($request->filled('year')) {
            $query->whereYear('letter_date', $request->year);
        }

        $letters = $query->orderBy('letter_date', 'desc')->get();

        return view('pages.agenda.print-incoming', compact('letters'));
    }

    public function printOutgoing(Request $request)
    {
        $query = Letter::outgoing()->with(['user', 'classification']);

        if ($request->filled('month')) {
            $query->whereMonth('letter_date', $request->month);
        }

        if ($request->filled('year')) {
            $query->whereYear('letter_date', $request->year);
        }

        $letters = $query->orderBy('letter_date', 'desc')->get();

        return view('pages.agenda.print-outgoing', compact('letters'));
    }
}
