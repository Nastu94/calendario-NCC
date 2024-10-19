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
        Schema::create('fogli_viaggio', function (Blueprint $table) {
            $table->id();
            $table->foreignId('azienda_id')->constrained('aziende')->onDelete('cascade');
            $table->foreignId('veicolo_id')->constrained('veicoli')->onDelete('cascade');
            $table->foreignId('prenotazione_id')->constrained('prenotazioni')->onDelete('cascade');
            $table->unsignedBigInteger('kmIniziali');
            $table->unsignedBigInteger('kmFinali')->nullable();
            $table->string('numero')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fogli_viaggio');
    }
};
