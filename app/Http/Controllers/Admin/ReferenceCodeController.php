<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Role;
use App\Http\Controllers\Controller;
use App\Models\ReferenceCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ReferenceCodeController extends Controller
{
    public function index(Request $request)
    {
        $query = ReferenceCode::with(['creator', 'users']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $codes = $this->paginateQuery($query->latest(), $request);
        $roles = Role::options();

        return view('pages.admin.reference-code.index', compact('codes', 'roles'));
    }

    public function create()
    {
        $roles = Role::options();
        return view('pages.admin.reference-code.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|unique:reference_codes,code',
            'max_usage' => 'required|integer|min:0',
            'role' => 'required|in:admin,staff',
            'expired_at' => 'nullable|date|after:today',
        ]);

        if (empty($validated['code'])) {
            $validated['code'] = strtoupper(Str::random(8));
        }

        $validated['created_by'] = Auth::id();
        $validated['is_active'] = true;

        ReferenceCode::create($validated);

        return redirect()->route('admin.reference-codes.index')
            ->with('success', 'Kode referensi berhasil ditambahkan.');
    }

    public function edit(ReferenceCode $referenceCode)
    {
        $roles = Role::options();
        return view('pages.admin.reference-code.edit', compact('referenceCode', 'roles'));
    }

    public function update(Request $request, ReferenceCode $referenceCode)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'max_usage' => 'required|integer|min:0',
            'role' => 'required|in:admin,staff',
            'expired_at' => 'nullable|date',
        ]);

        $referenceCode->update($validated);

        return redirect()->route('admin.reference-codes.index')
            ->with('success', 'Kode referensi berhasil diperbarui.');
    }

    public function destroy(ReferenceCode $referenceCode)
    {
        $referenceCode->delete();

        return redirect()->route('admin.reference-codes.index')
            ->with('success', 'Kode referensi berhasil dihapus.');
    }

    public function toggleStatus(ReferenceCode $referenceCode)
    {
        $referenceCode->update(['is_active' => !$referenceCode->is_active]);

        $status = $referenceCode->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Kode referensi berhasil {$status}.");
    }

    public function usage(ReferenceCode $referenceCode)
    {
        $users = $referenceCode->users()->withPivot('used_at')->get();
        return view('pages.admin.reference-code.usage', compact('referenceCode', 'users'));
    }
}
