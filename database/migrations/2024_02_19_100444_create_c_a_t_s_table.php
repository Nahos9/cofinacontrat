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
        Schema::create('c_a_t_s', function (Blueprint $table) {
            $table->id();
            $table->foreignId("contract_id")->nullable()->constrained()->cascadeOnDelete();                                         //Le contrat du
            $table->foreignId("notification_id")->nullable()->constrained()->cascadeOnDelete();                                     //Le contrat du
            $table->string("credit_number");                                                                                        //Le numéro du prêt
            $table->string("sector");                                                                                               //Le secteur
            $table->string("code_sector")->nullable();                                                                                               //Le secteur
            $table->date("first_deadline");                                                                                         //La date de première échéance
            $table->date("last_deadline");                                                                                          //La date de dernière échéance
            $table->enum("source_of_reimbursement", ["revenue_from_the_activity", "final_payer_settlement", "resale_of_goods","salary"]);    //La source du remboursement
            $table->string("instructions_from_the_risk_and_credit_department");                                                     //Les instructions du département risque et crédit
            $table->string("outstanding_number_ready_to_settle");                                                                   //Le numéro encours prêt à solder
            $table->decimal("other_expenses", 30, 3);                                                                               //Les autres frais
            $table->decimal("teg", 30, 3);                                                                                          //Le TEG
            $table->foreignId("validator_user_id")->nullable()->constrained(table: 'users', column: 'id')->cascadeOnDelete();       //Le validateur du CAT
            $table->enum("validation_status", ["waiting", "rejected", "validated"]);                                                //Le statut de validation du CAT
            $table->string("validation_comment")->nullable();                                                                       //Le commentaire de changement de status du CAT
            $table->foreignId("unblocker_user_id")->nullable()->constrained(table: 'users', column: 'id')->cascadeOnDelete();       //Le débloqueur du CAT
            $table->enum("unblock_status", ["waiting", "rejected", "validated"]);                                                   //Le statut de déblocage du CAT
            $table->string("unblock_comment")->nullable();                                                                          //Le commentaire de changement de status de déblocage du CAT
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('c_a_t_s');
    }
};
