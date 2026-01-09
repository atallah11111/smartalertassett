<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\DocumentReminderController;

class KirimReminderCommand extends Command
{
    protected $signature = 'reminder:whatsapp';
    protected $description = 'Kirim pengingat WhatsApp untuk dokumen yang mendekati expired';

    public function handle()
    {
        $controller = new DocumentReminderController();
        $controller->kirim();

        $this->info('Pengingat WA berhasil dikirim (jika ada yang sesuai).');
    }
}
