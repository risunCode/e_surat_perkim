<?php

namespace App\Http\Controllers;

use App\Enums\LetterType;
use App\Enums\NotificationType;
use App\Models\Attachment;
use App\Models\Classification;
use App\Models\Letter;
use App\Models\Notification;
use App\Models\User;
use App\Services\SignatureService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class OutgoingLetterController extends Controller
{
    public function index(Request $request)
    {
        $query = Letter::outgoing()->with(['user', 'classification', 'attachments', 'referenceTo']);

        // Hide completed letters by default (unless requested)
        if (!$request->filled('show_completed')) {
            $query->where('is_completed', false);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('reference_number', 'like', "%{$search}%")
                    ->orWhere('to', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('classification')) {
            $query->where('classification_code', $request->classification);
        }


        if ($request->filled('date_from')) {
            $query->whereDate('letter_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('letter_date', '<=', $request->date_to);
        }

        $letters = $this->paginateQuery($query->latest(), $request);
        $classifications = Classification::all();

        return view('pages.transaction.outgoing.index', compact('letters', 'classifications'));
    }

    public function create(Request $request)
    {
        $classifications = Classification::all();
        $replyTo = null;
        
        // If replying to an incoming letter
        if ($request->has('reply_to')) {
            $replyTo = Letter::with(['user', 'classification'])->find($request->reply_to);
        }
        
        return view('pages.transaction.outgoing.create', compact('classifications', 'replyTo'));
    }

    public function store(Request $request)
    {
        // Validate total file size (max 15MB for all files combined)
        if ($request->hasFile('attachments')) {
            $totalSize = 0;
            foreach ($request->file('attachments') as $file) {
                $totalSize += $file->getSize();
            }
            if ($totalSize > 15 * 1024 * 1024) {
                return back()->withErrors(['attachments' => 'Total ukuran semua file tidak boleh lebih dari 15MB.'])->withInput();
            }
        }

        $validated = $request->validate([
            'reference_number' => 'required|unique:letters,reference_number',
            'agenda_number' => 'nullable|string',
            'to' => 'required|string|max:255',
            'letter_date' => 'required|date',
            'classification_code' => 'required|exists:classifications,code',
            'description' => 'nullable|string',
            'note' => 'nullable|string',
            'reference_to' => 'nullable|exists:letters,id',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|mimes:pdf,doc,docx,ppt,pptx,txt,jpg,jpeg,png,gif|max:15360',
        ]);

        $validated['type'] = LetterType::OUTGOING->value;
        $validated['user_id'] = Auth::id();
        unset($validated['attachments']);

        $letter = Letter::create($validated);

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->storeAs('attachments/outgoing', $filename, 'public');

                Attachment::create([
                    'path' => 'attachments/outgoing',
                    'filename' => $filename,
                    'extension' => $file->getClientOriginalExtension(),
                    'file_size' => $file->getSize(),
                    'mime_type' => $file->getMimeType(),
                    'letter_id' => $letter->id,
                    'user_id' => Auth::id(),
                ]);
            }
        }

        // Auto-complete dispositions if this is a reply
        if ($letter->reference_to) {
            $originalLetter = Letter::find($letter->reference_to);
            if ($originalLetter && $originalLetter->isIncoming()) {
                // Mark all pending dispositions as completed
                $originalLetter->dispositions()
                    ->where('letter_status', '!=', 3)  // 3 = completed status ID
                    ->update(['letter_status' => 3]);
            }
        }

        // Generate digital signature for document integrity
        app(SignatureService::class)->generateLetterSignature($letter);

        // Create notification for all users
        $users = User::where('is_active', true)->get();
        foreach ($users as $user) {
            Notification::create([
                'user_id' => $user->id,
                'type' => NotificationType::OUTGOING->value,
                'title' => 'Surat Keluar Baru',
                'message' => "Surat keluar baru dengan nomor {$letter->reference_number}",
                'link' => route('outgoing.show', $letter->id),
                'icon' => NotificationType::OUTGOING->icon(),
            ]);
        }

        return redirect()->route('outgoing.index')
            ->with('success', 'Surat keluar berhasil ditambahkan.');
    }

    public function show(Letter $letter)
    {
        $letter->load(['user', 'classification', 'attachments', 'referenceTo', 'replies.user', 'latestSignature']);
        $correspondenceChain = $letter->getCorrespondenceChain();
        $signature = $letter->latestSignature;
        return view('pages.transaction.outgoing.show', compact('letter', 'correspondenceChain', 'signature'));
    }

    public function edit(Letter $letter)
    {
        $this->authorizeEdit($letter);
        $classifications = Classification::all();
        return view('pages.transaction.outgoing.edit', compact('letter', 'classifications'));
    }

    public function update(Request $request, Letter $letter)
    {
        $this->authorizeEdit($letter);

        // Validate total file size (max 15MB for all files combined)
        if ($request->hasFile('attachments')) {
            $totalSize = 0;
            foreach ($request->file('attachments') as $file) {
                $totalSize += $file->getSize();
            }
            if ($totalSize > 15 * 1024 * 1024) {
                return back()->withErrors(['attachments' => 'Total ukuran semua file tidak boleh lebih dari 15MB.'])->withInput();
            }
        }

        $validated = $request->validate([
            'reference_number' => 'required|unique:letters,reference_number,' . $letter->id,
            'agenda_number' => 'nullable|string',
            'to' => 'required|string|max:255',
            'letter_date' => 'required|date',
            'classification_code' => 'required|exists:classifications,code',
            'description' => 'nullable|string',
            'note' => 'nullable|string',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|mimes:pdf,doc,docx,ppt,pptx,txt,jpg,jpeg,png,gif|max:15360',
        ]);

        unset($validated['attachments']);
        $letter->update($validated);

        if ($request->hasFile('attachments')) {
            // Delete old attachments
            foreach ($letter->attachments as $attachment) {
                Storage::disk('public')->delete($attachment->full_path);
                $attachment->delete();
            }

            foreach ($request->file('attachments') as $file) {
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->storeAs('attachments/outgoing', $filename, 'public');

                Attachment::create([
                    'path' => 'attachments/outgoing',
                    'filename' => $filename,
                    'extension' => $file->getClientOriginalExtension(),
                    'file_size' => $file->getSize(),
                    'mime_type' => $file->getMimeType(),
                    'letter_id' => $letter->id,
                    'user_id' => Auth::id(),
                ]);
            }
        }

        // Regenerate digital signature after update
        app(SignatureService::class)->generateLetterSignature($letter);

        return redirect()->route('outgoing.index')
            ->with('success', 'Surat keluar berhasil diperbarui.');
    }

    public function destroy(Letter $letter)
    {
        $this->authorizeEdit($letter);

        // Delete attachments
        foreach ($letter->attachments as $attachment) {
            Storage::disk('public')->delete($attachment->full_path);
            $attachment->delete();
        }

        $letter->delete();

        return redirect()->route('outgoing.index')
            ->with('success', 'Surat keluar berhasil dihapus.');
    }


    public function toggleCompletion(Letter $letter)
    {
        $this->authorizeEdit($letter);

        if (!$letter->isOutgoing()) {
            return back()->withErrors(['error' => 'Hanya surat keluar yang bisa ditandai selesai.']);
        }

        if ($letter->is_completed) {
            $letter->markAsIncomplete();
            $message = 'Surat ditandai belum selesai.';
        } else {
            $letter->markAsCompleted();
            $message = 'Surat ditandai selesai!';
        }

        return back()->with('success', $message);
    }

    private function authorizeEdit(Letter $letter)
    {
        if (!Auth::user()->isAdmin() && $letter->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses untuk mengubah surat ini.');
        }
    }
}
