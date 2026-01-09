<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('document_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_id')->constrained()->onDelete('cascade');
            $table->string('event'); // contoh: "peringatan dikirim", "dokumen expired", dll
            $table->text('keterangan')->nullable(); // detail tambahan
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('document_logs');
    }
};
