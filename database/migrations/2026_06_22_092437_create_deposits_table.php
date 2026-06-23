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
        Schema::create('deposits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('location_id')->constrained()->onDelete('cascade');
            $table->string('nama_barang');
            $table->integer('jumlah_barang')->default(1);
            $table->string('durasi')->nullable();
            $table->text('catatan')->nullable();
            $table->string('foto_barang')->nullable();
            $table->decimal('total_biaya', 10, 2)->default(0);
            $table->enum('payment_status', ['unpaid', 'paid', 'failed'])->default('unpaid');
            $table->text('payment_qr_url')->nullable();
            $table->string('tracking_code')->unique();
            $table->enum('status', ['pending', 'accepted', 'retrieved'])->default('pending');
            $table->dateTime('waktu_titip')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deposits');
    }
};
