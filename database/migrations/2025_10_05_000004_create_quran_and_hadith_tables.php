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
        Schema::create('quran_verses', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('surah');
            $table->unsignedSmallInteger('ayah');
            $table->text('arabic_text');
            $table->text('translation')->nullable();
            $table->text('transliteration')->nullable();
            $table->string('language')->default('en');
            $table->json('meta')->nullable();
            $table->timestamps();

            $table->unique(['surah', 'ayah', 'language']);
        });

        Schema::create('hadiths', function (Blueprint $table) {
            $table->id();
            $table->string('collection');
            $table->string('book')->nullable();
            $table->string('reference')->nullable();
            $table->text('text');
            $table->string('narrator')->nullable();
            $table->text('translation')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();
        });

        Schema::create('user_daily_readings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('quran_verse_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('hadith_id')->nullable()->constrained()->nullOnDelete();
            $table->date('reading_date');
            $table->boolean('is_favorite')->default(false);
            $table->timestamps();

            $table->unique(['user_id', 'reading_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_daily_readings');
        Schema::dropIfExists('hadiths');
        Schema::dropIfExists('quran_verses');
    }
};
