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
        Schema::create('onopay_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_id')->unique();
            $table->string('qr_code')->nullable();
            $table->string('payer_phone');
            $table->string('receiver_phone');
            $table->decimal('amount', 12, 0);
            $table->string('status'); // success, failed, pending
            $table->decimal('payer_new_balance', 12, 0)->nullable();
            $table->decimal('receiver_new_balance', 12, 0)->nullable();
            $table->string('description')->nullable();
            $table->string('merchant_code')->nullable();
            $table->json('response_data')->nullable(); // Simpan response dari API
            $table->timestamps();
            
            // Indexes
            $table->index('transaction_id');
            $table->index('payer_phone');
            $table->index('receiver_phone');
            $table->index('status');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('onopay_transactions');
    }
};