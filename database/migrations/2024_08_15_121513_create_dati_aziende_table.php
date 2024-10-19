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
        Schema::create('aziende_dati', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('azienda_id');
            $table->string('indirizzo');
            $table->string('cap');
            $table->string('citta');
            $table->string('provincia');
            $table->string('partita_iva');
            $table->string('codice_sdi');
            $table->string('codice_fiscale');
            $table->string('email');
            $table->string('cellulare');
            $table->timestamps();

            $table->foreign('azienda_id')->references('id')->on('aziende')->onDelete('cascade');            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aziende_dati');
    }
};
