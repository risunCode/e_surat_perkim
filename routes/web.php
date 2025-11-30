<?php

use App\Http\Controllers\AgendaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DispositionController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\IncomingLetterController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\OutgoingLetterController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SecurityQuestionController;
use App\Http\Controllers\Admin\ClassificationController;
use App\Http\Controllers\Admin\LetterStatusController;
use App\Http\Controllers\Admin\ReferenceCodeController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AttachmentController;
use App\Http\Controllers\TranscriptController;
use App\Http\Controllers\DocumentVerificationController;
use Illuminate\Support\Facades\Route;

// Guest routes (auth only)
Route::middleware('guest')->group(function () {
    // Unified auth page
    Route::get('/auth', function () {
        $mode = request('mode', 'login');
        return view('pages.auth.index', ['mode' => $mode]);
    })->name('auth');
});

// Password Reset (Local - Security Questions) - Allow both guest & authenticated
Route::get('/forgot-password', [SecurityQuestionController::class, 'forgotPassword'])->name('password.request');
Route::post('/forgot-password', [SecurityQuestionController::class, 'verifyEmail'])->name('password.verify-email');
Route::get('/verify-security', [SecurityQuestionController::class, 'showSecurityForm'])->name('password.security');
Route::post('/verify-security', [SecurityQuestionController::class, 'verifySecurity'])->name('password.verify-security');
Route::get('/reset-password', [SecurityQuestionController::class, 'showResetForm'])->name('password.reset.form');
Route::post('/reset-password', [SecurityQuestionController::class, 'resetPassword'])->name('password.update.local');

// Security Setup (must be authenticated but not yet setup)
Route::middleware('auth')->group(function () {
    Route::get('/security-setup', [SecurityQuestionController::class, 'setup'])->name('security.setup');
    Route::post('/security-setup', [SecurityQuestionController::class, 'storeSetup'])->name('security.store');
});

// Document Verification (Public access - no auth required)
Route::prefix('verify')->group(function () {
    Route::get('/', [DocumentVerificationController::class, 'index'])->name('document.verification');
    Route::post('/', [DocumentVerificationController::class, 'check'])->name('document.check');
    Route::get('/{hash}', [DocumentVerificationController::class, 'verify'])->name('document.verify');
});

// Authenticated routes (with security check)
Route::middleware(['auth', \App\Http\Middleware\EnsureSecuritySetup::class])->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    
    // About
    Route::get('/about', fn() => view('pages.about'))->name('about');

    // Transaksi - Surat Masuk
    Route::prefix('transaksi/surat-masuk')->name('incoming.')->group(function () {
        Route::get('/', [IncomingLetterController::class, 'index'])->name('index');
        Route::get('/tambah', [IncomingLetterController::class, 'create'])->name('create');
        Route::post('/', [IncomingLetterController::class, 'store'])->name('store');
        Route::get('/{letter}', [IncomingLetterController::class, 'show'])->name('show');
        Route::get('/{letter}/edit', [IncomingLetterController::class, 'edit'])->name('edit');
        Route::put('/{letter}', [IncomingLetterController::class, 'update'])->name('update');
        Route::delete('/{letter}', [IncomingLetterController::class, 'destroy'])->name('destroy');
        Route::patch('/{letter}/selesai', [IncomingLetterController::class, 'toggleCompletion'])->name('toggleCompletion');
    });

    // Transaksi - Surat Keluar
    Route::prefix('transaksi/surat-keluar')->name('outgoing.')->group(function () {
        Route::get('/', [OutgoingLetterController::class, 'index'])->name('index');
        Route::get('/tambah', [OutgoingLetterController::class, 'create'])->name('create');
        Route::post('/', [OutgoingLetterController::class, 'store'])->name('store');
        Route::get('/{letter}', [OutgoingLetterController::class, 'show'])->name('show');
        Route::get('/{letter}/edit', [OutgoingLetterController::class, 'edit'])->name('edit');
        Route::put('/{letter}', [OutgoingLetterController::class, 'update'])->name('update');
        Route::delete('/{letter}', [OutgoingLetterController::class, 'destroy'])->name('destroy');
        Route::patch('/{letter}/selesai', [OutgoingLetterController::class, 'toggleCompletion'])->name('toggleCompletion');
    });

    // Disposisi
    Route::prefix('disposisi')->name('disposition.')->group(function () {
        Route::get('/{letter}/tambah', [DispositionController::class, 'create'])->name('create');
        Route::post('/{letter}', [DispositionController::class, 'store'])->name('store');
        Route::get('/{disposition}/edit', [DispositionController::class, 'edit'])->name('edit');
        Route::put('/{disposition}', [DispositionController::class, 'update'])->name('update');
        Route::delete('/{disposition}', [DispositionController::class, 'destroy'])->name('destroy');
    });

    // Agenda
    Route::prefix('agenda')->name('agenda.')->group(function () {
        Route::get('/masuk', [AgendaController::class, 'incoming'])->name('incoming');
        Route::get('/keluar', [AgendaController::class, 'outgoing'])->name('outgoing');
        Route::get('/masuk/cetak', [AgendaController::class, 'printIncoming'])->name('incoming.print');
        Route::get('/keluar/cetak', [AgendaController::class, 'printOutgoing'])->name('outgoing.print');
    });

    // Galeri
    Route::prefix('galeri')->name('gallery.')->group(function () {
        Route::get('/masuk', [GalleryController::class, 'incoming'])->name('incoming');
        Route::get('/keluar', [GalleryController::class, 'outgoing'])->name('outgoing');
    });

    // Notifications
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::get('/unread', [NotificationController::class, 'getUnread'])->name('unread');
        Route::post('/{notification}/read', [NotificationController::class, 'markAsRead'])->name('read');
        Route::post('/read-all', [NotificationController::class, 'markAllAsRead'])->name('read-all');
    });

    // Profile
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'show'])->name('show');
        Route::put('/', [ProfileController::class, 'update'])->name('update');
        Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password');
        Route::put('/security', [ProfileController::class, 'updateSecurity'])->name('security');
        Route::post('/photo', [ProfileController::class, 'updatePhoto'])->name('photo');
        Route::post('/deactivate', [ProfileController::class, 'deactivate'])->name('deactivate');
    });

    // File Serving with Cache Headers (Auth Protected)
    Route::get('/attachments/{id}', [AttachmentController::class, 'serve'])->name('attachment.serve');
    Route::get('/attachments/{id}/download', [AttachmentController::class, 'download'])->name('attachment.download');
    Route::get('/profile-pictures/{filename}', [AttachmentController::class, 'profilePicture'])->name('profile.picture');

    // Letter Transcript Print View
    Route::get('/transcript/{type}/{id}/print', [TranscriptController::class, 'printView'])->name('transcript.print');
    
    // Gallery Preview Pages
    Route::get('/galeri/masuk/print/{id}', [TranscriptController::class, 'galleryIncomingPreview'])->name('gallery.incoming.print');
    Route::get('/galeri/keluar/print/{id}', [TranscriptController::class, 'galleryOutgoingPreview'])->name('gallery.outgoing.print');

    // Admin routes
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        // Users
        Route::resource('users', UserController::class)->except(['show']);
        Route::post('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');

        // Classifications
        Route::resource('classifications', ClassificationController::class)->except(['show']);

        // Letter Statuses
        Route::resource('statuses', LetterStatusController::class)->except(['show']);

        // Reference Codes
        Route::resource('reference-codes', ReferenceCodeController::class)->except(['show']);
        Route::post('reference-codes/{referenceCode}/toggle-status', [ReferenceCodeController::class, 'toggleStatus'])->name('reference-codes.toggle-status');
        Route::get('reference-codes/{referenceCode}/usage', [ReferenceCodeController::class, 'usage'])->name('reference-codes.usage');

        // Settings
        Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
        Route::put('settings', [SettingController::class, 'update'])->name('settings.update');
    });
});
