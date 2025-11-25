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
        ])->validate();

        // Validate reference code
        $refCode = ReferenceCode::where('code', strtoupper($input['reference_code']))
            ->where('is_active', true)
            ->whereNull('deleted_at')
            ->first();

        if (!$refCode) {
            throw ValidationException::withMessages([
                'reference_code' => ['Kode referensi tidak valid atau tidak aktif.'],
            ]);
        }

        // Check if expired
        if ($refCode->expired_at && $refCode->expired_at < now()) {
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

        return $user;
    }
}
