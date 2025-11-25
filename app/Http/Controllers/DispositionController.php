<?php

namespace App\Http\Controllers;

use App\Enums\NotificationType;
use App\Models\Disposition;
use App\Models\Letter;
use App\Models\LetterStatus;
use App\Models\Notification;
use App\Models\User;
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

        // Create notification for all users
        $users = User::where('is_active', true)->get();
        foreach ($users as $user) {
            Notification::create([
                'user_id' => $user->id,
                'type' => NotificationType::DISPOSITION->value,
                'title' => 'Disposisi Baru',
                'message' => "Disposisi baru untuk surat {$letter->reference_number}",
                'link' => route('incoming.show', $letter->id),
                'icon' => NotificationType::DISPOSITION->icon(),
            ]);
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
