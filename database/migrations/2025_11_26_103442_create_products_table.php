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
    Schema::create('products', function (Blueprint $table) {
        $table->id();
        $table->string('game');                     // contoh: mobile_legends
        $table->string('name');                     // contoh: 86 Diamonds
        $table->integer('amount');                  // jumlah item
        $table->integer('price');                   // harga jual
        $table->integer('base_price')->default(0);  // harga modal
        $table->boolean('is_active')->default(true);
        $table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('products');
}

};
