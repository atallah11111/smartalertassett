<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::table('documents', function (Blueprint $table) {
        $table->enum('status', ['aktif', 'tidak aktif'])->default('aktif')->after('diperingatkan_h');
        $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade')->after('status');
    });
}

public function down(): void
{
    Schema::table('documents', function (Blueprint $table) {
        $table->dropColumn('status');
        $table->dropForeign(['user_id']);
        $table->dropColumn('user_id');
    });
}
};
