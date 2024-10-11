<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\CAT;
use App\Models\Company;
use App\Models\Contract;
use App\Models\Guarantee;
use App\Models\Guarantor;
use App\Models\IndividualBusiness;
use App\Models\TypeOfApplicant;
use App\Models\TypeOfCredit;
use App\Models\TypeOfGuarantee;
use App\Models\User;
use App\Models\VerbalTrial;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
	/**
	 * Seed the application's database.
	 */
	public function run(): void
	{
		$admin = User::factory(1)->create(["name" => "admin", "full_name" => "admin", "profile" => "admin", "password" => "P@sse123", "password_change_required" => false, "activated" => true, "email" => "admin@cofinacorp.com"])->first();

		$credit_analyst = User::factory(1)->create(["name" => "credit_analyst", "full_name" => "Credit Analyst", "profile" => "credit_analyst", "password" => "P@sse123", "password_change_required" => false, "activated" => true, "email" => "credit_analyst@cofinacorp.com"])->first();

		$credit_admin = User::factory(1)->create(["name" => "credit_admin", "full_name" => "Credit Admin", "profile" => "credit_admin", "password" => "P@sse123", "password_change_required" => false, "activated" => true, "email" => "credit_admin@cofinacorp.com"])->first();

		$head_credit = User::factory(1)->create(["name" => "head_credit", "full_name" => "Head Credit", "profile" => "head_credit", "password" => "P@sse123", "password_change_required" => false, "activated" => true, "email" => "head_credit@cofinacorp.com"])->first();

		$operation = User::factory(1)->create(["name" => "operation", "full_name" => "Operation", "profile" => "operation", "password" => "P@sse123", "password_change_required" => false, "activated" => true, "email" => "operation@cofinacorp.com"])->first();

		$legal = User::factory(1)->create(["name" => "legal", "full_name" => "Legal", "profile" => "legal", "password" => "P@sse123", "password_change_required" => false, "activated" => true, "email" => "legal@cofinacorp.com"])->first();

		$dex = User::factory(1)->create(["name" => "dex", "full_name" => "Dex", "profile" => "dex", "password" => "P@sse123", "password_change_required" => false, "activated" => true, "email" => "dex@cofinacorp.com"])->first();

		// foreach ([["full_name" => "Yao HOETOWOU", "email" => "yao.hoetowou@cofinacorp.com"], ["full_name" => "Simen Broklyn MEBA", "email" => "simen.meba@cofinacorp.com"], ["full_name" => "Nyokpogbe AGBENOWOSI", "email" => "nykpogbe.agbenowosi@cofinacorp.com"], ["full_name" => "Mawule Kokou DOGBE", "email" => "kokou.dogbe@cofinacorp.com"], ["full_name" => "Kossi AGBLEVON", "email" => "kossi.agblevon@cofinacorp.com"], ["full_name" => "Kossi Dodji AMEGBEDJI", "email" => "dodji.amegbedji@cofinacorp.com"], ["full_name" => "Kosi Mensa DUYIBOE", "email" => "kosi-mensa.duyiboe@cofinacorp.com"], ["full_name" => "Komlan Somefa AHATEFOU", "email" => "somefa.ahatefou@cofinacorp.com"], ["full_name" => "Komlan DADZIE", "email" => "komlan.dadzie@cofinacorp.com"], ["full_name" => "Komla Nopeli AHONDE", "email" => "komla.ahonde@cofinacorp.com"], ["full_name" => "Komi Vienyeawu KUNAKEY", "email" => "komi.kunakey@cofinacorp.com"], ["full_name" => "Komi Elom ZAGARAGO", "email" => "elom.zagarago@cofinacorp.com"], ["full_name" => "Komi Dogbéda NOLI", "email" => "komi.noli@cofinacorp.com"], ["full_name" => "Koffivi Amenyo TOLESSI", "email" => "koffivi.tolessi@cofinacorp.com"], ["full_name" => "Koffi WOEKPO", "email" => "koffi.woekpo@cofinacorp.com"], ["full_name" => "Kadiko Pyalo SOHOU", "email" => "kadiko-pyalo.sohou@cofinacorp.com"], ["full_name" => "Joseph AGBI", "email" => "joseph.agbi@cofinacorp.com"], ["full_name" => "Gnonyarou WALLA", "email" => "gnonyarou.walla@cofinacorp.com"], ["full_name" => "Gianni Patrick Attiogbe KOUDOSSOU", "email" => "gianni.koudossou@cofinacorp.com"], ["full_name" => "Gafar Tchapo NABINE", "email" => "gafar.nabine@cofinacorp.com"], ["full_name" => "Francis K GAVISSE", "email" => "francis.gavisse@cofinacorp.com"], ["full_name" => "Fatima Badoawè TCHA-COROUDOU", "email" => "fatima.tcha-coroudou@cofinacorp.com"], ["full_name" => "David Martial SOUSSOUKPO", "email" => "david-martial.soussoukpo@cofinacorp.com"], ["full_name" => "Chimene AZEGUE", "email" => "chimene.azegue@cofinacorp.com"], ["full_name" => "Baliza TEKPEZI", "email" => "baliza.tekpezi@cofinacorp.com"], ["full_name" => "Assowè MABOUGRE", "email" => "assowe.mabougre@cofinacorp.com"], ["full_name" => "Améyo Chimène Edwige ATTILA", "email" => "edwige.attila@cofinacorp.com"], ["full_name" => "Amah AYIKOE", "email" => "amah.ayikoe@cofinacorp.com"], ["full_name" => "Aimé Edem HOUNSOU DEGBE", "email" => "aime.hounsou-degbe@cofinacorp.com"], ["full_name" => "Abra DOUGAME", "email" => "abra.dougame@cofinacorp.com"], ["full_name" => "Ablam Samuel AKAKPO", "email" => "samuel.akakpo@cofinacorp.com"], ["full_name" => "Abire KPENIFEI KPATCHA", "email" => "abire.kpenifei@cofinacorp.com"], ["full_name" => "Abdou Kabirou YOMENOU", "email" => "kabirou.yomenou@cofinacorp.com"],] as $userData) {
		// 	$elements = explode("@cofinacorp.com", $userData["email"]);
		// 	$caf = User::factory(1)->create(["name" => strtolower($elements[0]), "full_name" => $userData["full_name"], "profile" => "caf", "password" => "P@sse123", "password_change_required" => false, "activated" => true, "email" => $userData["email"]])->first();
		// }
		$caf = User::factory(1)->create(["name" => strtolower("caf"), "full_name" => "CAF", "profile" => "caf", "password" => "P@sse123", "password_change_required" => false, "activated" => true, "email" => "caf@cofinacorp.com"])->first();
		
		$ca = User::factory(1)->create(["name" => "ca", "full_name" => "CA", "profile" => "ca", "password" => "P@sse123", "password_change_required" => false, "activated" => true, "email" => "ca@cofinacorp.com"])->first();

		$md = User::factory(1)->create(["name" => "md", "full_name" => "MD", "profile" => "md", "password" => "P@sse123", "password_change_required" => false, "activated" => true, "email" => "md@cofinacorp.com"])->first();

		$physical_person = TypeOfApplicant::factory(1)->create(["name" => "Personne Physique"])->first();
		$moral_person = TypeOfApplicant::factory(1)->create(["name" => "Personne Morale"])->first();

		$typeOfCredit = TypeOfCredit::factory(1)->create(["name" => "AVANCE SUR FACTURE", "min_month" => 0, "max_month" => 6, "type_of_applicant_id" => $moral_person->id])->first();
		TypeOfCredit::factory(1)->create(["name" => "AVANCE SUR FACTURE", "min_month" => 6, "max_month" => 12, "type_of_applicant_id" => $moral_person->id]);
		TypeOfCredit::factory(1)->create(["name" => "AVANCE SUR LOYER", "min_month" => 0, "max_month" => 6, "type_of_applicant_id" => $physical_person->id]);
		TypeOfCredit::factory(1)->create(["name" => "AVANCE MARCHE/BC", "min_month" => 0, "max_month" => 6, "type_of_applicant_id" => $moral_person->id]);
		TypeOfCredit::factory(1)->create(["name" => "AVANCE MARCHE/BC_SOLO", "min_month" => 6, "max_month" => 12, "type_of_applicant_id" => $moral_person->id]);
		TypeOfCredit::factory(1)->create(["name" => "AV SALAIRE/PENSION", "min_month" => 0, "max_month" => 6, "type_of_applicant_id" => $physical_person->id]);
		TypeOfCredit::factory(1)->create(["name" => "CREDIT DE CAMPAGNE", "min_month" => 0, "max_month" => 6, "type_of_applicant_id" => $moral_person->id]);
		TypeOfCredit::factory(1)->create(["name" => "CREDIT DE CAMPAGNE", "min_month" => 6, "max_month" => 12, "type_of_applicant_id" => $moral_person->id]);
		TypeOfCredit::factory(1)->create(["name" => "CREDIT EXPLOITATION", "min_month" => 0, "max_month" => 6, "type_of_applicant_id" => $physical_person->id]);
		TypeOfCredit::factory(1)->create(["name" => "CREDIT EXPLOITATION", "min_month" => 6, "max_month" => 12, "type_of_applicant_id" => $physical_person->id]);
		TypeOfCredit::factory(1)->create(["name" => "CREDIT DE GROUPE", "min_month" => 6, "max_month" => 12, "type_of_applicant_id" => $moral_person->id]);
		TypeOfCredit::factory(1)->create(["name" => "CREDIT D'INVESTISSEMENT", "min_month" => 0, "max_month" => 6, "type_of_applicant_id" => $physical_person->id]);
		TypeOfCredit::factory(1)->create(["name" => "CREDIT D'INVESTISSEMENT", "min_month" => 6, "max_month" => 12, "type_of_applicant_id" => $physical_person->id]);
		TypeOfCredit::factory(1)->create(["name" => "CREDIT  CONSO", "min_month" => 0, "max_month" => 6, "type_of_applicant_id" => $physical_person->id]);
		TypeOfCredit::factory(1)->create(["name" => "CREDIT  CONSO", "min_month" => 6, "max_month" => 12, "type_of_applicant_id" => $physical_person->id]);
		TypeOfCredit::factory(1)->create(["name" => "ESCOMPTE DE CHEQUE", "min_month" => 0, "max_month" => 6, "type_of_applicant_id" => $moral_person->id]);
		TypeOfCredit::factory(1)->create(["name" => "ESCOMPTE DE TRAITE", "min_month" => 0, "max_month" => 6, "type_of_applicant_id" => $moral_person->id]);
		TypeOfCredit::factory(1)->create(["name" => "ESCOMPTE DE TRAITE_SOLO", "min_month" => 6, "max_month" => 12, "type_of_applicant_id" => $moral_person->id]);
		TypeOfCredit::factory(1)->create(["name" => "CREDIT FDR", "min_month" => 0, "max_month" => 6, "type_of_applicant_id" => $moral_person->id]);
		TypeOfCredit::factory(1)->create(["name" => "CREDIT FDR", "min_month" => 6, "max_month" => 12, "type_of_applicant_id" => $moral_person->id]);
		TypeOfCredit::factory(1)->create(["name" => "CREDIT DE IMMOBILIER", "min_month" => 0, "max_month" => 6, "type_of_applicant_id" => $physical_person->id]);
		TypeOfCredit::factory(1)->create(["name" => "CREDIT EXPLOITATION", "min_month" => 12, "max_month" => 24, "type_of_applicant_id" => $physical_person->id]);
		TypeOfCredit::factory(1)->create(["name" => "CREDIT DE GROUPE", "min_month" => 12, "max_month" => 24, "type_of_applicant_id" => $moral_person->id]);
		TypeOfCredit::factory(1)->create(["name" => "CREDIT D'INVESTISSEMENT", "min_month" => 12, "max_month" => 24, "type_of_applicant_id" => $physical_person->id]);
		TypeOfCredit::factory(1)->create(["name" => "CREDIT D'INVESTISSEMENT", "min_month" => 24, "max_month" => 36, "type_of_applicant_id" => $physical_person->id]);
		TypeOfCredit::factory(1)->create(["name" => "CREDIT  CONSO", "min_month" => 12, "max_month" => 24, "type_of_applicant_id" => $physical_person->id]);
		TypeOfCredit::factory(1)->create(["name" => "CREDIT  CONSO", "min_month" => 24, "max_month" => 36, "type_of_applicant_id" => $physical_person->id]);
		TypeOfCredit::factory(1)->create(["name" => "CREDIT FDR", "min_month" => 12, "max_month" => 24, "type_of_applicant_id" => $moral_person->id]);
		TypeOfCredit::factory(1)->create(["name" => "CREDIT IMMOBILIER", "min_month" => 24, "max_month" => 36, "type_of_applicant_id" => $physical_person->id]);
		TypeOfCredit::factory(1)->create(["name" => "CREDIT D'INVESTISSEMENT", "min_month" => 36, "max_month" => 120, "type_of_applicant_id" => $physical_person->id]);
		TypeOfCredit::factory(1)->create(["name" => "CREDIT  CONSO", "min_month" => 36, "max_month" => 120, "type_of_applicant_id" => $physical_person->id]);
		TypeOfCredit::factory(1)->create(["name" => "CREDIT  BFR", "min_month" => 0, "max_month" => 6, "type_of_applicant_id" => $physical_person->id]);
		TypeOfCredit::factory(1)->create(["name" => "CREDIT  BFR", "min_month" => 6, "max_month" => 12, "type_of_applicant_id" => $physical_person->id]);
		TypeOfCredit::factory(1)->create(["name" => "BACK TO SCHOOL", "min_month" => 0, "max_month" => 10, "type_of_applicant_id" => $physical_person->id]);
		TypeOfCredit::factory(1)->create(["name" => "COFI SCHOOL", "min_month" => 0, "max_month" => 12, "type_of_applicant_id" => $physical_person->id]);
		TypeOfCredit::factory(1)->create(["name" => "COFI SCHOOL", "min_month" => 12, "max_month" => 24, "type_of_applicant_id" => $physical_person->id]);
		TypeOfCredit::factory(1)->create(["name" => "COFI SCHOOL", "min_month" => 24, "max_month" => 36, "type_of_applicant_id" => $physical_person->id]);
		TypeOfCredit::factory(1)->create(["name" => "PP COMMERCANT", "min_month" => 0, "max_month" => 6, "type_of_applicant_id" => $physical_person->id]);
		TypeOfCredit::factory(1)->create(["name" => "PP COMMERCANT", "min_month" => 6, "max_month" => 12, "type_of_applicant_id" => $physical_person->id]);

		foreach (["Dépôt de garantie", "Caution personnelle et solidaire", "Gage de véhicule", "Gage d'équipement", "Billet à ordre", "Engagement de domiciliation de paiement", "Constitution de PEP", "Constitution de dépôt hebdomadaire", "Hypothèque", "Nantissement de Dépôt à terme (DAT)","Domiciliation irrévocable de salaire","Domiciliation partielle de salaire","Transfert fiduciaire","PAH"] as $typeOfGuaranteeName) {
			TypeOfGuarantee::factory(1)->create(["name" => $typeOfGuaranteeName]);
		}

		if (true) {
			VerbalTrial::factory(5)->create(["creator_id" => $credit_analyst->id, "status" => "validated", "credit_admin_id" => $credit_admin->id])->each(function ($verbalTrial) use ($credit_admin) {
				Guarantee::factory(5)->create(["verbal_trial_id" => $verbalTrial->id]);
				Contract::factory(1)->create(["verbal_trial_id" => $verbalTrial->id, "type" => "individual_business", "creator_id" => $credit_admin->id])->each(function ($contract) {
					Guarantor::factory(3)->create(["contract_id" => $contract->id]);
					IndividualBusiness::factory(1)->create(["contract_id" => $contract->id]);
				});
			});
			//Pv avec contrat de société
			VerbalTrial::factory(5)->create(["creator_id" => $credit_analyst->id, "status" => "validated", "credit_admin_id" => $credit_admin->id])->each(function ($verbalTrial) use ($credit_admin) {
				Guarantee::factory(5)->create(["verbal_trial_id" => $verbalTrial->id]);
				Contract::factory(1)->create(["verbal_trial_id" => $verbalTrial->id, "type" => "company", "creator_id" => $credit_admin->id])->each(function ($contract) {
					Guarantor::factory(3)->create(["contract_id" => $contract->id]);
					Company::factory(1)->create(["contract_id" => $contract->id]);
				});
			});
			//Pv avec contrat particulier
			VerbalTrial::factory(5)->create(["creator_id" => $credit_analyst->id, "status" => "validated", "credit_admin_id" => $credit_admin->id])->each(function ($verbalTrial) use ($credit_admin) {
				Guarantee::factory(5)->create(["verbal_trial_id" => $verbalTrial->id]);
				Contract::factory(1)->create(["verbal_trial_id" => $verbalTrial->id, "type" => "particular", "creator_id" => $credit_admin->id])->each(function ($contract) {
					Guarantor::factory(3)->create(["contract_id" => $contract->id]);
				});
			});

			//Pv avec contrat et avec CAT
			VerbalTrial::factory(5)->create(["creator_id" => $credit_analyst->id, "credit_admin_id" => $credit_admin->id])->each(function ($verbalTrial) use ($credit_admin) {
				Guarantee::factory(5)->create(["verbal_trial_id" => $verbalTrial->id]);
				Contract::factory(1)->create(["verbal_trial_id" => $verbalTrial->id, "type" => "particular", "creator_id" => $credit_admin->id])->each(function ($contract) {
					Guarantor::factory(3)->create(["contract_id" => $contract->id]);
					CAT::factory(1)->create(["contract_id" => $contract->id]);
				});
			});

			//Pv sans contrat
			VerbalTrial::factory(15)->create(["creator_id" => $credit_analyst->id, "credit_admin_id" => $credit_admin->id])->each(function ($verbalTrial) {
				Guarantee::factory(5)->create(["verbal_trial_id" => $verbalTrial->id]);
			});

			//Pv hypothécaire non validé
			VerbalTrial::factory(5)->create(["creator_id" => $credit_analyst->id, "credit_admin_id" => $credit_admin->id])->each(function ($verbalTrial) {
				Guarantee::factory(2)->create(["verbal_trial_id" => $verbalTrial->id, "type_of_guarantee_id" => 9]);
			});
			//Pv hypothécaire validés
			VerbalTrial::factory(5)->create(["creator_id" => $credit_analyst->id, "status" => "validated", "credit_admin_id" => $credit_admin->id])->each(function ($verbalTrial) {
				Guarantee::factory(2)->create(["verbal_trial_id" => $verbalTrial->id, "type_of_guarantee_id" => 9]);
			});
		}
		// //Pv avec contrat individuel

		$plainTextToken = $credit_admin->createToken("auth-token")->plainTextToken;
		DB::update("update personal_access_tokens set TOKEN = '8fb55a1d50842403ddc4ea7dc0c80a5d2e44eeb029f1077341babd46b68fe0ba' where ID = 1");
		$plainTextToken = $credit_admin->createToken("auth-token")->plainTextToken;
		DB::update("update personal_access_tokens set TOKEN = '96b91ec21dfc7acd2bb8489528e67dbabaf8d6da488267f72069ebb0f09658d6' where ID = 2");
		$plainTextToken = "1|c96jDUWBogbtZRsU6Oo9ZbzL3ZB5ry2spd3PC5RHd9464644";
		$file = public_path('../.env');
		$lines = file($file);

		if (count($lines) >= 1) {
			$lines[count($lines) - 1] = "SCRIBE_AUTH_KEY=$plainTextToken\n";

			// Réécrire le contenu modifié dans le fichier
			file_put_contents($file, implode('', $lines));
		} else {
			// Gérer le cas où le fichier n'a pas assez de lignes
			echo "Le fichier ne contient pas suffisamment de lignes pour effectuer le remplacement.";
		}

		echo "admin Token: " . $plainTextToken . "\n";


	}
}
