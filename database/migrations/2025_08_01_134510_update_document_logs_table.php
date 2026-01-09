<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('document_logs', function (Blueprint $table) {
            // Tambah kolom baru
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('status_sebelumnya')->nullable();
            $table->string('status_sekarang')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('document_logs', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn(['user_id', 'status_sebelumnya', 'status_sekarang']);
        });
    }
};
