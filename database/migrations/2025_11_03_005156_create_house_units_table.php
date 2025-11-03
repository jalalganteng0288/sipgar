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
        Schema::create('house_units', function (Blueprint $table) {
            $table->id();
            $table->foreignId('housing_project_id')->constrained()->onDelete('cascade');
            $table->foreignId('house_type_id')->constrained()->onDelete('cascade');

            $table->string('block', 5);
            $table->string('unit_number', 5);
            $table->decimal('price', 15, 2)->default(0);

            // Status SiKumbang (Tersedia = Kuning, Terjual = Merah)
            $table->enum('status', ['Tersedia', 'Dipesan', 'Terjual'])->default('Tersedia');
            $table->enum('type', ['Subsidi', 'Komersil'])->default('Subsidi');

            // Koordinat Poligon Kavling (untuk peta interaktif)
            $table->json('unit_coordinates')->nullable();

            $table->timestamps();

            // Indeks unik untuk identifikasi kavling
            $table->unique(['housing_project_id', 'block', 'unit_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('house_units');
    }
};
