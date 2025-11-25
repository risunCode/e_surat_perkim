<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SecurityQuestionController extends Controller
{
    /**
     * Available security questions
     */
    public static function getQuestions(): array
    {
        return [
            'pet_name' => 'Siapa nama hewan peliharaan pertama Anda?',
            'favorite_food' => 'Apa makanan favorit Anda?',
            'mother_maiden' => 'Siapa nama gadis ibu Anda?',
            'childhood_friend' => 'Siapa nama teman masa kecil Anda?',
            'first_school' => 'Apa nama sekolah dasar Anda?',
            'favorite_teacher' => 'Siapa nama guru favorit Anda?',
        ];
    }

    /**
     * Show security setup form
     */
    public function setup()
    {
        $user = Auth::user();
        
        if ($user->security_setup_completed) {
            return redirect()->route('dashboard');
        }

        return view('pages.auth.security-setup', [
            'questions' => self::getQuestions()
        ]);
    }

    /**
     * Store security questions
     */
    public function storeSetup(Request $request)
    {
        $validQuestions = array_merge(array_keys(self::getQuestions()), ['custom']);
        
        $request->validate([
            'birth_date' => 'required|date|before:today',
            'security_question' => 'required|in:' . implode(',', $validQuestions),
            'custom_question' => 'required_if:security_question,custom|nullable|string|min:5|max:255',
            'security_answer' => 'required|string|min:3|max:100',
        ], [
            'birth_date.required' => 'Tanggal lahir wajib diisi.',
            'birth_date.before' => 'Tanggal lahir tidak valid.',
            'security_question.required' => 'Pertanyaan keamanan wajib dipilih.',
            'custom_question.required_if' => 'Pertanyaan kustom wajib diisi.',
            'custom_question.min' => 'Pertanyaan minimal 5 karakter.',
            'security_answer.required' => 'Jawaban keamanan wajib diisi.',
            'security_answer.min' => 'Jawaban minimal 3 karakter.',
        ]);

        // Determine the question to save
        $questionToSave = $request->security_question;
        if ($request->security_question === 'custom') {
            $questionToSave = 'custom:' . $request->custom_question;
        }

        $user = Auth::user();
        $user->update([
            'birth_date' => $request->birth_date,
            'security_question' => $questionToSave,
            'security_answer' => Hash::make(strtolower(trim($request->security_answer))),
            'security_setup_completed' => true,
        ]);

        return redirect()->route('dashboard')->with('success', 'Pengaturan keamanan berhasil disimpan!');
    }

    /**
     * Show forgot password form (step 1: email)
     */
    public function forgotPassword()
    {
        return view('pages.auth.forgot-password');
    }

    /**
     * Verify email and show security questions (step 2)
     */
    public function verifyEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.exists' => 'Email tidak ditemukan dalam sistem.',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user->security_setup_completed) {
            return back()->withErrors(['email' => 'Akun ini belum mengatur pertanyaan keamanan. Hubungi administrator.']);
        }

        // Store email in session for next step
        session(['reset_email' => $request->email]);

        return redirect()->route('password.security');
    }

    /**
     * Show security questions form (step 2)
     */
    public function showSecurityForm()
    {
        $email = session('reset_email');
        
        if (!$email) {
            return redirect()->route('password.request');
        }

        $user = User::where('email', $email)->first();
        $questions = self::getQuestions();

        // Handle custom questions
        $questionText = 'Pertanyaan Keamanan';
        if (str_starts_with($user->security_question, 'custom:')) {
            $questionText = substr($user->security_question, 7); // Remove 'custom:' prefix
        } else {
            $questionText = $questions[$user->security_question] ?? 'Pertanyaan Keamanan';
        }

        return view('pages.auth.verify-security', [
            'email' => $email,
            'question' => $questionText,
            'birth_date_hint' => $user->birth_date ? $user->birth_date->format('Y') : null,
        ]);
    }

    /**
     * Verify security answers (step 3)
     */
    public function verifySecurity(Request $request)
    {
        $request->validate([
            'birth_date' => 'required|date',
            'security_answer' => 'required|string',
        ]);

        $email = session('reset_email');
        $user = User::where('email', $email)->first();

        if (!$user) {
            return redirect()->route('password.request');
        }

        // Verify birth date
        if ($user->birth_date->format('Y-m-d') !== $request->birth_date) {
            return back()->withErrors(['birth_date' => 'Tanggal lahir tidak sesuai.']);
        }

        // Verify security answer
        if (!Hash::check(strtolower(trim($request->security_answer)), $user->security_answer)) {
            return back()->withErrors(['security_answer' => 'Jawaban keamanan tidak sesuai.']);
        }

        // Generate reset token
        $token = bin2hex(random_bytes(32));
        session(['reset_token' => $token, 'reset_user_id' => $user->id]);

        return redirect()->route('password.reset.form');
    }

    /**
     * Show reset password form (step 4)
     */
    public function showResetForm()
    {
        if (!session('reset_token') || !session('reset_user_id')) {
            return redirect()->route('password.request');
        }

        return view('pages.auth.reset-password-local');
    }

    /**
     * Reset the password (step 5)
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ], [
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $userId = session('reset_user_id');
        $user = User::find($userId);

        if (!$user) {
            return redirect()->route('password.request');
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        // Clear session
        session()->forget(['reset_email', 'reset_token', 'reset_user_id']);

        return redirect()->route('login')->with('status', 'Password berhasil direset! Silakan login.');
    }
}
