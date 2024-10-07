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
        Schema::table('pledges', function (Blueprint $table) {
            $table->string('montant_estime')->nullable();
            $table->date('date_carte_crise')->nullable();
            $table->date('date_mise_en_circulation')->nullable();
            $table->string('immatriculation')->nullable();
            $table->string('genre')->nullable();
            $table->string('marque')->nullable();
            $table->string('model')->nullable();
            $table->string('numero_serie')->nullable();
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pledges', function (Blueprint $table) {
            //
        });
    }
};