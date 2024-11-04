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
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->foreignId("verbal_trial_id")->constrained(table: 'verbals_trials', column: 'id')->cascadeOnDelete();
            $table->date("representative_birth_date");
            $table->string("representative_birth_place");
            $table->string("representative_nationality");
            $table->string("representative_home_address");
            $table->string("representative_phone_number");
            $table->enum("representative_type_of_identity_document", ["cni", "passport", "residence_certificate", "driving_licence","carte_sej"])->default('cni');
            $table->string("representative_number_of_identity_document");
            $table->date("representative_date_of_issue_of_identity_document");
            $table->float('risk_premium_percentage');
            $table->decimal('total_amount_of_interest', 30, 10);
            $table->integer('number_of_due_dates');
            $table->enum('type', ['particular', 'company', 'individual_business','ong','professions_libérales']);
            $table->boolean("has_pledges")->default(0);
            $table->foreignId("creator_id")->constrained(table: "users", column: "id")->cascadeOnDelete();
            $table->string("signed_contract_path")->nullable();
            $table->string("signed_promissory_note_path")->nullable();
            $table->enum('status', ["waiting", 'rejected', "validated"])->default('waiting');
            $table->string("status_observation")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};
