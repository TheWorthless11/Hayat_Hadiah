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
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('location_id')->nullable()->after('id')->constrained()->nullOnDelete();
            $table->string('username')->nullable()->unique()->after('name');
            $table->string('timezone')->nullable()->after('email');
            $table->string('preferred_calculation_method')->nullable()->after('timezone');
            $table->string('preferred_madhab')->nullable()->after('preferred_calculation_method');
            $table->unsignedSmallInteger('reading_streak_days')->default(0)->after('preferred_madhab');
            $table->timestamp('last_quran_read_at')->nullable()->after('reading_streak_days');
            $table->boolean('prayer_notifications_enabled')->default(true)->after('last_quran_read_at');
            $table->json('dashboard_preferences')->nullable()->after('prayer_notifications_enabled');
            $table->timestamp('last_login_at')->nullable()->after('dashboard_preferences');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('location_id');
            $table->dropColumn([
                'username',
                'timezone',
                'preferred_calculation_method',
                'preferred_madhab',
                'reading_streak_days',
                'last_quran_read_at',
                'prayer_notifications_enabled',
                'dashboard_preferences',
                'last_login_at',
            ]);
        });
    }
};
