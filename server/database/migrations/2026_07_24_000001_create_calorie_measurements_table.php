<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Saved calorie-calculator measurements. Sites are attached through the
        // polymorphic `siteables` table via the Siteable trait (no own column).
        Schema::create('calorie_measurements', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedInteger('portions')->default(1);
            // [{ foodstuff_id, name, amount }] — amount in grams/ml.
            $table->json('items')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('calorie_measurements');
    }
};
