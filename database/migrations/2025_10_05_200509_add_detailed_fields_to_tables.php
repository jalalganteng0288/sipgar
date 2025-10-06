<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Menambah kolom di tabel Tipe Rumah (house_types)
        Schema::table('house_types', function (Blueprint $table) {
            $table->string('image')->nullable()->after('name'); // Untuk foto tipe rumah
            $table->string('floor_plan')->nullable()->after('image'); // Untuk gambar denah
            $table->json('specifications')->nullable()->after('units_available'); // Untuk spesifikasi teknis
        });

        // Menambah kolom di tabel Proyek Perumahan (housing_projects)
        Schema::table('housing_projects', function (Blueprint $table) {
            $table->string('site_plan')->nullable()->after('image'); // Untuk siteplan digital
        });
    }

    public function down(): void
    {
        Schema::table('house_types', function (Blueprint $table) {
            $table->dropColumn(['image', 'floor_plan', 'specifications']);
        });

        Schema::table('housing_projects', function (Blueprint $table) {
            $table->dropColumn('site_plan');
        });
    }
};