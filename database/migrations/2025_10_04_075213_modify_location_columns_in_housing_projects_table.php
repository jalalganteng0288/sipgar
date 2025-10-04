<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('housing_projects', function (Blueprint $table) {
            if (Schema::hasColumn('housing_projects', 'district')) {
                $table->dropColumn('district');
            }

            $table->char('district_code', 7)->nullable()->after('address');
            $table->char('village_code', 10)->nullable()->after('district_code');
        });
    }

    public function down(): void
    {
        Schema::table('housing_projects', function (Blueprint $table) {
            $table->string('district')->nullable()->after('address');
            $table->dropForeign(['district_code']);
            $table->dropForeign(['village_code']);
            $table->dropColumn(['district_code', 'village_code']);
        });
    }
};
