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
        Schema::create('seven_word_games', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('letter')->nullable();
            $table->string('date')->nullable();
            $table->integer('theme')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seven_word_games');
    }
};
