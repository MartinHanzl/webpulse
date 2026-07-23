<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('address')->nullable();
            $table->string('phone')->nullable();
            $table->integer('position')->default(0);
            $table->timestamps();
        });

        Schema::create('course_layouts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('course_id');
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade')->onUpdate('cascade');
            $table->string('name');
            $table->integer('total_meters')->nullable();
            $table->integer('holes_count')->default(0);
            $table->integer('par')->default(0);
            $table->integer('position')->default(0);
            $table->timestamps();
        });

        Schema::create('course_layout_holes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('course_layout_id');
            $table->foreign('course_layout_id')->references('id')->on('course_layouts')->onDelete('cascade')->onUpdate('cascade');
            $table->integer('number');
            $table->integer('par')->default(3);
            $table->integer('meters')->nullable();
            $table->timestamps();
            $table->unique(['course_layout_id', 'number']);
        });

        Schema::create('players', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('position')->default(0);
            $table->timestamps();
        });

        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('course_id')->nullable();
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('set null')->onUpdate('cascade');
            $table->unsignedBigInteger('course_layout_id')->nullable();
            $table->foreign('course_layout_id')->references('id')->on('course_layouts')->onDelete('set null')->onUpdate('cascade');
            $table->string('custom_course_name')->nullable();
            $table->string('custom_layout_name')->nullable();
            $table->dateTime('played_at');
            $table->enum('status', ['draft', 'in_progress', 'completed'])->default('draft');
            $table->text('note')->nullable();
            // Snapshot of layout at game time — stats stay stable if layout is later edited
            $table->integer('par')->default(0);
            $table->integer('holes_count')->default(0);
            $table->integer('position')->default(0);
            $table->timestamps();
        });

        Schema::create('game_holes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('game_id');
            $table->foreign('game_id')->references('id')->on('games')->onDelete('cascade')->onUpdate('cascade');
            $table->integer('number');
            $table->integer('par')->default(3);
            $table->integer('meters')->nullable();
            $table->timestamps();
            $table->unique(['game_id', 'number']);
        });

        Schema::create('game_players', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('game_id');
            $table->foreign('game_id')->references('id')->on('games')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('player_id');
            $table->foreign('player_id')->references('id')->on('players')->onDelete('cascade')->onUpdate('cascade');
            $table->integer('handicap')->default(0);
            $table->integer('total_throws')->default(0);
            $table->integer('position')->default(0);
            $table->timestamps();
            $table->unique(['game_id', 'player_id']);
        });

        Schema::create('game_hole_scores', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('game_id');
            $table->foreign('game_id')->references('id')->on('games')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('game_player_id');
            $table->foreign('game_player_id')->references('id')->on('game_players')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('game_hole_id');
            $table->foreign('game_hole_id')->references('id')->on('game_holes')->onDelete('cascade')->onUpdate('cascade');
            $table->integer('throws')->nullable();
            $table->timestamps();
            $table->unique(['game_player_id', 'game_hole_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('game_hole_scores');
        Schema::dropIfExists('game_players');
        Schema::dropIfExists('game_holes');
        Schema::dropIfExists('games');
        Schema::dropIfExists('players');
        Schema::dropIfExists('course_layout_holes');
        Schema::dropIfExists('course_layouts');
        Schema::dropIfExists('courses');
    }
};
