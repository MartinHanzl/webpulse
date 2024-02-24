<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('link_translations', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('link_id');
            $table->foreign('link_id')
                ->references('id')
                ->on('links')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');

            $table->string('locale');
            $table->string('title');
            $table->string('link')->default('/');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('link_translations');
    }
};
