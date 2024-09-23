<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('guarantors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contract_id')->nullable()->constrained(table: 'contracts', column: "id")->cascadeOnDelete();
            $table->foreignId('notification_id')->nullable()->constrained(table: 'notifications', column: "id")->cascadeOnDelete();
            $table->enum("civility", ["Mr", "Mme", "Mlle"]);
            $table->string("first_name");
            $table->string("last_name");
            $table->string("home_address");
            $table->enum("type_of_identity_document", ["cni", "passport", "residence_certificate", "driving_licence","carte_sej"])->default('cni');
            $table->string("number_of_identity_document");
            $table->date("date_of_issue_of_identity_document");
            $table->date("birth_date");
            $table->string("birth_place");
            $table->string("nationality");
            $table->string("function");
            $table->string("phone_number");
            $table->string("signed_contract_path")->nullable();
            $table->string("signed_promissory_note_path")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guarantors');
    }
};
