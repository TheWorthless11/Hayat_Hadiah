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
        Schema::create('duas', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('category')->nullable();
            $table->text('arabic_text');
            $table->text('transliteration')->nullable();
            $table->text('translation')->nullable();
            $table->json('tags')->nullable();
            $table->timestamps();
        });

        Schema::create('islamic_rules', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('category')->nullable();
            $table->text('content');
            $table->json('references')->nullable();
            $table->timestamps();
        });

        Schema::create('user_dua_reminders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('dua_id')->nullable()->constrained()->nullOnDelete();
            $table->string('custom_title')->nullable();
            $table->time('reminder_time');
            $table->string('frequency')->default('daily');
            $table->json('days_of_week')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_dua_reminders');
        Schema::dropIfExists('islamic_rules');
        Schema::dropIfExists('duas');
    }
};
