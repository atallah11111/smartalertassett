<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('documents', function (Blueprint $table) {

            // Jangan tambah status lagi kalau sudah ada
            if (!Schema::hasColumn('documents', 'status')) {
                $table->enum('status', ['aktif', 'tidak aktif'])->default('aktif');
            }

            // Tambah user_id kalau belum ada (tanpa FK dulu)
            if (!Schema::hasColumn('documents', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {

            if (Schema::hasColumn('documents', 'user_id')) {
                $table->dropColumn('user_id');
            }

            if (Schema::hasColumn('documents', 'status')) {
                $table->dropColumn('status');
            }
        });
    }
};
