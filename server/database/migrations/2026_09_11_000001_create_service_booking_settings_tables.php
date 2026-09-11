<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_booking_settings', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('service_id');
            $table->foreign('service_id')
                ->references('id')
                ->on('services')
                ->onDelete('cascade');
            $table->unique('service_id');

            $table->time('work_start');
            $table->time('work_end');
            $table->integer('slot_duration_minutes');
            $table->integer('break_minutes')->default(0);
            $table->json('working_days')->nullable();
            $table->boolean('active')->default(true);

            $table->timestamps();
        });

        Schema::create('service_booking_setting_exceptions', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('booking_setting_id');
            $table->foreign('booking_setting_id')
                ->references('id')
                ->on('service_booking_settings')
                ->onDelete('cascade');

            $table->date('date');
            $table->boolean('is_closed')->default(false);
            $table->json('custom_times')->nullable();

            $table->unique(['booking_setting_id', 'date'], 'service_booking_exceptions_setting_date_unique');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_booking_setting_exceptions');
        Schema::dropIfExists('service_booking_settings');
    }
};
