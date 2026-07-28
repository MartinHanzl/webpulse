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
        Schema::table('foodstuffs', function (Blueprint $table) {
            $table->unsignedBigInteger('rohlik_id')->nullable()->unique()->after('macronutrients');
        });

        Schema::table('foodstuff_categories', function (Blueprint $table) {
            $table->unsignedBigInteger('rohlik_id')->nullable()->unique()->after('foodstuff_category_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('foodstuffs', function (Blueprint $table) {
            $table->dropColumn('rohlik_id');
        });

        Schema::table('foodstuff_categories', function (Blueprint $table) {
            $table->dropColumn('rohlik_id');
        });
    }
};
