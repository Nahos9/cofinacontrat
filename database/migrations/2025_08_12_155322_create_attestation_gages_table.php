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
        Schema::create('attestation_gages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attestation_id')->constrained('attestations');
            $table->string('immatriculation')->nullable();
            $table->string('marque')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attestation_gages');
    }
};
