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
        Schema::create('exchange_logs', function (Blueprint $table) {
            $table->id();
            $table->integer('nominal')->nullable();
            $table->string('status'); // success atau error
            $table->text('message')->nullable(); // untuk mencatat error atau pesan lainnya
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exchange_logs');
    }
};
