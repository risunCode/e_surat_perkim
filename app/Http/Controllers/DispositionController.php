<?php

namespace App\Http\Controllers;

use App\Models\Disposition;
use App\Models\Letter;
use App\Models\LetterStatus;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DispositionController extends Controller
{
    public function create(Letter $letter)
    {
        $statuses = LetterStatus::all();
        return view('pages.transaction.disposition.create', compact('letter', 'statuses'));
    }

    public function store(Request $request, Letter $letter)
    {
        $validated = $request->validate([
            'to' => 'required|string|max:255',
            'due_date' => 'required|date',
            'content' => 'required|string',
            'note' => 'nullable|string',
            'letter_status' => 'required|exists:letter_statuses,id',
        ]);

        $validated['letter_id'] = $letter->id;
        $validated['user_id'] = Auth::id();

        $disposition = Disposition::create($validated);

        // Create notification for all users (bulk insert - optimized)
        try {
            app(NotificationService::class)->notifyDisposition($letter->reference_number, $letter->id);
        } catch (\Exception $e) {
            \Log::error('Failed to create disposition notifications for letter ' . $letter->id . ': ' . $e->getMessage());
        }

        return redirect()->route('incoming.show', $letter->id)
            ->with('success', 'Disposisi berhasil ditambahkan.');
    }

    public function edit(Disposition $disposition)
    {
        $this->authorizeEdit($disposition);
        $statuses = LetterStatus::all();
        return view('pages.transaction.disposition.edit', compact('disposition', 'statuses'));
    }

    public function update(Request $request, Disposition $disposition)
    {
        $this->authorizeEdit($disposition);

        $validated = $request->validate([
            'to' => 'required|string|max:255',
            'due_date' => 'required|date',
            'content' => 'required|string',
            'note' => 'nullable|string',
            'letter_status' => 'required|exists:letter_statuses,id',
        ]);

        $disposition->update($validated);

        return redirect()->route('incoming.show', $disposition->letter_id)
            ->with('success', 'Disposisi berhasil diperbarui.');
    }

    public function destroy(Disposition $disposition)
    {
        $this->authorizeEdit($disposition);
        $letterId = $disposition->letter_id;
        $disposition->delete();

        return redirect()->route('incoming.show', $letterId)
            ->with('success', 'Disposisi berhasil dihapus.');
    }

    private function authorizeEdit(Disposition $disposition)
    {
        if (!Auth::user()->isAdmin() && $disposition->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses untuk mengubah disposisi ini.');
        }
    }
}
