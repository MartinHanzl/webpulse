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
        Schema::table('administrators', function (Blueprint $table) {
            $table->unsignedBigInteger('group_id')
                ->nullable()
                ->after('active');
            $table->foreign('group_id')
                ->references('id')
                ->on('administrator_groups')
                ->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('administrators', function (Blueprint $table) {
            $table->dropColumn('group_id');
        });
    }
};
