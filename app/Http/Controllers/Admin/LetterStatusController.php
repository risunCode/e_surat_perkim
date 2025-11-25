<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LetterStatus;
use Illuminate\Http\Request;

class LetterStatusController extends Controller
{
    public function index(Request $request)
    {
        $query = LetterStatus::query();

        if ($request->filled('search')) {
            $query->where('status', 'like', "%{$request->search}%");
        }

        $statuses = $query->orderBy('id')->paginate(10);

        return view('pages.admin.status.index', compact('statuses'));
    }

    public function create()
    {
        return view('pages.admin.status.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'status' => 'required|string|max:255|unique:letter_statuses,status',
        ]);

        LetterStatus::create($validated);

        return redirect()->route('admin.statuses.index')
            ->with('success', 'Status berhasil ditambahkan.');
    }

    public function edit(LetterStatus $status)
    {
        return view('pages.admin.status.edit', compact('status'));
    }

    public function update(Request $request, LetterStatus $status)
    {
        $validated = $request->validate([
            'status' => 'required|string|max:255|unique:letter_statuses,status,' . $status->id,
        ]);

        $status->update($validated);

        return redirect()->route('admin.statuses.index')
            ->with('success', 'Status berhasil diperbarui.');
    }

    public function destroy(LetterStatus $status)
    {
        if ($status->dispositions()->exists()) {
            return back()->with('error', 'Status tidak dapat dihapus karena masih digunakan.');
        }

        $status->delete();

        return redirect()->route('admin.statuses.index')
            ->with('success', 'Status berhasil dihapus.');
    }
}
