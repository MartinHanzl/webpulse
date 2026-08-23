<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contact_board_sections', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('name');
            $table->string('color')->default('#6366f1');
            $table->unsignedInteger('position')->default(0);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index('user_id');
        });

        Schema::create('contact_board_cards', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('contact_board_section_id')->nullable();
            $table->unsignedBigInteger('contact_id')->nullable();
            $table->string('title');
            $table->text('description')->nullable();
            $table->text('note')->nullable();
            $table->enum('priority', ['low', 'medium', 'high', 'critical'])->default('medium');
            $table->date('due_date')->nullable();
            $table->unsignedInteger('position')->default(0);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('contact_board_section_id')->references('id')->on('contact_board_sections')->onDelete('set null');
            $table->foreign('contact_id')->references('id')->on('contacts')->onDelete('set null');
            $table->index(['contact_board_section_id', 'position']);
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_board_cards');
        Schema::dropIfExists('contact_board_sections');
    }
};
