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
        Schema::create('fasting_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('location_id')->constrained()->cascadeOnDelete();
            $table->date('gregorian_date');
            $table->string('hijri_date')->nullable();
            $table->time('sehri_time');
            $table->time('iftar_time');
            $table->boolean('is_ramadan')->default(true);
            $table->unsignedTinyInteger('ramadan_day')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();

            $table->unique(['location_id', 'gregorian_date']);
        });

        Schema::create('islamic_calendar_events', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('hijri_date');
            $table->date('gregorian_date');
            $table->string('type')->default('reminder');
            $table->json('meta')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('islamic_calendar_events');
        Schema::dropIfExists('fasting_schedules');
    }
};
