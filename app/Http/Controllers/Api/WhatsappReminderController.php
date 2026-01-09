<?php
// filepath: app/Http/Controllers/Api/WhatsappReminderController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Console\Commands\SendWhatsappReminder;
use Illuminate\Support\Facades\Artisan;

class WhatsappReminderController extends Controller
{
    public function send(Request $request)
    {
        // Panggil logic pengingat WhatsApp di sini
        // Bisa juga memanggil command SendWhatsappReminder secara manual
        Artisan::call('app:send-whatsapp-reminder');
        return response()->json(['status' => 'success', 'message' => 'Reminder dikirim!']);
    }
}  
