# Panduan Controllers E-Surat Perkim

## Daftar Controllers

### Main Controllers (`app/Http/Controllers/`)

| Controller | Fungsi |
|------------|--------|
| `DashboardController` | Halaman dashboard utama |
| `IncomingLetterController` | CRUD surat masuk |
| `OutgoingLetterController` | CRUD surat keluar |
| `DispositionController` | CRUD disposisi |
| `AgendaController` | Laporan agenda & cetak PDF |
| `GalleryController` | Galeri lampiran |
| `NotificationController` | Manajemen notifikasi |
| `ProfileController` | Profil user |
| `AttachmentController` | Serve & download file |
| `TranscriptController` | Print view surat |
| `DocumentVerificationController` | Verifikasi dokumen publik |
| `SecurityQuestionController` | Reset password & security setup |
| `ActivityLogController` | Histori aktivitas user |

### Admin Controllers (`app/Http/Controllers/Admin/`)

| Controller | Fungsi |
|------------|--------|
| `UserController` | Manajemen user |
| `ClassificationController` | Manajemen klasifikasi surat |
| `LetterStatusController` | Manajemen status disposisi |
| `ReferenceCodeController` | Manajemen kode referensi |
| `SettingController` | Pengaturan aplikasi |

---

## Pola Controller

### Standard CRUD Pattern

```php
class IncomingLetterController extends Controller
{
    // List dengan pagination & filter
    public function index(Request $request)
    {
        $query = Letter::incoming()->with([...]);
        
        // Filter
        if ($request->filled('search')) {
            $query->where(...);
        }
        
        $letters = $this->paginateQuery($query->latest(), $request);
        return view('pages.transaction.incoming.index', compact('letters'));
    }

    // Form create
    public function create()
    {
        $classifications = Classification::all();
        return view('pages.transaction.incoming.create', compact('classifications'));
    }

    // Store dengan validasi
    public function store(Request $request)
    {
        $validated = $request->validate([...]);
        $letter = Letter::create($validated);
        
        // Handle attachments
        // Generate signature
        // Create notifications
        
        return redirect()->route('incoming.index')
            ->with('success', 'Surat berhasil ditambahkan.');
    }

    // Detail view
    public function show(Letter $letter)
    {
        $letter->load([...]);
        return view('pages.transaction.incoming.show', compact('letter'));
    }

    // Form edit
    public function edit(Letter $letter)
    {
        $this->authorizeEdit($letter);
        return view('pages.transaction.incoming.edit', compact('letter'));
    }

    // Update
    public function update(Request $request, Letter $letter)
    {
        $this->authorizeEdit($letter);
        $validated = $request->validate([...]);
        $letter->update($validated);
        
        return redirect()->route('incoming.index')
            ->with('success', 'Surat berhasil diperbarui.');
    }

    // Delete
    public function destroy(Letter $letter)
    {
        $this->authorizeEdit($letter);
        $letter->delete();
        
        return redirect()->route('incoming.index')
            ->with('success', 'Surat berhasil dihapus.');
    }
}
```

### Authorization Pattern

```php
private function authorizeEdit(Letter $letter)
{
    // Hanya admin yang bisa edit/delete surat
    if (!Auth::user()->isAdmin()) {
        abort(403, 'Hanya administrator yang dapat mengubah atau menghapus surat.');
    }
}
```

### File Upload Pattern

```php
public function store(Request $request)
{
    // 1. Validasi total size
    if ($request->hasFile('attachments')) {
        $totalSize = 0;
        foreach ($request->file('attachments') as $file) {
            $totalSize += $file->getSize();
        }
        if ($totalSize > 15 * 1024 * 1024) {
            return back()->withErrors(['attachments' => 'Max 15MB.'])->withInput();
        }
    }

    // 2. Validasi individual files
    $validated = $request->validate([
        'attachments' => 'nullable|array',
        'attachments.*' => 'file|mimes:pdf,doc,docx,ppt,pptx,txt,jpg,jpeg,png,gif|max:15360',
    ]);

    // 3. Store files
    if ($request->hasFile('attachments')) {
        foreach ($request->file('attachments') as $file) {
            $filename = \Str::uuid() . '.' . $file->getClientOriginalExtension();
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
}
```

### Service Integration Pattern

```php
public function store(Request $request)
{
    $letter = Letter::create($validated);

    // Generate digital signature
    try {
        app(SignatureService::class)->generateLetterSignature($letter);
    } catch (\Exception $e) {
        \Log::error('Failed to generate signature: ' . $e->getMessage());
    }

    // Create notifications (bulk insert)
    try {
        app(NotificationService::class)->notifyIncomingLetter(
            $letter->reference_number, 
            $letter->id
        );
    } catch (\Exception $e) {
        \Log::error('Failed to create notifications: ' . $e->getMessage());
    }
}
```

---

## Controller Khusus

### NotificationController

```php
// Get unread notifications (AJAX)
public function getUnread()
{
    $notifications = Auth::user()
        ->userNotifications()
        ->unread()
        ->latest()
        ->take(10)
        ->get();

    return response()->json([
        'count' => Auth::user()->userNotifications()->unread()->count(),
        'notifications' => $notifications
    ]);
}

// Mark as read
public function markAsRead(Notification $notification)
{
    $notification->markAsRead();
    return response()->json(['success' => true]);
}
```

### DocumentVerificationController

```php
// Public verification (no auth required)
public function verify(string $hash)
{
    $signature = DocumentSignature::verifySignature($hash);
    
    if (!$signature) {
        return view('pages.verification.result', [
            'valid' => false,
            'message' => 'Dokumen tidak ditemukan atau tidak valid.'
        ]);
    }

    return view('pages.verification.result', [
        'valid' => true,
        'signature' => $signature,
        'letter' => $signature->letter
    ]);
}
```

### ProfileController

```php
// Update photo dengan Cropper.js
public function updatePhoto(Request $request)
{
    $request->validate([
        'photo' => 'required|image|max:2048'
    ]);

    // Delete old photo
    if (Auth::user()->profile_picture) {
        Storage::disk('public')->delete('profile-pictures/' . Auth::user()->profile_picture);
    }

    // Store new photo
    $filename = Auth::id() . '_' . time() . '.' . $request->file('photo')->extension();
    $request->file('photo')->storeAs('profile-pictures', $filename, 'public');

    Auth::user()->update(['profile_picture' => $filename]);

    return back()->with('success', 'Foto profil berhasil diperbarui.');
}
```

---

## Validation Rules

### Surat Masuk
```php
[
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
]
```

### Surat Keluar
```php
[
    'reference_number' => 'required|unique:letters,reference_number',
    'agenda_number' => 'nullable|string',
    'to' => 'required|string|max:255',
    'letter_date' => 'required|date',
    'classification_code' => 'required|exists:classifications,code',
    'description' => 'nullable|string',
    'note' => 'nullable|string',
    'reference_to' => 'nullable|exists:letters,id',  // Reply to incoming
    'attachments' => 'nullable|array',
    'attachments.*' => 'file|mimes:pdf,doc,docx,ppt,pptx,txt,jpg,jpeg,png,gif|max:15360',
]
```

### Disposisi
```php
[
    'to' => 'required|string|max:255',
    'due_date' => 'required|date|after_or_equal:today',
    'content' => 'required|string',
    'note' => 'nullable|string',
    'letter_status' => 'required|exists:letter_statuses,id',
]
```
