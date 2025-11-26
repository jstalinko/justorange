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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
        $table->foreignId('product_id')->constrained()->cascadeOnDelete();
        $table->string('uid');                      // ID player
        $table->string('zone_id')->nullable();      // tergantung game
        $table->integer('price');                   // harga user bayar
        $table->enum('status', ['pending','processing','success','failed'])
              ->default('pending');
        $table->string('transaction_id')->nullable(); // jika pakai API topup
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
