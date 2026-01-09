<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('documents', function (Blueprint $table) {
        // Sesuaikan tipe data dengan kebutuhan (string, text, atau boolean)
        // Saya asumsikan string karena di error value-nya terlihat seperti teks "tidak" atau "ya"
        $table->string('ingatkan_setelah_kadaluwarsa')->nullable()->after('diperingatkan_h');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down()
{
    Schema::table('documents', function (Blueprint $table) {
        $table->dropColumn('ingatkan_setelah_kadaluwarsa');
    });
}
};
