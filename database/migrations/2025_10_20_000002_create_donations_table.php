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
        Schema::create('donations', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_ref')->unique();
            $table->string('donor_name');
            $table->string('donor_email');
            $table->string('donor_phone');
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('BDT');
            $table->foreignId('category_id')->constrained('donation_categories')->onDelete('cascade');
            $table->text('message')->nullable();
            $table->enum('payment_method', ['bkash', 'nagad']);
            $table->enum('payment_status', ['pending', 'success', 'failed'])->default('pending');
            $table->json('payment_response')->nullable();
            $table->string('payment_transaction_id')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->timestamps();
            
            // Indexes for better query performance
            $table->index('transaction_ref');
            $table->index('payment_status');
            $table->index('category_id');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donations');
    }
};
