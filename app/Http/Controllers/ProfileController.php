<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function show()
    {
        return view('pages.profile.show');
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'birth_date' => 'nullable|date|before:today',
        ]);

        $user->update($validated);

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required|current_password',
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        Auth::user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'Password berhasil diperbarui.');
    }

    public function updateSecurity(Request $request)
    {
        $validQuestions = array_merge(array_keys(\App\Http\Controllers\SecurityQuestionController::getQuestions()), ['custom']);
        
        $validated = $request->validate([
            'birth_date' => 'required|date|before:today',
            'security_question' => 'required|in:' . implode(',', $validQuestions),
            'custom_question' => 'required_if:security_question,custom|nullable|string|min:5|max:255',
            'security_answer' => 'required|string|min:3|max:100',
        ], [
            'birth_date.required' => 'Tanggal lahir wajib diisi untuk verifikasi.',
            'birth_date.before' => 'Tanggal lahir tidak valid.',
            'security_question.required' => 'Pertanyaan keamanan wajib dipilih.',
            'custom_question.required_if' => 'Pertanyaan kustom wajib diisi.',
            'custom_question.min' => 'Pertanyaan minimal 5 karakter.',
            'security_answer.required' => 'Jawaban keamanan wajib diisi.',
            'security_answer.min' => 'Jawaban minimal 3 karakter.',
        ]);

        $user = Auth::user();

        // Verify birth date if user already has one set
        if ($user->birth_date && $user->birth_date->format('Y-m-d') !== $validated['birth_date']) {
            return back()->withErrors(['birth_date' => 'Tanggal lahir tidak sesuai dengan data profil.']);
        }

        // Determine the question to save
        $questionToSave = $validated['security_question'];
        if ($validated['security_question'] === 'custom') {
            $questionToSave = 'custom:' . $validated['custom_question'];
        }

        $user->update([
            'birth_date' => $validated['birth_date'], // Update birth date if not set
            'security_question' => $questionToSave,
            'security_answer' => Hash::make(strtolower(trim($validated['security_answer']))),
            'security_setup_completed' => true,
        ]);

        return back()->with('success', 'Pertanyaan keamanan berhasil diperbarui.');
    }

    public function updatePhoto(Request $request)
    {
        $request->validate([
            'profile_picture' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $user = Auth::user();

        // Delete old photo if exists
        if ($user->profile_picture) {
            Storage::disk('public')->delete($user->profile_picture);
        }

        $path = $request->file('profile_picture')->store('profile-pictures', 'public');
        $user->update(['profile_picture' => $path]);

        return back()->with('success', 'Foto profil berhasil diperbarui.');
    }

    public function deactivate(Request $request)
    {
        $user = Auth::user();

        // Only staff can deactivate their own account
        if ($user->isAdmin()) {
            return back()->with('error', 'Administrator tidak dapat menonaktifkan akun sendiri.');
        }

        $request->validate([
            'password' => 'required|current_password',
        ]);

        $user->update(['is_active' => false]);

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Akun berhasil dinonaktifkan.');
    }
}
