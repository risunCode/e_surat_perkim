<?php

namespace App\Http\Controllers;

use App\Enums\LetterType;
use App\Models\Attachment;
use App\Models\Classification;
use App\Models\Letter;
use App\Services\NotificationService;
use App\Services\SignatureService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class IncomingLetterController extends Controller
{
    public function index(Request $request)
    {
        // Optimized eager loading with selective columns for list view
        $query = Letter::incoming()->with([
            'user:id,name',
            'classification:code,name',
            'attachments:id,letter_id,filename',
            'replies:id,reference_to',
            'dispositions:id,letter_id,letter_status'
        ]);

        // Hide completed letters by default (unless requested)
        if (!$request->filled('show_completed')) {
            $query->where('is_completed', false);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('reference_number', 'like', "%{$search}%")
                    ->orWhere('from', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('classification')) {
            $query->where('classification_code', $request->classification);
        }

        $letters = $this->paginateQuery($query->latest(), $request);
        $classifications = Classification::all();

        return view('pages.transaction.incoming.index', compact('letters', 'classifications'));
    }

    public function create()
    {
        $classifications = Classification::all();
        return view('pages.transaction.incoming.create', compact('classifications'));
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
            'from' => 'required|string|max:255',
            'letter_date' => 'required|date',
            'received_date' => 'required|date',
            'classification_code' => 'required|exists:classifications,code',
            'description' => 'nullable|string',
            'note' => 'nullable|string',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|mimes:pdf,doc,docx,ppt,pptx,txt,jpg,jpeg,png,gif|max:15360',
        ]);

        $validated['type'] = LetterType::INCOMING->value;
        $validated['user_id'] = Auth::id();
        unset($validated['attachments']);

        $letter = Letter::create($validated);

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->storeAs('attachments/incoming', $filename, 'public');

                Attachment::create([
                    'path' => 'attachments/incoming',
                    'filename' => $filename,
                    'extension' => $file->getClientOriginalExtension(),
                    'file_size' => $file->getSize(),
                    'mime_type' => $file->getMimeType(),
                    'letter_id' => $letter->id,
                    'user_id' => Auth::id(),
                ]);
            }
        }

        // Generate digital signature for document integrity
        app(SignatureService::class)->generateLetterSignature($letter);

        // Create notification for all users (bulk insert - optimized)
        app(NotificationService::class)->notifyIncomingLetter($letter->reference_number, $letter->id);

        return redirect()->route('incoming.index')
            ->with('success', 'Surat masuk berhasil ditambahkan.');
    }

    public function show(Letter $letter)
    {
        $letter->load(['user', 'classification', 'attachments', 'dispositions.status', 'dispositions.user', 'replies.user', 'latestSignature']);
        $statuses = \App\Models\LetterStatus::all();
        $correspondenceChain = $letter->getCorrespondenceChain();
        $signature = $letter->latestSignature;
        return view('pages.transaction.incoming.show', compact('letter', 'statuses', 'correspondenceChain', 'signature'));
    }

    public function edit(Letter $letter)
    {
        $this->authorizeEdit($letter);
        $classifications = Classification::all();
        return view('pages.transaction.incoming.edit', compact('letter', 'classifications'));
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
            'from' => 'required|string|max:255',
            'letter_date' => 'required|date',
            'received_date' => 'required|date',
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
                $file->storeAs('attachments/incoming', $filename, 'public');

                Attachment::create([
                    'path' => 'attachments/incoming',
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

        return redirect()->route('incoming.index')
            ->with('success', 'Surat masuk berhasil diperbarui.');
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

        return redirect()->route('incoming.index')
            ->with('success', 'Surat masuk berhasil dihapus.');
    }

    public function toggleCompletion(Letter $letter)
    {
        $this->authorizeEdit($letter);

        if (!$letter->isIncoming()) {
            return back()->withErrors(['error' => 'Hanya surat masuk yang bisa ditandai selesai.']);
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
