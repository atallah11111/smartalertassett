<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\OtpLoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\DocumentReminderController;
use App\Http\Controllers\DocumentLogController;
use App\Http\Controllers\SocialiteController;

// --------------------------------------------------------------------------
// Root
// --------------------------------------------------------------------------
Route::get('/', fn() => redirect()->route('login'));

// --------------------------------------------------------------------------
// Guest Routes (Login, OTP, Socialite)
// --------------------------------------------------------------------------
Route::middleware('guest')->group(function () {

    Route::get('/login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    Route::post('/login', [OtpLoginController::class, 'sendOtp'])
        ->name('login.post');

    // Socialite
    Route::get('/auth/{provider}/redirect', [SocialiteController::class, 'redirect'])
        ->name('socialite.redirect');

    Route::get('/auth/{provider}/callback', [SocialiteController::class, 'callback'])
        ->name('socialite.callback');

    // OTP
    Route::get('/otp', [OtpLoginController::class, 'showForm'])
        ->name('otp.form');

    Route::post('/otp/verify', [OtpLoginController::class, 'verifyOtp'])
        ->name('otp.verify');

    Route::post('/otp/resend', [OtpLoginController::class, 'resend'])
        ->name('otp.resend');
});

// --------------------------------------------------------------------------
// Logout
// --------------------------------------------------------------------------
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

// --------------------------------------------------------------------------
// Dashboard Redirect (role based)
// --------------------------------------------------------------------------
Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', function () {
        $user = auth()->user();

        return match ($user->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'user'  => redirect()->route('user.dashboard'),
            default => abort(403),
        };
    })->name('dashboard');

    // Reminder dokumen
    Route::get('/kirim-reminder-dokumen', [DocumentReminderController::class, 'kirimReminder']);

    // Update status dokumen
    Route::put('/documents/{id}/status', [DocumentController::class, 'updateStatus'])
        ->name('documents.updateStatus');
});

// --------------------------------------------------------------------------
// Admin Routes
// --------------------------------------------------------------------------
Route::middleware(['auth', 'can:isAdmin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        // Manajemen user
        Route::resource('users', UserController::class)->except(['show']);

        // Dokumen
        Route::get('/daftar-dokumen', [DocumentController::class, 'adminIndex'])
            ->name('documents.index');

        // Logs (Admin)
        Route::get('/daftar-log', [DocumentLogController::class, 'index'])
            ->name('document-logs.index');
    });

// --------------------------------------------------------------------------
// User Routes  ✅ (DI SINI FIX user.logs)
// --------------------------------------------------------------------------
Route::middleware(['auth', 'verified', 'can:isUser'])
    ->prefix('user')
    ->name('user.')
    ->group(function () {

        Route::get('/dashboard', [DocumentController::class, 'index'])
            ->name('dashboard');

        // Dokumen user
        Route::get('/dokumen-saya', [DocumentController::class, 'userDocuments'])
            ->name('documents');

        Route::get('/dokumen-saya/{document}', [DocumentController::class, 'show'])
            ->name('documents.show');

        Route::put('/dokumen-saya/{document}/update-file', [DocumentController::class, 'updateFile'])
            ->name('documents.update');

        // Tasks
        Route::get('/documents-task', [DocumentController::class, 'tasks'])
            ->name('documents.tasks');

        // ✅ LOGS USER (FIX ERROR)
        Route::get('/logs', [DocumentLogController::class, 'index'])
            ->name('logs');
    });

// --------------------------------------------------------------------------
// Dokumen Routes (Umum untuk auth user)
// --------------------------------------------------------------------------
Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('documents', DocumentController::class);
});

// --------------------------------------------------------------------------
// Document Logs (API / Internal)
// --------------------------------------------------------------------------
Route::post('/document-logs', [DocumentLogController::class, 'logEvent']);
Route::get('/document-logs/{document_id}', [DocumentLogController::class, 'getLogsByDocument']);
Route::get('/daftar-log', [DocumentLogController::class, 'index'])
    ->name('document-logs.index');
