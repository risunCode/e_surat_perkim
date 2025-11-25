@extends('layouts.app')
@section('title', 'Penggunaan Kode')
@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div>
        <a href="{{ route('admin.reference-codes.index') }}" class="inline-flex items-center gap-1 text-gray-400 hover:text-white mb-2"><i class="bx bx-arrow-back"></i> Kembali</a>
        <h1 class="text-2xl font-bold text-white">Penggunaan Kode: {{ $referenceCode->code }}</h1>
        <p class="text-gray-400">{{ $referenceCode->name }}</p>
    </div>
    <div class="bg-neutral-900 border border-neutral-800 rounded-xl overflow-hidden">
        <table class="w-full">
            <thead class="bg-neutral-800"><tr>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase">Pengguna</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase">Email</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase">Tanggal Penggunaan</th>
            </tr></thead>
            <tbody class="divide-y divide-neutral-800">
                @forelse($users as $user)
                <tr class="hover:bg-neutral-800/50">
                    <td class="px-4 py-3 text-white">{{ $user->name }}</td>
                    <td class="px-4 py-3 text-gray-400">{{ $user->email }}</td>
                    <td class="px-4 py-3 text-gray-400">{{ \Carbon\Carbon::parse($user->pivot->used_at)->format('d/m/Y H:i') }}</td>
                </tr>
                @empty<tr><td colspan="3" class="px-4 py-12 text-center text-gray-500">Belum ada yang menggunakan kode ini</td></tr>@endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
