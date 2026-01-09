<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Documents;
use Carbon\Carbon;

class DocumentReminderController extends Controller
{
    public function kirim()
    {
        $documents   = Documents::all(); // Ambil semua data dokumen
        $messages    = [];
        $jamSekarang = now()->format('H:i'); // format jam:menit

        foreach ($documents as $doc) {
            // Validasi data wajib
            if (!$doc->nomor_pic || !$doc->nama_pic || !$doc->tanggal_expired || !$doc->diperingatkan_h) {
                continue;
            }

            // Hitung tanggal peringatan (H-...)
            $tanggalExpired    = Carbon::parse($doc->tanggal_expired);
            $tanggalPeringatan = $tanggalExpired->copy()->subDays($doc->diperingatkan_h);
            $hariIni           = Carbon::now()->startOfDay();

            // ✅ Kirim jika hari ini = tanggal peringatan & jam cocok
            if ($hariIni->equalTo($tanggalPeringatan) && $jamSekarang === '14:50') {

                // Pisah nama & nomor berdasarkan koma
                $namaPICs  = explode(',', $doc->nama_pic);
                $nomorPICs = explode(',', $doc->nomor_pic);

                foreach ($namaPICs as $index => $nama) {
                    $nomor = $nomorPICs[$index] ?? null;
                    if (!$nomor) continue;

                    $nomor = preg_replace('/^0/', '62', trim($nomor));

                    $message = "Selamat Pagi Bapak/Ibu " . trim($nama) . ",  

Kami ingin menginformasikan bahwa aset berikut akan segera memasuki masa kedaluwarsa:  

📋 Nama Aset       :  *{$doc->nama_dokumen}*
📅 Tanggal Expired :  *{$tanggalExpired->format('d-m-Y')}*

Mohon untuk segera melakukan tindak lanjut agar aset tetap terkelola dengan baik melalui link berikut: 
👉 http://127.0.0.1:8000

Terima kasih atas perhatian dan kerjasamanya.  
Tim Reminder Aset.";

                    // Tambahkan tiap nomor ke array messages
                    $messages[] = [
                        'number'  => $nomor,
                        'message' => $message
                    ];
                }
            }
        }

        // Kalau ada pesan, kirim ke Node.js sekali POST
        if (!empty($messages)) {
            $response = Http::post('http://localhost:3000/send-message', [
                'messages' => $messages
            ]);

            return response()->json([
                'status'  => true,
                'message' => 'Pesan WA berhasil dikirim.',
                'data'    => $response->json()
            ]);
        }

        return response()->json([
            'status'  => false,
            'message' => 'Tidak ada dokumen yang perlu dikirim pengingat hari ini.'
        ]);
    }
}
