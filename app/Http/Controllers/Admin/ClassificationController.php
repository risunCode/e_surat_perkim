<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Classification;
use Illuminate\Http\Request;

class ClassificationController extends Controller
{
    public function index(Request $request)
    {
        $query = Classification::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                    ->orWhere('type', 'like', "%{$search}%");
            });
        }

        $classifications = $this->paginateQuery($query->orderBy('code'), $request);

        return view('pages.admin.classification.index', compact('classifications'));
    }

    public function create()
    {
        return view('pages.admin.classification.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|unique:classifications,code',
            'type' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Classification::create($validated);

        return redirect()->route('admin.classifications.index')
            ->with('success', 'Klasifikasi berhasil ditambahkan.');
    }

    public function edit(Classification $classification)
    {
        return view('pages.admin.classification.edit', compact('classification'));
    }

    public function update(Request $request, Classification $classification)
    {
        $validated = $request->validate([
            'code' => 'required|string|unique:classifications,code,' . $classification->id,
            'type' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $classification->update($validated);

        return redirect()->route('admin.classifications.index')
            ->with('success', 'Klasifikasi berhasil diperbarui.');
    }

    public function destroy(Classification $classification)
    {
        if ($classification->letters()->exists()) {
            return back()->with('error', 'Klasifikasi tidak dapat dihapus karena masih digunakan.');
        }

        $classification->delete();

        return redirect()->route('admin.classifications.index')
            ->with('success', 'Klasifikasi berhasil dihapus.');
    }
}
