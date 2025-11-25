@props(['id', 'title', 'size' => 'md'])

@php
$sizeClasses = [
    'sm' => 'max-w-md',
    'md' => 'max-w-xl',
    'lg' => 'max-w-3xl',
    'xl' => 'max-w-5xl',
    'full' => 'max-w-full mx-4',
];
@endphp

<!-- Backdrop -->
<div id="{{ $id }}-backdrop" class="modal-backdrop"></div>

<!-- Modal Content -->
<div id="{{ $id }}" class="modal-content w-full {{ $sizeClasses[$size] ?? $sizeClasses['md'] }}">
    <div class="bg-white dark:bg-neutral-900 border border-gray-200 dark:border-neutral-800 rounded-2xl shadow-2xl">
        <!-- Header -->
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 dark:border-neutral-800">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $title }}</h3>
            <button onclick="closeModal('{{ $id }}')" class="p-1 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-neutral-800">
                <i class="bx bx-x text-2xl"></i>
            </button>
        </div>
        
        <!-- Body -->
        <div class="p-6">
            {{ $slot }}
        </div>
        
        <!-- Footer (optional) -->
        @if(isset($footer))
        <div class="px-6 py-4 border-t border-gray-200 dark:border-neutral-800 flex justify-end gap-3">
            {{ $footer }}
        </div>
        @endif
    </div>
</div>
