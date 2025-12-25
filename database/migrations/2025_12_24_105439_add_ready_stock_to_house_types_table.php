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
        Schema::table('house_types', function (Blueprint $table) {
            // Menambah kolom ready_stock setelah kolom total_units
            $table->integer('ready_stock')->default(0)->after('total_units');
        });
    }

    public function down(): void
    {
        Schema::table('house_types', function (Blueprint $table) {
            $table->dropColumn('ready_stock');
        });
    }
};
