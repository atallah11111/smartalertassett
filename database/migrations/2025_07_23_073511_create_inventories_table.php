<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('nama_dokumen');
            $table->string('jenis_dokumen');
            $table->string('upload_dokumen'); // path/file dokumen
            $table->string('nama_pic');
            $table->string('jabatan')->nullable();
            $table->string('nomor_pic');
            $table->date('tanggal_expired')->nullable(); // tanggal expired dokumen
            $table->integer('diperingatkan_h')->nullable(); // H-berapa hari sebelum peringatan
            $table->enum('status', ['diproses', 'telah di proses'])->default('diproses'); // kolom status
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // user yang mengupload
            $table->timestamps();
        });
    }

    public function down(): void
{
    Schema::table('inventories', function (Blueprint $table) {
        $table->dropColumn('status');
        $table->dropForeign(['user_id']);
        $table->dropColumn('user_id');
    });
}
};
