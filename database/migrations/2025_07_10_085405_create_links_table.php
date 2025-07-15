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
        Schema::create('links', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('domain');
            $table->string('slug');
            $table->string('cloaking_method'); // template , redirect 
            $table->string('cloaking_url')->nullable();
            $table->string('template')->nullable();
            $table->integer('meta_id')->nullable();
            $table->boolean('random_target_url')->default(false);
            $table->text('target_url');
            $table->string('lock_country')->default('all');
            $table->string('lock_platform')->default('all'); // mobile-only, desktop-only, fb-browser.
            $table->string('lock_referer')->default('all'); // fbads, googleads.
            $table->boolean('active')->default(true);
            $table->integer('clicks')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('links');
    }
};
