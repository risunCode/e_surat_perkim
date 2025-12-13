# Coding Standards E-Surat Perkim

## PHP / Laravel Standards

### Naming Conventions

| Type | Convention | Example |
|------|------------|---------|
| Class | PascalCase | `IncomingLetterController` |
| Method | camelCase | `getCorrespondenceChain()` |
| Variable | camelCase | `$letterDate` |
| Constant | UPPER_SNAKE | `MAX_FILE_SIZE` |
| Database Table | snake_case (plural) | `letter_statuses` |
| Database Column | snake_case | `reference_number` |
| Route Name | dot.notation | `incoming.store` |
| View File | kebab-case | `incoming-letter.blade.php` |
| Config Key | snake_case | `app_name` |

### Model Standards

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Letter extends Model
{
    // 1. Traits
    use HasFactory;

    // 2. Properties
    protected $fillable = [...];

    // 3. Casts
    protected function casts(): array
    {
        return [...];
    }

    // 4. Relationships (alphabetical)
    public function attachments(): HasMany
    {
        return $this->hasMany(Attachment::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // 5. Scopes
    public function scopeIncoming($query)
    {
        return $query->where('type', 'incoming');
    }

    // 6. Accessors & Mutators
    public function getFullNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    // 7. Helper Methods
    public function isIncoming(): bool
    {
        return $this->type === 'incoming';
    }
}
```

### Controller Standards

```php
<?php

namespace App\Http\Controllers;

use App\Models\Letter;
use Illuminate\Http\Request;

class IncomingLetterController extends Controller
{
    // 1. CRUD methods in order: index, create, store, show, edit, update, destroy
    
    public function index(Request $request)
    {
        // Query building
        $query = Letter::incoming()->with([...]);
        
        // Filtering
        if ($request->filled('search')) {
            $query->where(...);
        }
        
        // Pagination
        $letters = $query->latest()->paginate(15);
        
        return view('pages.transaction.incoming.index', compact('letters'));
    }

    public function store(Request $request)
    {
        // 1. Validation
        $validated = $request->validate([...]);
        
        // 2. Create
        $letter = Letter::create($validated);
        
        // 3. Side effects (attachments, notifications, etc.)
        
        // 4. Redirect with message
        return redirect()->route('incoming.index')
            ->with('success', 'Surat berhasil ditambahkan.');
    }

    // 2. Custom methods after CRUD
    public function toggleCompletion(Letter $letter)
    {
        // ...
    }

    // 3. Private helper methods at the end
    private function authorizeEdit(Letter $letter)
    {
        // ...
    }
}
```

### Service Standards

```php
<?php

namespace App\Services;

class SignatureService
{
    /**
     * Generate digital signature for letter.
     * 
     * @param Letter $letter
     * @param int|null $userId
     * @return DocumentSignature
     */
    public function generateLetterSignature(Letter $letter, ?int $userId = null): DocumentSignature
    {
        // Implementation
    }
}
```

---

## Blade / Frontend Standards

### View Structure
```blade
{{-- 1. Extends layout --}}
@extends('layouts.app')

{{-- 2. Section title --}}
@section('title', 'Surat Masuk')

{{-- 3. Main content --}}
@section('content')
    <div class="container">
        {{-- Content here --}}
    </div>
@endsection

{{-- 4. Scripts (if needed) --}}
@push('scripts')
    <script>
        // JavaScript here
    </script>
@endpush
```

### Component Usage
```blade
{{-- Use components for reusable UI --}}
<x-navbar />
<x-sidebar />
<x-modal id="delete-modal" title="Konfirmasi">
    {{-- Content --}}
</x-modal>
```

### Form Standards
```blade
<form method="POST" action="{{ route('incoming.store') }}" enctype="multipart/form-data">
    @csrf
    
    {{-- Input with error handling --}}
    <div class="mb-4">
        <label for="reference_number" class="block text-sm font-medium">
            Nomor Surat <span class="text-red-500">*</span>
        </label>
        <input type="text" 
               name="reference_number" 
               id="reference_number"
               value="{{ old('reference_number', $letter->reference_number ?? '') }}"
               class="mt-1 block w-full rounded-md border-gray-300 
                      @error('reference_number') border-red-500 @enderror"
               required>
        @error('reference_number')
            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
        @enderror
    </div>
    
    <button type="submit" class="btn btn-primary">
        Simpan
    </button>
</form>
```

### TailwindCSS Classes
```blade
{{-- Use consistent spacing --}}
<div class="p-4 mb-4">          {{-- padding & margin --}}
<div class="space-y-4">          {{-- vertical spacing --}}
<div class="gap-4">              {{-- grid/flex gap --}}

{{-- Use consistent colors --}}
<button class="bg-blue-600 hover:bg-blue-700 text-white">
<span class="text-red-500">*</span>
<div class="border-gray-300">

{{-- Use responsive classes --}}
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3">
```

---

## JavaScript Standards

### Async/Await Pattern
```javascript
async function fetchData() {
    try {
        const response = await fetch('/api/data', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(data)
        });
        
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        
        const result = await response.json();
        return result;
    } catch (error) {
        console.error('Error:', error);
        throw error;
    }
}
```

### Event Handling
```javascript
// Use event delegation for dynamic elements
document.addEventListener('click', (e) => {
    if (e.target.matches('.delete-btn')) {
        handleDelete(e.target.dataset.id);
    }
});

// Or direct binding for static elements
document.getElementById('submit-btn').addEventListener('click', handleSubmit);
```

---

## Database Standards

### Migration Naming
```
YYYY_MM_DD_HHMMSS_action_table_name.php

Examples:
2024_01_01_000001_create_letters_table.php
2024_01_01_000002_add_status_to_letters_table.php
2024_01_01_000003_create_letter_user_pivot_table.php
```

### Migration Structure
```php
public function up(): void
{
    Schema::create('letters', function (Blueprint $table) {
        // 1. Primary key
        $table->id();
        
        // 2. Foreign keys
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        
        // 3. Data columns (alphabetical or logical grouping)
        $table->string('reference_number')->unique();
        $table->text('description')->nullable();
        $table->date('letter_date');
        
        // 4. Status/flag columns
        $table->boolean('is_completed')->default(false);
        
        // 5. Timestamps
        $table->timestamps();
        
        // 6. Indexes
        $table->index('reference_number');
    });
}
```

---

## Error Handling

### Controller Error Handling
```php
public function store(Request $request)
{
    try {
        $letter = Letter::create($validated);
        
        // Side effects with try-catch
        try {
            app(SignatureService::class)->generateLetterSignature($letter);
        } catch (\Exception $e) {
            \Log::error('Signature generation failed: ' . $e->getMessage());
            // Continue - signature is not critical
        }
        
        return redirect()->route('incoming.index')
            ->with('success', 'Surat berhasil ditambahkan.');
            
    } catch (\Exception $e) {
        \Log::error('Letter creation failed: ' . $e->getMessage());
        return back()->withErrors(['error' => 'Gagal menyimpan surat.'])->withInput();
    }
}
```

### Validation Error Messages (Indonesian)
```php
$request->validate([
    'reference_number' => 'required|unique:letters',
    'letter_date' => 'required|date',
], [
    'reference_number.required' => 'Nomor surat wajib diisi.',
    'reference_number.unique' => 'Nomor surat sudah digunakan.',
    'letter_date.required' => 'Tanggal surat wajib diisi.',
    'letter_date.date' => 'Format tanggal tidak valid.',
]);
```

---

## Comments & Documentation

### PHPDoc
```php
/**
 * Generate digital signature for letter.
 * Invalidates any existing signatures for this letter.
 *
 * @param Letter $letter The letter to sign
 * @param int|null $userId User ID (defaults to authenticated user)
 * @return DocumentSignature The generated signature
 * @throws \Exception If SIGNATURE_KEY is not configured
 */
public function generateLetterSignature(Letter $letter, ?int $userId = null): DocumentSignature
```

### Inline Comments
```php
// Only for complex logic that isn't self-explanatory
$chain = collect();

// Get the root letter (original incoming letter)
$root = $this;
while ($root->referenceTo) {
    $root = $root->referenceTo;
}

// Build chain from root recursively
$this->buildChain($root, $chain);
```
