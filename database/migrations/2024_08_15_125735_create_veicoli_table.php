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
        Schema::create('veicoli', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('azienda_id');
            $table->string('modello');
            $table->string('targa');
            $table->unsignedBigInteger('kmPercorsi');
            $table->timestamps();

            $table->foreign('azienda_id')->references('id')->on('aziende')->onDelete('cascade');  
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('veicoli');
    }
};
