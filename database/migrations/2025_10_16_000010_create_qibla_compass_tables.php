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
        // Table to store user's saved Qibla locations
        Schema::create('saved_qibla_locations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('location_name'); // e.g., "Home", "Office", "Mosque"
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
            $table->decimal('qibla_direction', 6, 3); // Direction in degrees (0-360)
            $table->decimal('distance_to_kaaba', 10, 2)->nullable(); // Distance in kilometers
            $table->string('address')->nullable(); // Full address
            $table->string('city')->nullable();
            $table->string('country')->nullable();
            $table->boolean('is_favorite')->default(false);
            $table->integer('usage_count')->default(0); // Track how often this location is accessed
            $table->timestamp('last_accessed_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'is_favorite']);
            $table->index('usage_count');
        });

        // Table to track Qibla compass usage statistics
        Schema::create('qibla_compass_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
            $table->decimal('qibla_direction', 6, 3);
            $table->string('device_type')->nullable(); // mobile, desktop, tablet
            $table->string('browser')->nullable();
            $table->ipAddress('ip_address')->nullable();
            $table->timestamp('accessed_at');
            $table->timestamps();

            $table->index('user_id');
            $table->index('accessed_at');
        });

        // Table for Kaaba coordinates (reference point - single row)
        Schema::create('kaaba_location', function (Blueprint $table) {
            $table->id();
            $table->decimal('latitude', 10, 7)->default(21.4225); // Kaaba latitude
            $table->decimal('longitude', 10, 7)->default(39.8262); // Kaaba longitude
            $table->string('location_name')->default('Holy Kaaba, Mecca');
            $table->string('city')->default('Mecca');
            $table->string('country')->default('Saudi Arabia');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('qibla_compass_logs');
        Schema::dropIfExists('saved_qibla_locations');
        Schema::dropIfExists('kaaba_location');
    }
};
