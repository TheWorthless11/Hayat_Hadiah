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
        Schema::create('zakat_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('nisab_value', 12, 2)->nullable();
            $table->string('currency')->default('USD');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('zakat_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('zakat_category_id')->nullable()->constrained()->nullOnDelete();
            $table->decimal('assets_value', 14, 2);
            $table->decimal('liabilities_value', 14, 2)->default(0);
            $table->decimal('zakat_due', 14, 2);
            $table->unsignedSmallInteger('calculation_year');
            $table->date('paid_at')->nullable();
            $table->text('notes')->nullable();
            $table->json('breakdown')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('zakat_records');
        Schema::dropIfExists('zakat_categories');
    }
};
