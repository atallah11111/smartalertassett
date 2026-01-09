<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DocumentReminderController;
use App\Http\Controllers\OtpController;
// use App\Http\Controllers\WhatsappTestController;

Route::get('/kirim-wa-dokumen', [DocumentReminderController::class, 'kirim']);
Route::get('/kirim-otp-bos', [OtpController::class, 'kirimOtpBos']);
// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');
// Route::post('/test-wa', [WhatsappTestController::class, 'send']);

// Route::post('/whatsapp-reminder', [WhatsappReminderController::class, 'send']);
// Route::get('/kirim-reminder-dokumen', [DocumentReminderController::class, 'kirimReminder']);