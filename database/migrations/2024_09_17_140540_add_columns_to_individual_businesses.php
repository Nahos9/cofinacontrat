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
        Schema::table('individual_businesses', function (Blueprint $table) {
            $table->date('date_naiss')->nullable();
            $table->date('date_delivrance')->nullable();
            $table->string('lieux_naiss')->nullable();
            $table->string('office_delivery')->nullable();
            $table->string('home_address')->nullable();
            $table->string('num_piece')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('nationalite')->nullable();
            $table->string('number_phone')->nullable();
            $table->string('bp')->nullable();
            $table->string('commune')->nullable();
            $table->enum("civility", ["Mr", "Mme", "Mlle"]);
            $table->enum("type_of_identity_document", ["cni", "passport", "residence_certificate", "driving_licence","carte_sej"])->default('cni');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('individual_businesses', function (Blueprint $table) {
            //
        });
    }
};
