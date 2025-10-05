<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // database/migrations/xxxx_xx_xx_xxxxxx_add_image_column_to_housing_projects_table.php

    public function up(): void
    {
        Schema::table('housing_projects', function (Blueprint $table) {
            $table->string('image')->nullable()->after('developer_name');
        });
    }

    public function down(): void
    {
        Schema::table('housing_projects', function (Blueprint $table) {
            $table->dropColumn('image');
        });
    }
};
