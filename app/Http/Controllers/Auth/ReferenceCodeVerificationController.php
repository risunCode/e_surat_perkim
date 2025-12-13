<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\ReferenceCode;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ReferenceCodeVerificationController extends Controller
{
    /**
     * Verify reference code via AJAX.
     * Returns a token that must be submitted with registration.
     */
    public function verify(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:50',
        ]);

        $code = strtoupper(trim($request->code));

        $refCode = ReferenceCode::where('code', $code)
            ->where('is_active', true)
            ->first();

        if (!$refCode) {
            return response()->json([
                'valid' => false,
                'message' => 'Kode referensi tidak ditemukan atau tidak aktif.',
            ]);
        }

        // Check expiration
        if ($refCode->expired_at && $refCode->expired_at->isPast()) {
            return response()->json([
                'valid' => false,
                'message' => 'Kode referensi sudah kedaluwarsa.',
            ]);
        }

        // Check usage limit (0 = unlimited)
        if ($refCode->max_usage > 0 && $refCode->used_count >= $refCode->max_usage) {
            return response()->json([
                'valid' => false,
                'message' => 'Kode referensi sudah mencapai batas penggunaan.',
            ]);
        }

        // Generate verification token and store in session
        $token = Str::random(64);
        session([
            'refcode_verified' => $code,
            'refcode_token' => $token,
            'refcode_verified_at' => now(),
        ]);

        return response()->json([
            'valid' => true,
            'token' => $token,
            'message' => 'Kode referensi valid.',
        ]);
    }
}
