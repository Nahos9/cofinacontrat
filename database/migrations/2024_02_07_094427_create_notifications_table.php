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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId("verbal_trial_id")->constrained(table: 'verbals_trials', column: 'id')->cascadeOnDelete();
            $table->string("representative_phone_number");
            $table->string("representative_home_address");
            $table->integer("number_of_due_dates");
            $table->float('risk_premium_percentage');

            $table->enum("head_credit_validation", ["waiting", "rejected", "validated"])->default(("waiting"));
            $table->string("head_credit_observation")->nullable();
            $table->enum("status", ["waiting", "rejected", "validated"])->default(("waiting"));
            $table->string("status_observation")->nullable();

            $table->decimal('total_amount_of_interest', 30, 10);
            $table->enum("representative_type_of_identity_document", ["cni", "passport", "residence_certificate", "driving_licence"])->default('cni');
            $table->string("representative_number_of_identity_document");
            $table->date("representative_date_of_issue_of_identity_document");
            $table->enum('type', ['particular', 'company', 'individual_business']);

            $table->string("business_denomination")->nullable();

            $table->string("signed_notification_path")->nullable();
            $table->string("signed_contract_path")->nullable();
            $table->string("signed_promissory_note_path")->nullable();

            $table->foreignId("creator_id")->constrained(table: "users", column: "id")->cascadeOnDelete();

            $table->boolean("sent")->default(false);
            $table->boolean("is_simple")->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
