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
        Schema::table('housing_projects', function (Blueprint $table) {
            // Kolom status untuk proses verifikasi Admin Disperkim
            $table->enum('status', ['pending', 'approved', 'rejected'])
                  ->default('pending') // Status awal saat proyek dibuat
                  ->after('developer_name'); // Letakkan setelah kolom developer_name
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('housing_projects', function (Blueprint $table) {
            // Hapus kolom status jika migrasi dibatalkan (rollback)
            $table->dropColumn('status');
        });
    }
};