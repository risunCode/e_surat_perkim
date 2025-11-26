<?php

namespace App\Http\Controllers;

use App\Models\Attachment;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class AttachmentController extends Controller
{
    /**
     * Serve attachment file with proper caching headers
     */
    public function serve($id)
    {
        $attachment = Attachment::findOrFail($id);
        
        // Check if file exists
        $fullPath = $attachment->full_path;
        if (!Storage::disk('public')->exists($fullPath)) {
            abort(404, 'File not found');
        }
        
        // Get file path
        $filePath = Storage::disk('public')->path($fullPath);
        
        // Get file info for cache headers
        $lastModified = filemtime($filePath);
        $etag = md5($attachment->id . '_' . $lastModified . '_' . $attachment->file_size);
        
        // Check if client has cached version
        $clientEtag = request()->header('If-None-Match');
        $clientLastModified = request()->header('If-Modified-Since');
        
        if ($clientEtag === $etag || 
            ($clientLastModified && strtotime($clientLastModified) >= $lastModified)) {
            return response('', 304);
        }
        
        // Create response with cache headers
        $response = new BinaryFileResponse($filePath);
        
        // Set cache headers - 7 days for attachments (reasonable for document files)
        $maxAge = 604800; // 7 days
        $response->headers->set('Cache-Control', 'public, max-age=' . $maxAge . ', must-revalidate');
        $response->headers->set('ETag', $etag);
        $response->headers->set('Last-Modified', gmdate('D, d M Y H:i:s', $lastModified) . ' GMT');
        $response->headers->set('Expires', gmdate('D, d M Y H:i:s', time() + $maxAge) . ' GMT');
        
        // Set proper content type
        if ($attachment->mime_type) {
            $response->headers->set('Content-Type', $attachment->mime_type);
        }
        
        // Set content disposition for download vs inline
        $disposition = request()->query('download') ? 'attachment' : 'inline';
        $response->headers->set('Content-Disposition', $disposition . '; filename="' . $attachment->filename . '"');
        
        return $response;
    }
    
    /**
     * Force download attachment
     */
    public function download($id)
    {
        $attachment = Attachment::findOrFail($id);
        
        // Check if file exists
        $fullPath = $attachment->full_path;
        if (!Storage::disk('public')->exists($fullPath)) {
            abort(404, 'File not found');
        }
        
        // Get file path
        $filePath = Storage::disk('public')->path($fullPath);
        
        // Create response for forced download
        $response = new BinaryFileResponse($filePath);
        
        // Set proper content type
        if ($attachment->mime_type) {
            $response->headers->set('Content-Type', $attachment->mime_type);
        }
        
        // Force download with proper filename
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $attachment->filename . '"');
        
        return $response;
    }
    
    /**
     * Serve profile picture with caching
     */
    public function profilePicture($filename)
    {
        $path = 'profile-pictures/' . $filename;
        
        if (!Storage::disk('public')->exists($path)) {
            abort(404, 'Profile picture not found');
        }
        
        $filePath = Storage::disk('public')->path($path);
        $lastModified = filemtime($filePath);
        $etag = md5($filename . '_' . $lastModified);
        
        // Check cache
        $clientEtag = request()->header('If-None-Match');
        if ($clientEtag === $etag) {
            return response('', 304);
        }
        
        $response = new BinaryFileResponse($filePath);
        
        // Profile pictures cache for 14 days (user might change occasionally)
        $maxAge = 1209600; // 14 days
        $response->headers->set('Cache-Control', 'public, max-age=' . $maxAge . ', must-revalidate');
        $response->headers->set('ETag', $etag);
        $response->headers->set('Last-Modified', gmdate('D, d M Y H:i:s', $lastModified) . ' GMT');
        $response->headers->set('Expires', gmdate('D, d M Y H:i:s', time() + $maxAge) . ' GMT');
        
        return $response;
    }
}
