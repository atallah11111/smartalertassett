<?php

namespace App\Console\Commands;

use App\Models\Documents;
use App\Models\DocumentLog;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class PeriksaDokumenKadaluarsa extends Command
{
    protected $signature = 'dokumen:cek-expired';
    protected $description = 'Memeriksa dokumen yang akan atau sudah kadaluarsa dan mencatat log peringatannya';

    public function handle(): void
    {
        $today = Carbon::today();

        // 🔔 Peringatan sebelum expired
        $dokumenSebelumExpired = Documents::whereNotNull('tanggal_expired')
            ->whereNotNull('diperingatkan_h')
            ->get();

        foreach ($dokumenSebelumExpired as $doc) {
            $targetReminderDate = Carbon::parse($doc->tanggal_expired)->subDays($doc->diperingatkan_h);
            if ($today->equalTo($targetReminderDate)) {
                Log::info("🔔 Peringatan H-{$doc->diperingatkan_h} untuk dokumen: {$doc->nama_dokumen}");

                DocumentLog::create([
                    'document_id' => $doc->id,
                    'event' => 'Peringatan H-' . $doc->diperingatkan_h,
                    'keterangan' => "Dikirim pada {$today->format('d-m-Y')}",
                ]);
            }
        }

        // ⚠️ Peringatan setelah expired (jika diminta)
        $dokumenSetelahExpired = Documents::whereNotNull('tanggal_expired')
            ->where('ingatkan_setelah_kadaluwarsa', 'ya')
            ->whereDate('tanggal_expired', '<', $today)
            ->get();

        foreach ($dokumenSetelahExpired as $doc) {
            Log::info("❗ Dokumen kadaluarsa tetap diingatkan: {$doc->nama_dokumen}");

            DocumentLog::create([
                'document_id' => $doc->id,
                'event' => 'Pengingat Setelah Expired',
                'keterangan' => "Expired pada {$doc->tanggal_expired}, dikirim {$today->format('d-m-Y')}",
            ]);
        }
    }
}
