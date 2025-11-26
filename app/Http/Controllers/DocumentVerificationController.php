<?php

namespace App\Http\Controllers;

use App\Models\DocumentSignature;
use Illuminate\Http\Request;

class DocumentVerificationController extends Controller
{
    /**
     * Verify document signature
     */
    public function verify(Request $request, string $hash)
    {
        $signature = DocumentSignature::verifySignature($hash);
        
        if (!$signature) {
            return view('pages.verification.result', [
                'status' => 'invalid',
                'message' => 'Dokumen tidak valid atau telah dimodifikasi',
                'hash' => $hash
            ]);
        }
        
        // Load letter and user data
        $signature->load(['letter.classification', 'letter.user', 'user']);
        
        return view('pages.verification.result', [
            'status' => 'valid',
            'message' => 'Dokumen valid dan terpercaya',
            'signature' => $signature,
            'hash' => $hash
        ]);
    }
    
    /**
     * Verification page - show form to input hash manually
     */
    public function index()
    {
        return view('pages.verification.index');
    }
    
    /**
     * Handle manual verification form
     */
    public function check(Request $request)
    {
        $request->validate([
            'hash' => 'required|string|min:10'
        ]);
        
        return redirect()->route('document.verify', ['hash' => $request->hash]);
    }
}
