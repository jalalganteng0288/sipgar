<?php

namespace App\Models;

// database/migrations/xxxx_xx_xx_xxxxxx_create_house_types_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('house_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('housing_project_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->integer('price');
            $table->integer('land_area');
            $table->integer('building_area');
            $table->integer('units_available');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('house_types');
    }
};
