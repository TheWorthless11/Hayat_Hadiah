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
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('city')->nullable();
            $table->string('country');
            $table->decimal('latitude', 8, 5)->nullable();
            $table->decimal('longitude', 8, 5)->nullable();
            $table->string('timezone')->nullable();
            $table->string('calculation_method')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();
        });

        Schema::create('prayer_times', function (Blueprint $table) {
            $table->id();
            $table->foreignId('location_id')->constrained()->cascadeOnDelete();
            $table->date('prayer_date');
            $table->time('imsak')->nullable();
            $table->time('fajr');
            $table->time('sunrise')->nullable();
            $table->time('dhuhr');
            $table->time('asr');
            $table->time('maghrib');
            $table->time('isha');
            $table->time('midnight')->nullable();
            $table->time('qiyam')->nullable()->comment('Last third of night - best time for Tahajjud');
            $table->string('calculation_source')->nullable();
            $table->json('adjustments')->nullable();
            $table->timestamps();

            $table->unique(['location_id', 'prayer_date']);
        });

        Schema::create('user_prayer_preferences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('location_id')->nullable()->constrained()->nullOnDelete();
            $table->string('calculation_method')->nullable();
            $table->string('asr_madhab')->default('standard');
            $table->string('high_latitude_rule')->nullable();
            $table->boolean('notifications_enabled')->default(true);
            $table->integer('reminder_offset_minutes')->default(10);
            $table->json('custom_offsets')->nullable();
            $table->timestamps();

            $table->unique('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_prayer_preferences');
        Schema::dropIfExists('prayer_times');
        Schema::dropIfExists('locations');
    }
};
