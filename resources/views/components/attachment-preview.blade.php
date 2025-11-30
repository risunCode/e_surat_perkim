@props(['attachments'])

@if($attachments->count() > 0)
    @php
        $first = $attachments->first();
        $remainingCount = $attachments->count() - 1;
        $galleryFiles = $attachments->map(fn($att) => [
            'url' => route('attachment.serve', $att->id),
            'name' => $att->filename
        ])->toJson();
    @endphp
    
    <div class="flex items-center gap-1">
        @if(in_array(strtolower($first->extension), ['jpg','jpeg','png','gif','webp']))
            {{-- Image thumbnail with gallery --}}
            <img 
                src="{{ route('attachment.serve', $first->id) }}" 
                alt="{{ $first->filename }}"
                class="w-8 h-8 rounded object-cover cursor-pointer hover:opacity-80 transition-opacity hover:ring-2 hover:ring-blue-400"
                title="Klik untuk lihat galeri"
                onclick="openGallery({{ $galleryFiles }}, 0)"
            >
        @else
            {{-- File icon with extension --}}
            <div 
                class="w-8 h-8 rounded flex items-center justify-center text-xs font-medium cursor-pointer hover:opacity-80 transition-opacity {{ strtolower($first->extension) == 'pdf' ? 'bg-red-100 text-red-600' : 'bg-blue-100 text-blue-600' }}"
                title="Klik untuk lihat galeri"
                onclick="openGallery({{ $galleryFiles }}, 0)"
            >
                {{ strtoupper(substr($first->extension, 0, 3)) }}
            </div>
        @endif
        
        {{-- Remaining files count with gallery click --}}
        @if($remainingCount > 0)
            <span 
                class="text-xs px-1.5 py-0.5 rounded cursor-pointer hover:opacity-80" 
                style="background-color: var(--bg-input); color: var(--text-secondary);"
                onclick="openGallery({{ $galleryFiles }}, 1)"
                title="Lihat {{ $remainingCount }} file lainnya"
            >+{{ $remainingCount }}</span>
        @endif
    </div>
@else
    <span style="color: var(--text-secondary);">-</span>
@endif
