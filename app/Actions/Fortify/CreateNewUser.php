<?php

namespace App\Actions\Fortify;

use App\Models\User;
use App\Models\ReferenceCode;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class),
            ],
            'password' => $this->passwordRules(),
            'reference_code' => ['required', 'string'],
            '_refcode_token' => ['required', 'string'],
        ], [
            '_refcode_token.required' => 'Silakan verifikasi kode referensi terlebih dahulu.',
        ])->validate();

        $code = strtoupper(trim($input['reference_code']));
        $token = $input['_refcode_token'] ?? '';

        // ANTI-BYPASS: Validate session token
        $sessionCode = session('refcode_verified');
        $sessionToken = session('refcode_token');
        $verifiedAt = session('refcode_verified_at');

        // Check if token matches and code matches
        if (!$sessionToken || $sessionToken !== $token || $sessionCode !== $code) {
            throw ValidationException::withMessages([
                'reference_code' => ['Sesi verifikasi tidak valid. Silakan verifikasi ulang kode referensi.'],
            ]);
        }

        // Check if verification is not expired (15 minutes max)
        if (!$verifiedAt || now()->diffInMinutes($verifiedAt) > 15) {
            // Clear session
            session()->forget(['refcode_verified', 'refcode_token', 'refcode_verified_at']);
            throw ValidationException::withMessages([
                'reference_code' => ['Sesi verifikasi sudah kedaluwarsa. Silakan verifikasi ulang.'],
            ]);
        }

        // Re-validate reference code (double check - in case status changed)
        $refCode = ReferenceCode::where('code', $code)
            ->where('is_active', true)
            ->first();

        if (!$refCode) {
            throw ValidationException::withMessages([
                'reference_code' => ['Kode referensi tidak valid atau tidak aktif.'],
            ]);
        }

        // Check if expired
        if ($refCode->expired_at && $refCode->expired_at->isPast()) {
            throw ValidationException::withMessages([
                'reference_code' => ['Kode referensi sudah kedaluwarsa.'],
            ]);
        }

        // Check usage limit (0 = unlimited)
        if ($refCode->max_usage > 0 && $refCode->used_count >= $refCode->max_usage) {
            throw ValidationException::withMessages([
                'reference_code' => ['Kode referensi sudah mencapai batas penggunaan.'],
            ]);
        }

        // Create user with role from reference code
        $user = User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
            'role' => $refCode->role ?? 'staff',
        ]);

        // Record usage
        $refCode->increment('used_count');
        $refCode->users()->attach($user->id, ['used_at' => now()]);

        // Clear verification session
        session()->forget(['refcode_verified', 'refcode_token', 'refcode_verified_at']);

        return $user;
    }
}
