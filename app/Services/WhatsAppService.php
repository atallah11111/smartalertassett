<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class WhatsAppService
{
    protected $baseUrl = 'https://sby.wablas.com';
    protected $token = 'koilNkEtn0MgVnhQuVkw2J0k5W9a3wq3JEEYQHXx3UkYGEiIQ1Q3nj4'; // Ganti dengan token asli

    public function sendMessage($phone, $message)
    {
        $response = Http::withHeaders([ 
            'Authorization' => $this->token
        ])->post("{$this->baseUrl}/api/v2/send-message", [
            'phone' => $phone,
            'message' => $message
        ]);

        return $response->json();
    }
}

