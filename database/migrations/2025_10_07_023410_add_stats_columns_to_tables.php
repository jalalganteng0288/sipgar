<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('housing_projects', function (Blueprint $table) {
            // Menambahkan kolom untuk tipe 'Komersil' atau 'Subsidi'
            $table->string('type')->default('Komersil')->after('developer_name');
        });

        Schema::table('house_types', function (Blueprint $table) {
            // Menambahkan kolom untuk status unit
            $table->string('status')->default('Ready Stock')->after('name');
            // Mengubah nama kolom agar lebih jelas, ini opsional tapi disarankan
            $table->renameColumn('units_available', 'total_units');
        });
    }

    public function down(): void
    {
        Schema::table('housing_projects', function (Blueprint $table) {
            $table->dropColumn('type');
        });

        Schema::table('house_types', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->renameColumn('total_units', 'units_available');
        });
    }
};