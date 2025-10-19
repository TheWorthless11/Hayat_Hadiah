<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('duas', function (Blueprint $table) {
            $table->id();
            $table->string('category')->index(); // Ramadan, General, Occasion
            $table->string('subsection')->nullable()->index(); // e.g., after_meal, arafat
            $table->string('title')->nullable();
            // match existing model: arabic_text and tags
            $table->text('arabic_text')->nullable();
            $table->text('transliteration')->nullable();
            $table->text('translation')->nullable();
            $table->json('tags')->nullable();
            $table->unsignedBigInteger('user_id')->nullable(); // null for admin/global
            $table->boolean('is_public')->default(true); // user added defaults to false via controller
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('duas');
    }
};
