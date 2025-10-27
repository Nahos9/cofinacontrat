<?php

namespace App\Http\Controllers\API;

use Exception;
use Carbon\Carbon;
use App\Models\Pledge;
use App\Jobs\SendEmail;
use App\Models\Company;
use App\Models\Contract;
use Illuminate\Http\Request;
use App\Models\IndividualBusiness;
use Illuminate\Support\Facades\DB;
use Rmunate\Utilities\SpellNumber;
use App\Http\Controllers\Controller;
use App\Models\TypeOfCredit;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use PhpOffice\PhpWord\TemplateProcessor;
use Illuminate\Support\Facades\Validator;


/**
 * @group Contrat
 *
 * EndPoints pour gérer les contrats
 */
class ContractController extends Controller
{

	/**
	 * Rendre un document .docx non modifiable en ajoutant la protection readOnly
	 * dans word/settings.xml à l'intérieur de l'archive docx.
	 */
	private function protectDocx(string $filePath): void
	{
		$zip = new \ZipArchive();
		if ($zip->open($filePath) === true) {
			$settingsXml = $zip->getFromName('word/settings.xml');
			if ($settingsXml !== false) {
				$protectionPassword = env('DOCX_PROTECT_PASSWORD', 'Cofina2025');
				$spinCount = 100000;
				[$hashB64, $saltB64] = $this->generateDocxProtectionHash($protectionPassword, $spinCount);
				$legacy = $this->generateLegacyWordPasswordHash($protectionPassword);
				$protectionTag = '<w:documentProtection w:edit="readOnly" w:enforcement="1" w:password="' . $legacy . '" w:cryptProviderType="rsaFull" w:cryptAlgorithmClass="hash" w:cryptAlgorithmType="typeAny" w:cryptAlgorithmSid="4" w:cryptSpinCount="' . $spinCount . '" w:hash="' . $hashB64 . '" w:salt="' . $saltB64 . '"/>';
				if (strpos($settingsXml, 'w:documentProtection') === false) {
					$settingsXml = preg_replace('/<w:settings([^>]*)>/', '<w:settings$1>' . $protectionTag, $settingsXml, 1);
				} else {
					$settingsXml = preg_replace('/<w:documentProtection[^>]*\/>/', $protectionTag, $settingsXml, 1);
				}
				$zip->addFromString('word/settings.xml', $settingsXml);
			}
			$zip->close();
		}
	}

	/**
	 * Génère le hash et le sel (base64) pour la protection DOCX (SHA-1 + spinCount)
	 */
	private function generateDocxProtectionHash(string $password, int $spinCount = 100000): array
	{
		$passwordUtf16Le = iconv('UTF-8', 'UTF-16LE', $password);
		$salt = random_bytes(16);
		$current = sha1($salt . $passwordUtf16Le, true);
		for ($i = 0; $i < $spinCount; $i++) {
			$current = sha1($current . $passwordUtf16Le, true);
		}
		return [base64_encode($current), base64_encode($salt)];
	}

	/**
	 * Hash legacy (w:password) compatible Word, pour compatibilité maximale.
	 */
	private function generateLegacyWordPasswordHash(string $password): string
	{
		$utf16le = iconv('UTF-8', 'UTF-16LE', $password) ?: '';
		$length = (int) (strlen($utf16le) / 2);
		$key = 0x0000;
		for ($i = 0; $i < $length; $i++) {
			$low = ord($utf16le[$i * 2]);
			$high = ord($utf16le[$i * 2 + 1]);
			$charCode = $low | ($high << 8);
			$key ^= $charCode;
			$key = (($key << 1) | ($key >> 15)) & 0xFFFF;
		}
		$key ^= $length;
		$key ^= 0xCE4B;
		return strtoupper(str_pad(dechex($key), 4, '0', STR_PAD_LEFT));
	}

	/**
	 * Sauvegarde un TemplateProcessor et applique la protection lecture seule.
	 */
	private function saveAndProtect(TemplateProcessor $processor, string $outputPath): void
	{
		$processor->saveAs($outputPath);
		// Protection des fichiers Word désactivée
		// if (file_exists($outputPath)) {
		// 	$this->protectDocx($outputPath);
		// }
	}

	/**
	 * Affiche les contrats
	 *
	 * @queryParam  verbal_trial_id                                         int                 Filtrer par ID du PV.                                                   No-example
	 * @queryParam  representative_birth_date                               string              Filtrer par date de naissance du demandeur.                             No-example
	 * @queryParam  representative_birth_place                              string              Filtrer par lieu de naissance du demandeur.                             No-example
	 * @queryParam  representative_nationality                              string              Filtrer par nationalité du demandeur.                                   No-example
	 * @queryParam  represenstative_home_address                            string              Filtrer par addresse du domicile du demandeur.                          No-example
	 * @queryParam  representative_type_of_identity_document                string              Filtrer par type de la pièce d'identité du demandeur.                   No-example
	 * @queryParam  representative_number_of_identity_document              string              Filtrer par numéro de la pièce d'identité du demandeur.                 No-example
	 * @queryParam  representative_date_of_issue_of_identity_document       string              Filtrer par date de délivrance de la pièce d'identité du demandeur.     No-example
	 * @queryParam  representative_phone_number                             string              Filtrer par numéro de téléphone du demandeur.                           No-example
	 * @queryParam  risk_premium_percentage                                 int                 Filtrer par prime de risque (en pourcentage) du crédit du demandeur.    No-example
	 * @queryParam  total_amount_of_interest                                int                 Filtrer par montant total des intérêts du crédit du demandeur.          No-example
	 * @queryParam  number_of_due_dates                                     int                 Filtrer par nombre d'échéance.                                          No-example
	 * @queryParam  type                                                    string              Filtrer par type de contract.                                           No-example
	 * @queryParam  has_pledges                                             int                 Filtrer par présence de gage                                            No-example
	 * @queryParam  creator_id                                              int                 Filtrer par ID du créateur                                              No-example
	 * @queryParam  has_upload_completed                                    int                 Filtrer par finalisation du dossier du contrat.                         Example: 0
	 * @queryParam  has_cat                                                 int                 Filtrer par présence de cat.                                            Example: 0
	 * @queryParam  status                                                  string              Filtrer par statut du contrat                                           Example: waiting
	 *
	 * @queryParam  with_verbal_trial                                       int                 Afficher le PV.                                                         Example: 0
	 * @queryParam  with_type_of_credit                                     int                 Afficher le type de crédit.                                             Example: 0
	 * @queryParam  with_type_of_applicant                                  int                 Afficher le type de demandeur.                                          Example: 0
	 * @queryParam  with_caf                                                int                 Afficher le caf en charge du dossier.                                   Example: 0
	 * @queryParam  with_guarantees                                         int                 Afficher les garanties.                                                 Example: 0
	 * @queryParam  with_type_of_guarantees                                 int                 Afficher les types des garanties.                                       Example: 0
	 * @queryParam  with_company                                            int                 Afficher les informations de la société                                 Example: 0
	 * @queryParam  with_individual_business                                int                 Afficher les informations de l'entreprise individuelle                  Example: 0
	 * @queryParam  with_type_of_guarantees                                 int                 Afficher les types des garanties.                                       Example: 0
	 * @queryParam  with_creator                                            int                 Afficher le créateur du contrat.                                        Example: 0
	 * @queryParam  with_pledges                                            int                 Afficher les gages.                                                     Example: 0
	 * @queryParam  paginate                                                int                 Utiliser la pagination.                                                 Example: 0
	 *
	 * @response 200
	 */
	public function index(Request $request)
	{
		if (($authorisation = Gate::inspect('viewAny', Contract::class))->allowed()) {
			$contractList = Contract::query();
			if ($search = $request->search) {
				$contractList
					->where(function ($query) use ($search) {
						$query
							->where('representative_birth_date', 'LIKE', "%$search%")
							->orWhere('representative_birth_place', 'LIKE', "%$search%")
							->orWhere('representative_nationality', 'LIKE', "%$search%")
							->orWhere('representative_home_address', 'LIKE', "%$search%")
							->orWhere('representative_type_of_identity_document', 'LIKE', "%$search%")
							->orWhere('representative_number_of_identity_document', 'LIKE', "%$search%")
							->orWhere('representative_date_of_issue_of_identity_document', 'LIKE', "%$search%")
							->orWhere('representative_phone_number', 'LIKE', "%$search%")
							->orWhere('risk_premium_percentage', 'LIKE', "%$search%")
							->orWhere('total_amount_of_interest', 'LIKE', "%$search%")
							->orWhere('number_of_due_dates', 'LIKE', "%$search%")
							->orWhere('type', 'LIKE', "%$search%")
							->orWhere('has_pledges', 'LIKE', "%$search%")
							->orWhereHas('verbal_trial', function ($query) use ($search) {
								$query->where('committee_id', 'LIKE', "%$search%")
									->orWhere(DB::raw("CONCAT(applicant_first_name, ' ', applicant_last_name)"), 'LIKE', "%$search%");
							})
						;
					});
			}
			// dd($request["status"]);

			if (isset($request["has_cat"])) {
				$has_cat = (int) $request["has_cat"];
				if ($has_cat == 1) {
					$contractList->whereHas('c_a_t');
				} else if ($has_cat == 0) {
					$contractList->whereDoesntHave('c_a_t');
				}
			}

			foreach (["verbal_trial_id", "representative_birth_date", "representative_birth_place", "representative_nationality", "representative_home_address", "representative_type_of_identity_document", "representative_number_of_identity_document", "representative_date_of_issue_of_identity_document", "representative_phone_number", "risk_premium_percentage", "total_amount_of_interest", "number_of_due_dates", "type", "has_pledges", "creator_id"] as $filter) {
				if (isset($request[$filter]) && $request[$filter]) {
					$contractList->where($filter, $request[$filter]);
				}
			}

			if (isset($request["status"])) {
				$contractList->where(function ($query) use ($request) {
					foreach (str_split($request["status"]) as $char) {
						if (in_array($char, ['w', 'v', 'r', 'c'])) {
							$query->orWhere("status", ["w" => "waiting", "v" => "validated", "r" => "rejected"][$char]);
						}
					}
				});
			}

			foreach (["with_verbal_trial" => "verbal_trial", "with_type_of_credit" => "verbal_trial.type_of_credit", "with_type_of_applicant" => "verbal_trial.type_of_credit.type_of_applicant", "with_guarantees" => "verbal_trial.guarantees", "with_caf" => "verbal_trial.caf", "with_type_of_guarantees" => "verbal_trial.guarantees.type_of_guarantee", "with_company" => "company", "with_individual_business" => "individual_business", "with_creator" => "creator", "with_pledges" => "pledges"] as $key => $value) {
				if (isset($request[$key]) && $request[$key]) {
					$contractList->with($value);
				}
			}
			if (($currentUser = $request->user())->profile == "caf") {
				$contractList->whereHas('verbal_trial', function ($query) use ($currentUser) {
					$query->where('caf_id', $currentUser->id);
				});
			}

			// if (isset($request["has_upload_completed"])) {
			// 	if ($request["has_upload_completed"]) {
			// 		$contractList->whereNotNull('signed_contract_path')->whereNotNull('signed_promissory_note_path')->where(function ($query) {
			// 			$query->whereDoesntHave('guarantors', function ($query) {
			// 				$query->whereNull('signed_contract_path')->orWhere(function ($query) {
			// 					$query->whereNull('signed_promissory_note_path');
			// 				});
			// 			});
			// 		});
			// 	} else {
			// 		$contractList->where(function ($query) {
			// 			$query->whereNull('signed_contract_path')->orWhere(function ($query) {
			// 				$query->whereNull('signed_promissory_note_path');
			// 			})->orWhere(function ($query) {
			// 				$query->whereHas('guarantors', function ($query) {
			// 					$query->whereNull('signed_contract_path')->orWhere(function ($query) {
			// 						$query->whereNull('signed_promissory_note_path');
			// 					});
			// 				});
			// 			});
			// 		});
			// 	}
			// }
			// return $contractList->toSql();


			if (isset($request["paginate"]) && ($request->paginate == false)) {
				$contractList = $contractList->orderByDesc('updated_at')->get();
				$data = ["data" => $contractList, "total" => count($contractList)];
			} else {
				$data = $contractList->orderByDesc('updated_at')->paginate(8)->toArray();
			}

			// dd($data);

			return $this->responseOkPaginate($data);
		} else {
			return $this->responseError(["auth" => [$authorisation->message()]], 403);
		}
	}

	/**
	 * Affiche un contrat
	 *
	 * @urlParam    id                                                      int     required    L'ID du contrat.                                                        Example: 1
	 *
	 * @queryParam  with_verbal_trial                                       int                 Afficher le PV.                                                         Example: 0
	 * @queryParam  with_type_of_credit                                     int                 Afficher le type de crédit.                                             Example: 0
	 * @queryParam  with_type_of_applicant                                  int                 Afficher le type de demandeur.                                          Example: 0
	 * @queryParam  with_caf                                                int                 Afficher le CAF en charge du dossier.                                   Example: 0
	 * @queryParam  with_guarantees                                         int                 Afficher les garanties.                                                 Example: 0
	 * @queryParam  with_company                                            int                 Afficher les informations de la société                                 Example: 0
	 * @queryParam  with_individual_business                                int                 Afficher les informations de l'entreprise individuelle                  Example: 0
	 * @queryParam  with_type_of_guarantees                                 int                 Afficher les types des garanties.                                       Example: 0
	 * @queryParam  with_creator                                            int                 Afficher le créateur du contrat.                                        Example: 0
	 * @queryParam  with_pledges                                            int                 Afficher les gages.                                                     Example: 0
	 *
	 * @response 200
	 */
	public function show(Request $request, int $id)
	{
		$contract = Contract::find($id);
		if ($contract) {
			if (($authorisation = Gate::inspect('view', $contract))->allowed()) {
				$suplementList = [];
				foreach (["with_verbal_trial" => "verbal_trial", "with_type_of_credit" => "verbal_trial.type_of_credit", "with_type_of_applicant" => "verbal_trial.type_of_credit.type_of_applicant", "with_guarantees" => "verbal_trial.guarantees", "with_caf" => "verbal_trial.caf", "with_type_of_guarantees" => "verbal_trial.guarantees.type_of_guarantee", "with_company" => "company", "with_individual_business" => "individual_business", "with_pledges" => "pledges", "with_creator" => "creator"] as $key => $value) {
					if (isset($request[$key]) && $request[$key]) {
						$suplementList[] = $value;
					}
				}
				$contract->load($suplementList);
				return $this->responseOk(["contract" => $contract]);
			} else {
				return $this->responseError(
					["auth" => [$authorisation->message()]],
					403
				);
			}
		} else {
			return $this->responseError(["id" => "Le contrat n'existe pas"], 404);
		}
	}

	/**
	 * Télécharge la version word d'un contrat
	 *
	 * @urlParam    id                                                      int     required    L'ID du contrat.                                                        Example: 1
	 *
	 * @response 200
	 */
	// public function download(Request $request, int $id)
	// {
	// 	$zip = new \ZipArchive();
	// 	$zipFileName = 'contracts.zip';
	// 	$zipFilePath = public_path($zipFileName);
	// 	if ($zip->open($zipFilePath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== TRUE) {
	// 		return $this->responseError(["error" => "Impossible de créer le fichier ZIP"], 500);
	// 	}
	// 	$contract = Contract::find($id);
	// 	if ($contract) {
	// 		if (($authorisation = Gate::inspect('view', $contract))->allowed()) {
	// 			$templatePath1 = ($contract->has_pledges == "0") ? "../document_templates/Contracts/$contract->type/contract_$contract->type.docx" : "../document_templates/Contracts/$contract->type/with_pledge/contract_$contract->type" . "_with_pledge.docx";
	// 			$templatePath2 =  "../document_templates/Contracts/$contract->type/billet_a_ordre_$contract->type.docx";
	// 			$templateProcessor = new TemplateProcessor($templatePath1);
	// 			$templateProcessor1 = new TemplateProcessor($templatePath2);

	// 			$data = $contract->toArray();
	// 			$data = array_merge($data, collect($contract->verbal_trial)->mapWithKeys(function ($value, $key) {
	// 				return ['verbal_trial.' . $key => $value];
	// 			})->all());
	// 			$data = array_merge($data, collect($contract->verbal_trial->type_of_credit)->mapWithKeys(function ($value, $key) {
	// 				return ['verbal_trial.type_of_credit.' . $key => $value];
	// 			})->all());
	// 			$data = array_merge($data, collect($contract->verbal_trial->type_of_credit->type_of_applicant)->mapWithKeys(function ($value, $key) {
	// 				return ['verbal_trial.type_of_credit.type_of_applicant.' . $key => $value];
	// 			})->all());
	// 			if ($contract->type == "company") {
	// 				$data = array_merge($data, collect($contract->company)->mapWithKeys(function ($value, $key) {
	// 					return ['company.' . $key => $value];
	// 				})->all());
	// 			} elseif ($contract->type == "individual_business") {
	// 				$data = array_merge($data, collect($contract->individual_business)->mapWithKeys(function ($value, $key) {
	// 					return ['individual_business.' . $key => $value];
	// 				})->all());
	// 			}
	// 			$data["ht_rate"] = "17";
	// 			$data["verbal_trial.day_due_amount"] = ((float) $data["verbal_trial.due_amount"]) / 20;
	// 			$data["verbal_trial.day_due_amount.fr"] = SpellNumber::value((float) $data["verbal_trial.day_due_amount"])->locale('fr')->toLetters();
	// 			$data["verbal_trial.amount.fr"] = SpellNumber::value((float) $data["verbal_trial.amount"])->locale('fr')->toLetters();
	// 			$data["total_amount_of_interest.fr"] = SpellNumber::value((float) $data["total_amount_of_interest"])->locale('fr')->toLetters();
	// 			$data["verbal_trial.duration.fr"] = SpellNumber::value((float) $data["verbal_trial.duration"])->locale('fr')->toLetters();
	// 			$data["verbal_trial.due_amount.fr"] = SpellNumber::value((float) $data["verbal_trial.due_amount"])->locale('fr')->toLetters();
	// 			$data["total_to_pay"] = (float) $data["total_amount_of_interest"] + (float) $data["verbal_trial.amount"];
	// 			$data["total_to_pay.fr"] = SpellNumber::value((float) $data["total_to_pay"])->locale('fr')->toLetters();
	// 			$data["verbal_trial.duration.fr"] = SpellNumber::value((float) $data["verbal_trial.duration"])->locale('fr')->toLetters();
	// 			$data["signatory"] = (((float) $data["verbal_trial.amount"]) <= 10000000) ? "Madame Ameh Délali MESSANGAN épouse AMEDEMEGNAH, Responsable juridique" : "Mr. Koffi Djramedo GAMADO, Head Crédit";
	// 			$data["verbal_trial.periodicity.fr"] = ["mensual" => "Mensuel", "quarterly" => "Trimestrielle", "semi-annual" => "Semestrielle", "annual" => "Annuel", "in-fine" => "A la fin"][$data["verbal_trial.periodicity"]];
	// 			$data["verbal_trial.periodicity.fr2"] = ["mensual" => "chaque mois", "quarterly" => "chaque trimestre", "semi-annual" => "chaque semestre", "annual" => "chaque année", "in-fine" => "A la fin."][$data["verbal_trial.periodicity"]];
	// 			$data["verbal_trial.periodicity.fr3"] = ["mensual" => "mensualité", "quarterly" => "trimestre", "semi-annual" => "semestre", "annual" => "année", "in-fine" => "echéance."][$data["verbal_trial.periodicity"]];
	// 			$data["line_review_bonus"] = (((float) $data["verbal_trial.duration"]) < 18) ? "" : "Prime de révision de ligne      : « 1% du capital restant dû après 12 mois »";
	// 			$data["representative_type_of_identity_document"] = [
	// 				"cni" => "Carte d'identité nationale",
	// 				"passport" => "Passeport",
	// 				"residence_certificate" => "Certificat de résidence",
	// 				"driving_licence" => "Permis de conduire"
	// 			][$data["representative_type_of_identity_document"]];

	// 			$data["verbal_trial.amount"] = number_format(((float) $data["verbal_trial.amount"]), 0, ',', ' ');
	// 			$data["verbal_trial.day_due_amount"] = number_format(((float) $data["verbal_trial.day_due_amount"]), 0, ',', ' ');
	// 			$data["total_amount_of_interest"] = number_format(((float) $data["total_amount_of_interest"]), 0, ',', ' ');
	// 			$data["verbal_trial.due_amount"] = number_format(((float) $data["verbal_trial.due_amount"]), 0, ',', ' ');
	// 			$data["verbal_trial.administrative_fees_percentage"] = number_format(((float) $data["verbal_trial.administrative_fees_percentage"]), 0, ',', ' ');
	// 			$data["verbal_trial.insurance_premium"] = number_format(((float) $data["verbal_trial.insurance_premium"]), 0, ',', ' ');
	// 			$data["total_to_pay"] = number_format(((float) $data["total_to_pay"]), 0, ',', ' ');

	// 			$guaranteeList = [];
	// 			foreach ($contract->verbal_trial->guarantees as $guarantee) {
	// 				$tmp = $guarantee->toArray();
	// 				// dd($tmp);
	// 				// $tmp["value"] = number_format((float) $tmp["value"], 0, ',', ' ');
	// 				$guaranteeList[] = array_merge($tmp, collect($guarantee->type_of_guarantee)->mapWithKeys(function ($value, $key) {
	// 					return ['type_of_guarantee.' . $key => $value];
	// 				})->all());
	// 			}
	// 			$templateProcessor->cloneBlock('guaranteeList', 0, true, false, $guaranteeList);

	// 			if ($contract->has_pledges == "true") {
	// 				$pledgeList = [];
	// 				foreach ($contract->pledges as $pledge) {
	// 					$tmp = $pledge->toArray();
	// 					$tmp["type.fr"] = ["vehicle" => "véhicule", "stock" => "stock"][$tmp["type"]];
	// 					$pledgeList[] = array_merge($tmp, collect($pledge->type_of_pledge)->mapWithKeys(function ($value, $key) {
	// 						return ['pledge.' . $key => $value];
	// 					})->all());
	// 				}
	// 				$data["vehicleCount"] = $contract->pledges()->where('type', 'vehicle')->count();
	// 				$data["stockCount"] = $contract->pledges()->where('type', 'stock')->count();
	// 				$data["number_pledge.fr"] = "";
	// 				if ($data["vehicleCount"] > 0) {
	// 					$data["number_pledge.fr"] .= SpellNumber::value((float) $data["vehicleCount"])->locale('fr')->toLetters() . " véhicule(s)";
	// 				}

	// 				if ($data["stockCount"] > 0) {
	// 					$data["number_pledge.fr"] .= ($data["vehicleCount"] > 0) ? " et " : "";
	// 					$data["number_pledge.fr"] .= SpellNumber::value((float) $data["stockCount"])->locale('fr')->toLetters() . " Stock(s)";
	// 				}
	// 				$templateProcessor->cloneBlock('pledgeList', 0, true, false, $pledgeList);
	// 			}
	// 			unset($data["observations"]);
	// 			unset($data["guarantors"]);
	// 			$templateProcessor->setValues($data);
	// 			$templateProcessor1->setValues($data);

	// 			// Enregistrez les modifications dans un nouveau fichier
	// 			$outputFilePath = public_path("Contrat-" . $contract->verbal_trial->committee_id . ".docx");
	// 			$outputFilePath1 = public_path("Billet-" . $contract->verbal_trial->committee_id . ".docx");
	// 			$templateProcessor->saveAs($outputFilePath);
	// 			$templateProcessor1->saveAs($outputFilePath1);

	// 			// return Response::file($outputFilePath, ["Content-Type" => "application/vnd.openxmlformats-officedocument.wordprocessingml.document"])->deleteFileAfterSend(true);
	// 			$zip->addFile($outputFilePath, basename($outputFilePath));
	// 			$zip->addFile($outputFilePath1, basename($outputFilePath1));
	// 			$zip->close();

    // 			return response()->download($zipFilePath)->deleteFileAfterSend(true);
	// 		} else {
	// 			return $this->responseError(["auth" => [$authorisation->message()]], 403);
	// 		}
	// 	} else {
	// 		return $this->responseError(["id" => "Le contrat n'existe pas"], 404);
	// 	}
	// }

	public function download(Request $request, int $id)
	{
		$zip = new \ZipArchive();
		$zipFileName = 'contracts.zip';
		$zipFilePath = public_path($zipFileName);
		if ($zip->open($zipFilePath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== TRUE) {
			return $this->responseError(["error" => "Impossible de créer le fichier ZIP"], 500);
		}
		$contract = Contract::find($id);
		if ($contract) {
			if (($authorisation = Gate::inspect('view', $contract))->allowed()) {
				// dd($contract);
				$templatePath1 = ($contract->has_pledges == "0") ? "../document_templates/Contracts/$contract->type/contract_$contract->type.docx" : "../document_templates/Contracts/$contract->type/with_pledge/contract_$contract->type" . "_gage.docx";
				$templatePath2 =  "../document_templates/Contracts/$contract->type/billet_a_ordre_$contract->type.docx";
				
				// dd($contract->has_pledges);
				$data = $contract->toArray();
				$data = array_merge($data, collect($contract->verbal_trial)->mapWithKeys(function ($value, $key) {
					return ['verbal_trial.' . $key => $value];
				})->all());
				$data = array_merge($data, collect($contract->verbal_trial->type_of_credit)->mapWithKeys(function ($value, $key) {
					return ['verbal_trial.type_of_credit.' . $key => $value];
				})->all());
				$data = array_merge($data, collect($contract->verbal_trial->type_of_credit->type_of_applicant)->mapWithKeys(function ($value, $key) {
					return ['verbal_trial.type_of_credit.type_of_applicant.' . $key => $value];
				})->all());
				if ($contract->type == "company") {
					$data = array_merge($data, collect($contract->company)->mapWithKeys(function ($value, $key) {
						return ['company.' . $key => $value];
					})->all());
				} elseif ($contract->type == "individual_business") {
					$data = array_merge($data, collect($contract->individual_business)->mapWithKeys(function ($value, $key) {
						return ['individual_business.' . $key => $value];
					})->all());
				}
				// dd($data);
				if($data["verbal_trial.civility"] == "Mme"){
					$data["ne"] = "née le";
					$data["dom"] = "domiciliée";
				}
				if($data["verbal_trial.civility"] == "Mr"){
					$data["ne"] = "né le";
					$data["dom"] = "domicilié";
				}
				if($data["representative_type_of_identity_document"] == "carte_sej" || $data["representative_type_of_identity_document"] == "cni"){
					$data["carte"] = " de la";
				}
				if($data["representative_type_of_identity_document"] == "passport" || $data["representative_type_of_identity_document"] == "recep"){
					$data["carte"] = "du";
				}
				
				if($contract->type == "individual_business"){
					$templatePath3 = "../document_templates/Contracts/$contract->type/declaration_cession_$contract->type.docx";
					$templatePath4 = "../document_templates/Contracts/$contract->type/contrat_pep_$contract->type.docx";
					$templatePath5 = "../document_templates/Contracts/$contract->type/contrat_transfert_fudiciaire_$contract->type.docx";
					$templatePath6 = "../document_templates/Contracts/$contract->type/contrat_cautionnement_$contract->type.docx";
					// $templatePath7 = "../document_templates/Contracts/$contract->type/abandon_de_droit.docx";
					$templateProcessor2 = new TemplateProcessor($templatePath3);
					$templateProcessor3 = new TemplateProcessor($templatePath4);
					$templateProcessor4 = new TemplateProcessor($templatePath5);
					// $templateProcessor5 = new TemplateProcessor($templatePath7);
					// dd($data["verbal_trial.type_of_credit.name"] == "CREDIT FDR ");
					// dd(($data["verbal_trial.type_of_credit.name"] == "CREDIT FDR" || $data["verbal_trial.type_of_credit.name"] == "CREDIT  BFR"));
					if($contract->type == "individual_business" && ($data["verbal_trial.type_of_credit.name"] == "CREDIT FDR" || $data["verbal_trial.type_of_credit.name"] == "CREDIT  BFR"))
					{
						// dd("ok");
					$templatePath6 = "../document_templates/Contracts/$contract->type/fdr/billet_a_ordre_societe.docx";
					$templatePath7 = "../document_templates/Contracts/$contract->type/fdr/contrat_cautionnement-personne-physique.docx";
					$templatePath8 = "../document_templates/Contracts/$contract->type/fdr/contrat_de_pret_personne_morale.docx";
					$templatePath9 = "../document_templates/Contracts/$contract->type/contract_$contract->type.docx";
					$templatePath10 = "../document_templates/Contracts/$contract->type/collect.docx";
					$templatePath11 = "../document_templates/Contracts/$contract->type/contrat_pah.docx";
					$templatePath12 = "../document_templates/Contracts/$contract->type/collecte_fiche.docx";
					$templatePath13 = "../document_templates/Contracts/$contract->type/abandon_de_droit.docx";
					$templatePath14 = "../document_templates/Contracts/$contract->type/contrat_de_gage_du_stock.docx";
					$templatePath15 = "../document_templates/Contracts/$contract->type/contrat_de_nantissement_de_fonds_de_commerce.docx";
					$templateProcessor5 = new TemplateProcessor($templatePath6);
					$templateProcessor6= new TemplateProcessor($templatePath7);
					$templateProcessor7= new TemplateProcessor($templatePath8);
					$templateProcessor8= new TemplateProcessor($templatePath9);
					$templateProcessor9= new TemplateProcessor($templatePath10);
					$templateProcessor10= new TemplateProcessor($templatePath11);
					$templateProcessor11= new TemplateProcessor($templatePath12);
					$templateProcessor12= new TemplateProcessor($templatePath13);
					$templateProcessor13= new TemplateProcessor($templatePath14);
					$templateProcessor14= new TemplateProcessor($templatePath15);
					
					}
					if($contract->type == "individual_business" && ($data["verbal_trial.type_of_credit.name"] == "CREDIT D'INVESTISSEMENT" || 
						$data["verbal_trial.type_of_credit.name"] == "AVANCE MARCHE/BC" || $data["verbal_trial.type_of_credit.name"] == "AVANCE SUR FACTURE"
						|| $data["verbal_trial.type_of_credit.name"] == "PP COMMERCANT"))
						{
							$templatePath10 = "../document_templates/Contracts/$contract->type/investissement/contrat_cautionnement-personne-morale.docx";
							$templatePath11 = "../document_templates/Contracts/$contract->type/investissement/contrat_pret_personne_morale.docx";
							$templatePath12 = "../document_templates/Contracts/$contract->type/investissement/contrat_nantissement.docx";
							$templatePath13 = "../document_templates/Contracts/$contract->type/investissement/billet_a_ordre_societe.docx";
							$templatePath14 = "../document_templates/Contracts/$contract->type/contract_$contract->type.docx";// ajouter le 02 oct 2024
							$templatePath15 = "../document_templates/Contracts/$contract->type/RCCM_LOYERS.docx";// ajouter le 02 oct 2024
							$templatePath16 = "../document_templates/Contracts/$contract->type/contrat_pah.docx";// ajouter le 02 oct 2024
							$templatePath17 = "../document_templates/Contracts/$contract->type/engagement_domiciliation.docx";// ajouter le 02 oct 2024
							$templatePath18 = "../document_templates/Contracts/$contract->type/abandon_de_droit.docx";// ajouter le 02 oct 2024
							$templatePath19 = "../document_templates/Contracts/$contract->type/collecte_fiche.docx";// ajouter le 02 oct 2024
							$templateProcessor9= new TemplateProcessor($templatePath10);
							$templateProcessor10= new TemplateProcessor($templatePath11);
							$templateProcessor11= new TemplateProcessor($templatePath12);
							$templateProcessor12= new TemplateProcessor($templatePath13);
							$templateProcessor13= new TemplateProcessor($templatePath14);
							$templateProcessor14= new TemplateProcessor($templatePath15);
							$templateProcessor15= new TemplateProcessor($templatePath16);
							$templateProcessor16= new TemplateProcessor($templatePath17);
							$templateProcessor17= new TemplateProcessor($templatePath18);
							$templateProcessor18= new TemplateProcessor($templatePath19);
						
						}
					
					
				}
				if($contract->type == "company"){
					// dd("ok");
					$templatePath20 = "../document_templates/Contracts/$contract->type/declaration_cession.docx";
					$templatePath21 = "../document_templates/Contracts/$contract->type/contrat_pep.docx";
					$templatePath22 = "../document_templates/Contracts/$contract->type/contrat_transfert_fudiciaire.docx";
					$templatePath23 = "../document_templates/Contracts/$contract->type/attestation_capacite.docx";
					$templatePath24 = "../document_templates/Contracts/$contract->type/RCCM_LOYERS.docx";
					$templatePath25 = "../document_templates/Contracts/$contract->type/contrat_nantissement.docx";
					$templatePath26 = "../document_templates/Contracts/$contract->type/contrat_pret.docx";
					$templatePath27 = "../document_templates/Contracts/$contract->type/nantissement_de_compte_bancaire.docx";
					$templatePath28 = "../document_templates/Contracts/$contract->type/engagement_domiciliation.docx";
					$templatePath29 = "../document_templates/Contracts/$contract->type/contrat_pah.docx";
					$templatePath30 = "../document_templates/Contracts/$contract->type/billet_a_ordre.docx";
					$templatePath31 = "../document_templates/Contracts/$contract->type/collecte.docx";
					$templatePath32 = "../document_templates/Contracts/$contract->type/contrat_cautionnement.docx";
					$templatePath33 = "../document_templates/Contracts/$contract->type/abandon_de_droit.docx";
					$templatePath34 = "../document_templates/Contracts/$contract->type/collecte_fiche.docx";
					$templatePath35 = "../document_templates/Contracts/$contract->type/contrat_de_nantissement_de_fonds_de_commerce.docx";
					$templatePath36 = "../document_templates/Contracts/$contract->type/contrat_de_gage_du_stock.docx";
					$templatePath37 = "../document_templates/Contracts/$contract->type/contract_company_gage.docx";
					$templateProcessor2 = new TemplateProcessor($templatePath20);
					$templateProcessor3 = new TemplateProcessor($templatePath21);
					$templateProcessor4 = new TemplateProcessor($templatePath22);
					$templateProcessor5 = new TemplateProcessor($templatePath23);
					$templateProcessor6 = new TemplateProcessor($templatePath24);
					$templateProcessor7 = new TemplateProcessor($templatePath25);
					$templateProcessor8 = new TemplateProcessor($templatePath26);
					$templateProcessor9 = new TemplateProcessor($templatePath27);
					$templateProcessor10 = new TemplateProcessor($templatePath28);
					$templateProcessor11 = new TemplateProcessor($templatePath29);
					$templateProcessor12 = new TemplateProcessor($templatePath30);
					$templateProcessor13 = new TemplateProcessor($templatePath31);
					$templateProcessor14 = new TemplateProcessor($templatePath32);
					$templateProcessor15 = new TemplateProcessor($templatePath33);
					$templateProcessor16 = new TemplateProcessor($templatePath34);
					$templateProcessor17 = new TemplateProcessor($templatePath35);
					$templateProcessor18 = new TemplateProcessor($templatePath36);
					$templateProcessor19 = new TemplateProcessor($templatePath37);

					// dd($templatePath37);
				

				}
				
				if($contract->type == "particular"){
					$templatePath3 = "../document_templates/Contracts/$contract->type/declaration_cession_$contract->type.docx";
					$templatePath4 = "../document_templates/Contracts/$contract->type/contrat_pep_$contract->type.docx";
					$templatePath5 = "../document_templates/Contracts/$contract->type/contrat_transfert_fudiciaire_$contract->type.docx";
					$templatePath30 = "../document_templates/Contracts/$contract->type/contract_$contract->type.docx";
					$templatePath31 = "../document_templates/Contracts/$contract->type/contrat_pah.docx";
					$templatePath32 = "../document_templates/Contracts/$contract->type/contract_particular_gage.docx";
					$templatePath33 = "../document_templates/Contracts/$contract->type/conso/attestation_sur_honneur.docx";
					// $templatePath6 = "../document_templates/Contracts/$contract->type/contrat_cautionnement_$contract->type.docx";
					$templateProcessor2 = new TemplateProcessor($templatePath3);
					$templateProcessor3 = new TemplateProcessor($templatePath4);
					$templateProcessor4 = new TemplateProcessor($templatePath5);
					$templateProcessor30 = new TemplateProcessor($templatePath30);
					$templateProcessor31 = new TemplateProcessor($templatePath31);
					$templateProcessor32 = new TemplateProcessor($templatePath32);
					$templateProcessor33 = new TemplateProcessor($templatePath33);
					// dd("ok");
					if($contract->type == "particular"){
					$templatePath3 = "../document_templates/Contracts/$contract->type/declaration_cession_$contract->type.docx";
					$templatePath4 = "../document_templates/Contracts/$contract->type/contrat_pep_$contract->type.docx";
					$templatePath5 = "../document_templates/Contracts/$contract->type/contrat_transfert_fudiciaire_$contract->type.docx";
					$templateProcessor2 = new TemplateProcessor($templatePath3);
					$templateProcessor3 = new TemplateProcessor($templatePath4);
					$templateProcessor4 = new TemplateProcessor($templatePath5);
					if($contract->type == "particular" && $data["verbal_trial.type_of_credit.name"] == "CREDIT  CONSO" ){
					$templatePath6 = "../document_templates/Contracts/$contract->type/conso/abandon_de_droit.docx";
					$templatePath7 = "../document_templates/Contracts/$contract->type/conso/contrat_de_delegation.docx";
					$templatePath8 = "../document_templates/Contracts/$contract->type/conso/contrat_de_nantissement.docx";
					$templatePath9 = "../document_templates/Contracts/$contract->type/conso/contract_pret.docx";
					$templatePath10 = "../document_templates/Contracts/$contract->type/conso/note_information.docx";
					$templatePath11 = "../document_templates/Contracts/$contract->type/conso/contrat_pah.docx";
					$templatePath12 = "../document_templates/Contracts/$contract->type/conso/RCCM_LOYERS.docx";
					$templatePath13 = "../document_templates/Contracts/$contract->type/conso/attestation_sur_honneur.docx";
					// dd("ok");
					$templateProcessor5 = new TemplateProcessor($templatePath6);
					$templateProcessor6 = new TemplateProcessor($templatePath7);
					$templateProcessor7 = new TemplateProcessor($templatePath8);
					$templateProcessor8 = new TemplateProcessor($templatePath9);
					$templateProcessor9 = new TemplateProcessor($templatePath10);
					$templateProcessor10 = new TemplateProcessor($templatePath11);
					$templateProcessor11 = new TemplateProcessor($templatePath12);
					$templateProcessor12 = new TemplateProcessor($templatePath13);
						
					}
				
					if($contract->type == "particular" &&($data["verbal_trial.type_of_credit.name"] == "PP COMMERCANT" || $data["verbal_trial.type_of_credit.name"] == "COMMERCANT PP"))
					{
					 $templatePath9 = "../document_templates/Contracts/$contract->type/pp_commercant/collecte.docx";
					 $templatePath10 = "../document_templates/Contracts/$contract->type/pp_commercant/engagement_domiciliation.docx";
					 $templatePath11 = "../document_templates/Contracts/$contract->type/contract_$contract->type.docx";
					 $templateProcessor8 = new TemplateProcessor($templatePath9);
					 $templateProcessor9 = new TemplateProcessor($templatePath10);
					 $templateProcessor10 = new TemplateProcessor($templatePath11);

						
					}
					if($contract->type == "particular" && ($data["verbal_trial.type_of_credit.name"] == "CREDIT CONSO/IMMO"))
					{
						
						$templatePath40 = "../document_templates/Contracts/$contract->type/conso_immo/collecte.docx";
						$templatePath41 = "../document_templates/Contracts/$contract->type/conso_immo/delegation.docx";
						$templatePath42 = "../document_templates/Contracts/$contract->type/conso_immo/nantissement.docx";
						$templatePath43 = "../document_templates/Contracts/$contract->type/conso_immo/fiche_s1.docx";
						$templatePath44 = "../document_templates/Contracts/$contract->type/note_information.docx";
						$templatePath45 = "../document_templates/Contracts/$contract->type/conso_immo/attestation_sur_honneur.docx";
						$templateProcessor40 = new TemplateProcessor($templatePath40);
						$templateProcessor41 = new TemplateProcessor($templatePath41);
						$templateProcessor42 = new TemplateProcessor($templatePath42);
						$templateProcessor43 = new TemplateProcessor($templatePath43);
						$templateProcessor44 = new TemplateProcessor($templatePath44);
						$templateProcessor45 = new TemplateProcessor($templatePath45);
					}
					
				}
				
				$templateProcessor = new TemplateProcessor($templatePath1);
				$templateProcessor1 = new TemplateProcessor($templatePath2);
					
				}
				
				$templateProcessor = new TemplateProcessor($templatePath1);
				$templateProcessor1 = new TemplateProcessor($templatePath2);
				Carbon::setLocale('fr');
				$data["ht_rate"] = "17";
				$data["current_date"] = Carbon::now()->translatedFormat('d F Y');
				$data["verbal_trial.day_due_amount"] = (int)((int) $data["verbal_trial.due_amount"] / 20);
				$data["verbal_trial.day_due_amount.fr"] = SpellNumber::value((int) $data["verbal_trial.day_due_amount"])->locale('fr')->toLetters();
				// dd($data["verbal_trial.day_due_amount.fr"]);
				$data["verbal_trial.amount.fr"] = SpellNumber::value((float) $data["verbal_trial.amount"])->locale('fr')->toLetters();
				$data["total_amount_of_interest.fr"] = SpellNumber::value((float) $data["total_amount_of_interest"])->locale('fr')->toLetters();
				
				$data["verbal_trial.duration.fr"] = SpellNumber::value((float) $data["verbal_trial.duration"])->locale('fr')->toLetters();
				$data["verbal_trial.due_amount.fr"] = SpellNumber::value((float) $data["verbal_trial.due_amount"])->locale('fr')->toLetters();
				$data["total_to_pay"] = (int)((int) $data["total_amount_of_interest"] + (int) $data["verbal_trial.amount"] +(((int) $data["total_amount_of_interest"] / 100)*19));
				$data["total_to_pay.fr"] = SpellNumber::value((int) $data["total_to_pay"])->locale('fr')->toLetters();
				// dd($data["total_to_pay.fr"]);
				$data["echance.fr"] = SpellNumber::value((float) $data["verbal_trial.duration"])->locale('fr')->toLetters();
				$data["montant_second_ech.fr"] = SpellNumber::value((float) $data["montant_second_ech"])->locale('fr')->toLetters();
				$data["montant_heb"] = (int) ($data["montant_second_ech"] / 4);
				$data["montant_heb.fr"] = SpellNumber::value((int) $data["montant_heb"])->locale('fr')->toLetters();
				$data["montant_troisieme_ech.fr"] = SpellNumber::value((int) $data["montant_troisieme_ech"])->locale('fr')->toLetters();
				$data["montant_fudiciaire.fr"] = SpellNumber::value((int) $data["montant_fudiciaire"])->locale('fr')->toLetters();
				$data["verbal_trial.duration.fr"] = SpellNumber::value((float) $data["verbal_trial.duration"])->locale('fr')->toLetters();
				$data["signatory"] = (((float) $data["verbal_trial.amount"]) <= 10000000) ? "Madame Ameh Délali MESSANGAN épouse AMEDEMEGNAH, Responsable juridique" : "Mr. Koffi Djramedo GAMADO, Head Crédit";
				$data["verbal_trial.periodicity.fr"] = ["mensual" => "Mensuel", "quarterly" => "Trimestrielle", "semi-annual" => "Semestrielle", "annual" => "Annuel", "in-fine" => "A la fin"][$data["verbal_trial.periodicity"]];
				$data["verbal_trial.periodicity.fr2"] = ["mensual" => "chaque mois", "quarterly" => "chaque trimestre", "semi-annual" => "chaque semestre", "annual" => "chaque année", "in-fine" => "A la fin."][$data["verbal_trial.periodicity"]];
				$data["verbal_trial.periodicity.fr3"] = ["mensual" => "mensualité", "quarterly" => "trimestre", "semi-annual" => "semestre", "annual" => "année", "in-fine" => "echéance."][$data["verbal_trial.periodicity"]];
				$data["line_review_bonus"] = (((float) $data["verbal_trial.duration"]) < 18) ? "" : "Prime de révision de ligne      : « 1% du capital restant dû après 12 mois »";
				$data["representative_type_of_identity_document"] = [
					"cni" => "carte d'identité nationale",
					"passport" => "passeport",
					"residence_certificate" => "certificat de résidence",
					"driving_licence" => "permis de conduire",
					"carte_sej"=>"carte de séjour",
					"recep" =>"récépissé de la carte nationale d'identité "
				][$data["representative_type_of_identity_document"]];
				$data["verbal_trial.civility"] = [
					"Mr" => "Monsieur",
					"Mme" => "Madame",
					"Mlle" => "Mademoiselle"
					][$data["verbal_trial.civility"]];
				if(isset($data["individual_business.civility"])){
					$data["individual_business.civility"] = [
						"Mr" => "Monsieur",
						"Mme" => "Madame",
						"Mlle" => "Mademoiselle"
					][$data["individual_business.civility"]];
					// dd($data["individual_business.civility"]);
				}
				// dd('ok');
				if(isset($data["individual_business.type_of_identity_document"])){
					$data["individual_business.type_of_identity_document"] = [
						"cni" => "carte d'identité nationale",
						"passport" => "passeport",
						"residence_certificate" => "certificat de résidence",
						"driving_licence" => "permis de conduire",
						"carte_sej"=>"carte de séjour",
						"recep"=>"récépissé de la carte nationale d'identité "
					][$data["individual_business.type_of_identity_document"]];
				}
				if(isset($data["individual_business.type_of_identity_document"])){
					if($data["individual_business.type_of_identity_document"] == "carte_sej"){
						$data["carte"] = " de la";
					}
					if($data["individual_business.type_of_identity_document"] == "cni"){
						$data["carte"] = "de la";
					}
					if($data["individual_business.type_of_identity_document"] == "passport"){
						$data["carte"] = "du";
					}
				}
				
				// if($data["individual_business.type_of_identity_document"] == "recep" ||  $data["individual_business.type_of_identity_document"] == "passport" || $data["individual_business.type_of_identity_document"] == "driving_licence" || $data["individual_business.type_of_identity_document"] == "residence_certificate"){
				// 	$data["carte"] = "du";
				// }
				// if($data["individual_business.type_of_identity_document"] == "cni" || $data["individual_business.type_of_identity_document"] =="carte_sej")
				// {
				// 	$data["carte"] = "de la";
				// }
				
				// if($data["individual_business.type_of_identity_document"] == "residence_certificate"){
				// 	$data["carte"] = "le certificat de résidence";
				// }
				$data["frais_dossier"] = ((float)$data["verbal_trial.amount"] * (float) $data["verbal_trial.administrative_fees_percentage"]) / 100;
				$data["taux_mensuel"] = $data["verbal_trial.tax_fee_interest_rate"] / 12 ;
				// dd($data["taux_mensuel"]);
				// dd($data["frais_dossier"]);
				$data["verbal_trial.amount"] = number_format(((float) $data["verbal_trial.amount"]), 0, ',', ' ');
				$data["verbal_trial.day_due_amount"] = number_format(((float) $data["verbal_trial.day_due_amount"]), 0, ',', ' ');
				$data["total_amount_of_interest"] = number_format(((float) $data["total_amount_of_interest"]), 0, ',', ' ');
				$data["verbal_trial.due_amount"] = number_format(((float) $data["verbal_trial.due_amount"]), 0, ',', ' ');
				// $data["due_amount"] = number_format(((float) $data["due_amount"]), 0, ',', ' ');
				$data["montant_second_ech"] = (int) $data["montant_second_ech"];
				$data["montant_engagement"] = ((int) $data["montant_second_ech"] * 150) /100;
				$data["montant_engement_heb"] = (int) $data["montant_engagement"] / 4;
				$data["montant_engagement.fr"] = SpellNumber::value((int) $data["montant_engagement"])->locale('fr')->toLetters();
				$data["montant_engement_heb.fr"] = SpellNumber::value((int) $data["montant_engement_heb"])->locale('fr')->toLetters();
				// dd($data["montant_engement_heb"]);
				$data["due_amount.fr"] = SpellNumber::value((float) $data["due_amount"])->locale('fr')->toLetters();
				$data["due_amount"] = number_format(((float) $data["due_amount"]), 0, ',', ' ');
				$data["montant_engement_heb"] = number_format(((float) $data["montant_engement_heb"]), 0, ',', ' ');
				$data["montant_engagement"] = number_format(((float) $data["montant_engagement"]), 0, ',', ' ');
				// $data["total_to_pay"] = number_format(((float) $data["total_to_pay"]), 0, ',', ' ');
				$data["montant_troisieme_ech"] = number_format(((float) $data["montant_troisieme_ech"]), 0, ',', ' ');
				$data["montant_second_ech"] = number_format(((float) $data["montant_second_ech"]), 0, ',', ' ');
				$data["montant_fudiciaire"] = number_format(((float) $data["montant_fudiciaire"]), 0, ',', ' ');
				$data["verbal_trial.frais_administration"] = number_format(((float) $data["verbal_trial.frais_administration"]), 0, ',', ' ');
				// dd($data);
				

		
				
				// dd($data["montant_fudiciaire"]);
				// dd($data["montant_engagement"]);  
				// $data["verbal_trial.amount"] = (float) $data["verbal_trial.amount"];
				// $data["due_amount"] = number_format(((float) $data["due_amount"]), 0, ',', ' ');
				$data["verbal_trial.administrative_fees_percentage"] = number_format(((float) $data["verbal_trial.administrative_fees_percentage"]), 0, ',', ' ');
				$data["verbal_trial.insurance_premium"] = number_format(((float) $data["verbal_trial.insurance_premium"]), 0, ',', ' ');
				$data["total_to_pay"] = number_format(((float) $data["total_to_pay"]), 0, ',', ' ');
				$data["frais_dossier"] = number_format(((float) $data["frais_dossier"]), 0, ',', ' ');
				$data["taux_mensuel"] = number_format(((float) $data["taux_mensuel"]), 1, ',', ' ');
				$data["date_of_first_echeance"] = Carbon::createFromFormat('Y-m-d', $data["date_of_first_echeance"])->translatedFormat('d F Y');
				$data["date_of_last_echeance"] = Carbon::createFromFormat('Y-m-d', $data["date_of_last_echeance"])->translatedFormat('d F Y');
				$data["representative_birth_date"] = Carbon::createFromFormat('Y-m-d', $data["representative_birth_date"])->translatedFormat('d F Y');
				$data["representative_date_of_issue_of_identity_document"] = Carbon::createFromFormat('Y-m-d', $data["representative_date_of_issue_of_identity_document"])->translatedFormat('d F Y');
				if(isset($data["individual_business.date_naiss"])){
				$data["individual_business.date_naiss"] = Carbon::createFromFormat('Y-m-d', $data["individual_business.date_naiss"])->translatedFormat('d F Y');
				$data["individual_business.date_delivrance"] = Carbon::createFromFormat('Y-m-d', $data["individual_business.date_delivrance"])->translatedFormat('d F Y');
				}
				
				
				$guaranteeList = [];
				foreach ($contract->verbal_trial->guarantees as $guarantee) {
					$tmp = $guarantee->toArray();
					$guaranteeList[] = array_merge($tmp, collect($guarantee->type_of_guarantee)->mapWithKeys(function ($value, $key) {
						return ['type_of_guarantee.' . $key => $value];
					})->all());
				}
				// dd($guaranteeList);
				
				$garants = [];
				if (!empty($contract->guarantors)) {
					foreach ($contract->guarantors as $garant) {
						// Vérifie si c'est un objet avant d'utiliser toArray
						if (is_object($garant) && method_exists($garant, 'toArray')) {
							$garants[] = $garant->toArray();
						} else {
							// Si c'est déjà un tableau, on l'ajoute directement
							$garants[] = $garant;
						}
					}
				}
				// if(isset($contract->verbal_trial->pep)){
				// 	$data["pep"] = $contract->verbal_trial->pep->toArray();
				// 	// dd($data);
				// }
				if(isset($contract->verbal_trial->pep)){
					// $data["pep"] = $contract->verbal_trial->pep->toArray();
					$data = array_merge($data, collect($contract->verbal_trial->pep)->mapWithKeys(function ($value, $key) {
						return ['pep.' . $key => $value];
					})->all());
				}
				if(isset($contract->verbal_trial->pah)){
					// $data["pep"] = $contract->verbal_trial->pep->toArray();
					$data = array_merge($data, collect($contract->verbal_trial->pah)->mapWithKeys(function ($value, $key) {
						return ['pah.' . $key => $value];
					})->all());
				}
				// dd($contract->verbal_trial->pledge);
				
				if(isset($contract->verbal_trial->pledge)){
					$data = array_merge($data, collect($contract->verbal_trial->pledge)->mapWithKeys(function ($value, $key) {
						return ['pledge.' . $key => $value];
					})->all());
				}
				if(isset($data["pledge.identity_document"])){
					$data["pledge.identity_document"] = [
						"cni" => "carte d'identité nationale",
						"passport" => "passeport",
						"residence_certificate" => "certificat de résidence",
						"driving_licence" => "permis de conduire",
						"carte_sej"=>"carte de séjour",
						"recep"=>"récépissé de la carte nationale d'identité "
					][$data["pledge.identity_document"]];
					$data["pledge.civility"] = [
						"Mr" => "Monsieur",
						"Mme" => "Madame",
						"Mlle" => "Mademoiselle"
					][$data["pledge.civility"]];
				}
				// dd($contract->verbal_trial->pledges);
				if(isset($data["pep.montant"])){

					$data["pep.montant.fr"] = SpellNumber::value((float) $data["pep.montant"])->locale('fr')->toLetters();
					$data["pep.date_debut"] = Carbon::createFromFormat('Y-m-d', $data["pep.date_debut"])->translatedFormat('d F Y');
					$data["pep.montant"] = number_format(((float) $data["pep.montant"]), 0, ',', ' ');

				}
				// dd($data["pep.date_debut"]);
				if(isset($data["pledge.montant_estime"])){

					$data["pledge.montant_estime.fr"] = SpellNumber::value((float) $data["pledge.montant_estime"])->locale('fr')->toLetters();
					$data["pledge.date_naiss"] = Carbon::createFromFormat('Y-m-d', $data["pledge.date_naiss"])->translatedFormat('d F Y');
					$data["pledge.date_delivery_document"] = Carbon::createFromFormat('Y-m-d', $data["pledge.date_delivery_document"])->translatedFormat('d F Y');
					$data["pledge.date_carte_crise"] = Carbon::createFromFormat('Y-m-d', $data["pledge.date_carte_crise"])->translatedFormat('d F Y');
					$data["pledge.date_mise_en_circulation"] = Carbon::createFromFormat('Y-m-d', $data["pledge.date_mise_en_circulation"])->translatedFormat('d F Y');
					$data["pledge.montant_estime"] = number_format(((float) $data["pledge.montant_estime"]), 0, ',', ' ');


				}

				if(isset($data["pah.montant_terrain"])){

					$data["pah.montant_terrain.fr"] = SpellNumber::value((float) $data["pah.montant_terrain"])->locale('fr')->toLetters();
				}
				// dd($data);	
				// dd($guaranteeList);
				// dd($data["verbal_trial.type_of_credit.name"]);
				$templateProcessor->cloneBlock('guaranteeList', 0, true, false, $guaranteeList);
				$templateProcessor1->cloneBlock('guaranteeList', 0, true, false, $guaranteeList);
				$templateProcessor2->cloneBlock('guaranteeList', 0, true, false, $guaranteeList);
				$templateProcessor3->cloneBlock('guaranteeList', 0, true, false, $guaranteeList);
				$templateProcessor4->cloneBlock('guaranteeList', 0, true, false, $guaranteeList);
				if($contract->type == "particular")
				{
					$templateProcessor30->cloneBlock('guaranteeList', 0, true, false, $guaranteeList);
					$templateProcessor31->cloneBlock('guaranteeList', 0, true, false, $guaranteeList);
					$templateProcessor32->cloneBlock('guaranteeList', 0, true, false, $guaranteeList);
				}
				
				
				if ($contract->has_pledges == "1") {
					// dd("ok");
					$pledgeList = [];
					foreach ($contract->pledges as $pledge) {
						$tmp = $pledge->toArray();
						$tmp["type.fr"] = ["vehicle" => "véhicule", "stock" => "stock"][$tmp["type"]];
						$pledgeList[] = array_merge($tmp, collect($pledge->type_of_pledge)->mapWithKeys(function ($value, $key) {
							return ['pledge.' . $key => $value];
						})->all());
					}
					$data["vehicleCount"] = $contract->pledges()->where('type', 'vehicle')->count();
					$data["stockCount"] = $contract->pledges()->where('type', 'stock')->count();
					$data["number_pledge.fr"] = "";
					if ($data["vehicleCount"] > 0) {
						$data["number_pledge.fr"] .= SpellNumber::value((float) $data["vehicleCount"])->locale('fr')->toLetters() . " véhicule(s)";
					}
	
					if ($data["stockCount"] > 0) {
						$data["number_pledge.fr"] .= ($data["vehicleCount"] > 0) ? " et " : "";
						$data["number_pledge.fr"] .= SpellNumber::value((float) $data["stockCount"])->locale('fr')->toLetters() . " Stock(s)";
					}
					$templateProcessor->cloneBlock('pledgeList', 0, true, false, $pledgeList);
					// dd($pledgeList);
				}
				

				unset($data["observations"]);
				unset($data["guarantors"]);
				// dd($data["verbal_trial.type_of_credit.name"]);
				$templateProcessor->setValues($data);
				$templateProcessor1->setValues($data);
				$templateProcessor2->setValues($data);
				$templateProcessor3->setValues($data);
				$templateProcessor4->setValues($data);
				if($contract->type == "particular"){

					$templateProcessor30->setValues($data);
					$templateProcessor31->setValues($data);
					$templateProcessor32->setValues($data);
				}
				if($contract->type == "particular" && $data["verbal_trial.type_of_credit.name"] == "CREDIT  CONSO"){
					$templateProcessor5->cloneBlock('guaranteeList', 0, true, false, $guaranteeList);
					$templateProcessor6->cloneBlock('guaranteeList', 0, true, false, $guaranteeList);
					$templateProcessor7->cloneBlock('guaranteeList', 0, true, false, $guaranteeList);
					$templateProcessor8->cloneBlock('guaranteeList', 0, true, false, $guaranteeList);
					$templateProcessor9->cloneBlock('guaranteeList', 0, true, false, $guaranteeList);
					$templateProcessor10->cloneBlock('guaranteeList', 0, true, false, $guaranteeList);
					$templateProcessor12->cloneBlock('guaranteeList', 0, true, false, $guaranteeList);
					// dd("ok");
					$templateProcessor5->setValues($data);
					$templateProcessor6->setValues($data);
					$templateProcessor7->setValues($data);
					$templateProcessor8->setValues($data);
					$templateProcessor9->setValues($data);
					$templateProcessor10->setValues($data);
					$templateProcessor11->setValues($data);
					$templateProcessor12->setValues($data);
				}
				if($contract->type == "particular" && ($data["verbal_trial.type_of_credit.name"] == "PP COMMERCANT" || $data["verbal_trial.type_of_credit.name"] == "COMMERCANT PP"))
					{
						$templateProcessor8->cloneBlock('guaranteeList', 0, true, false, $guaranteeList);
						$templateProcessor9->cloneBlock('guaranteeList', 0, true, false, $guaranteeList);
						$templateProcessor10->cloneBlock('guaranteeList', 0, true, false, $guaranteeList);
						$templateProcessor8->setValues($data);
						$templateProcessor9->setValues($data);
						$templateProcessor10->setValues($data);
					}
				if($contract->type == "particular" && ($data["verbal_trial.type_of_credit.name"] =="CREDIT CONSO/IMMO"))
					{
						$templateProcessor40->cloneBlock('guaranteeList', 0, true, false, $guaranteeList);
						$templateProcessor41->cloneBlock('guaranteeList', 0, true, false, $guaranteeList);
						$templateProcessor42->cloneBlock('guaranteeList', 0, true, false, $guaranteeList);
						$templateProcessor43->cloneBlock('guaranteeList', 0, true, false, $guaranteeList);
						$templateProcessor44->cloneBlock('guaranteeList', 0, true, false, $guaranteeList);
						$templateProcessor45->cloneBlock('guaranteeList', 0, true, false, $guaranteeList);
						$templateProcessor40->setValues($data);
						$templateProcessor41->setValues($data);
						$templateProcessor42->setValues($data);
						$templateProcessor43->setValues($data);
						$templateProcessor44->setValues($data);
						$templateProcessor45->setValues($data);

					}
				if($contract->type == "individual_business" && ($data["verbal_trial.type_of_credit.name"] == "CREDIT FDR" || $data["verbal_trial.type_of_credit.name"] == "CREDIT  BFR"))
					{
						// dd("ooook");
						$templateProcessor5->cloneBlock('guaranteeList', 0, true, false, $guaranteeList);
						$templateProcessor6->cloneBlock('guaranteeList', 0, true, false, $guaranteeList);
						$templateProcessor7->cloneBlock('guaranteeList', 0, true, false, $guaranteeList);
						$templateProcessor8->cloneBlock('guaranteeList', 0, true, false, $guaranteeList);
						$templateProcessor9->cloneBlock('guaranteeList', 0, true, false, $guaranteeList);
						$templateProcessor10->cloneBlock('guaranteeList', 0, true, false, $guaranteeList);
						$templateProcessor11->cloneBlock('guaranteeList', 0, true, false, $guaranteeList);
						$templateProcessor12->cloneBlock('guaranteeList', 0, true, false, $guaranteeList);
						$templateProcessor13->cloneBlock('guaranteeList', 0, true, false, $guaranteeList);
						$templateProcessor14->cloneBlock('guaranteeList', 0, true, false, $guaranteeList);
						$templateProcessor5->setValues($data);
						$templateProcessor6->setValues($data);
						$templateProcessor7->setValues($data);
						$templateProcessor8->setValues($data);
						$templateProcessor9->setValues($data);
						$templateProcessor10->setValues($data);
						$templateProcessor11->setValues($data);
						$templateProcessor12->setValues($data);
						$templateProcessor13->setValues($data);
						$templateProcessor14->setValues($data);

					}
				if($contract->type == "individual_business" && ($data["verbal_trial.type_of_credit.name"] == "CREDIT D'INVESTISSEMENT" ||
						$data["verbal_trial.type_of_credit.name"] == "AVANCE MARCHE/BC" || $data["verbal_trial.type_of_credit.name"] == "AVANCE SUR FACTURE" ||
						$data["verbal_trial.type_of_credit.name"] == "PP COMMERCANT"))
						{
							$templateProcessor9->cloneBlock('guaranteeList', 0, true, false, $guaranteeList);
							$templateProcessor10->cloneBlock('guaranteeList', 0, true, false, $guaranteeList);
							$templateProcessor11->cloneBlock('guaranteeList', 0, true, false, $guaranteeList);
							$templateProcessor12->cloneBlock('guaranteeList', 0, true, false, $guaranteeList);
							$templateProcessor13->cloneBlock('guaranteeList', 0, true, false, $guaranteeList);
							$templateProcessor14->cloneBlock('guaranteeList', 0, true, false, $guaranteeList);
							$templateProcessor15->cloneBlock('guaranteeList', 0, true, false, $guaranteeList);
							$templateProcessor16->cloneBlock('guaranteeList', 0, true, false, $guaranteeList);
							$templateProcessor17->cloneBlock('guaranteeList', 0, true, false, $guaranteeList);
							$templateProcessor18->cloneBlock('guaranteeList', 0, true, false, $guaranteeList);
							$templateProcessor9->setValues($data);
							$templateProcessor10->setValues($data);
							$templateProcessor11->setValues($data);
							$templateProcessor12->setValues($data);
							$templateProcessor13->setValues($data);
							$templateProcessor14->setValues($data);
							$templateProcessor15->setValues($data);
							$templateProcessor16->setValues($data);
							$templateProcessor17->setValues($data);
							$templateProcessor18->setValues($data);
						}
			
				if($contract->type == "company" && ($data["verbal_trial.type_of_credit.name"] == "AVANCE SUR FACTURE" ||
					$data["verbal_trial.type_of_credit.name"] == "AVANCE MARCHE/BC" ||
					$data["verbal_trial.type_of_credit.name"] == "AVANCE MARCHE/BC_SOLO"))
					{
						// dd("ok");

						$templateProcessor5->cloneBlock('guaranteeList', 0, true, false, $guaranteeList);
						$templateProcessor6->cloneBlock('guaranteeList', 0, true, false, $guaranteeList);
						$templateProcessor7->cloneBlock('guaranteeList', 0, true, false, $guaranteeList);
						$templateProcessor8->cloneBlock('guaranteeList', 0, true, false, $guaranteeList);
						$templateProcessor11->cloneBlock('guaranteeList', 0, true, false, $guaranteeList);
						$templateProcessor12->cloneBlock('guaranteeList', 0, true, false, $guaranteeList);
						$templateProcessor13->cloneBlock('guaranteeList', 0, true, false, $guaranteeList);
						$templateProcessor14->cloneBlock('guaranteeList', 0, true, false, $guaranteeList);
						$templateProcessor15->cloneBlock('guaranteeList', 0, true, false, $guaranteeList);
						$templateProcessor16->cloneBlock('guaranteeList', 0, true, false, $guaranteeList);
						$templateProcessor17->cloneBlock('guaranteeList', 0, true, false, $guaranteeList);
						// $templateProcessor9->cloneBlock('guaranteeList', 0, true, false, $guaranteeList);
						$templateProcessor5->setValues($data);
						$templateProcessor6->setValues($data);
						$templateProcessor7->setValues($data);
						$templateProcessor8->setValues($data);
						$templateProcessor11->setValues($data);
						$templateProcessor12->setValues($data);
						$templateProcessor13->setValues($data);
						$templateProcessor14->setValues($data);
						$templateProcessor15->setValues($data);
						$templateProcessor16->setValues($data);
						$templateProcessor17->setValues($data);
						// $templateProcessor9->setValues($data);
					}
				// dd($data["verbal_trial.type_of_credit.name"]);
				// dd($contract->type);

				if($contract->type == "company" && ($data["verbal_trial.type_of_credit.name"] == "CREDIT  CONSO" || $data["verbal_trial.type_of_credit.name"] = "CREDIT CONSO/IMMO"))
					{

						// dd("ok");
						$templateProcessor5->cloneBlock('guaranteeList', 0, true, false, $guaranteeList);
						$templateProcessor6->cloneBlock('guaranteeList', 0, true, false, $guaranteeList);
						$templateProcessor7->cloneBlock('guaranteeList', 0, true, false, $guaranteeList);
						$templateProcessor8->cloneBlock('guaranteeList', 0, true, false, $guaranteeList);
						$templateProcessor9->cloneBlock('guaranteeList', 0, true, false, $guaranteeList);
						$templateProcessor10->cloneBlock('guaranteeList', 0, true, false, $guaranteeList);
						$templateProcessor11->cloneBlock('guaranteeList', 0, true, false, $guaranteeList);
						$templateProcessor12->cloneBlock('guaranteeList', 0, true, false, $guaranteeList);
						$templateProcessor13->cloneBlock('guaranteeList', 0, true, false, $guaranteeList);
						$templateProcessor14->cloneBlock('guaranteeList', 0, true, false, $guaranteeList);
						$templateProcessor15->cloneBlock('guaranteeList', 0, true, false, $guaranteeList);
						$templateProcessor16->cloneBlock('guaranteeList', 0, true, false, $guaranteeList);
						$templateProcessor17->cloneBlock('guaranteeList', 0, true, false, $guaranteeList);
						$templateProcessor18->cloneBlock('guaranteeList', 0, true, false, $guaranteeList);
						$templateProcessor19->cloneBlock('guaranteeList', 0, true, false, $guaranteeList);
						$templateProcessor5->setValues($data);
						$templateProcessor6->setValues($data);
						$templateProcessor7->setValues($data);
						$templateProcessor8->setValues($data);
						$templateProcessor9->setValues($data);
						$templateProcessor10->setValues($data);
						$templateProcessor12->setValues($data);
						$templateProcessor11->setValues($data);
						$templateProcessor13->setValues($data);
						$templateProcessor14->setValues($data);
						$templateProcessor15->setValues($data);
						$templateProcessor16->setValues($data);
						$templateProcessor17->setValues($data);
						$templateProcessor18->setValues($data);
						$templateProcessor19->setValues($data);
					}
				

				// Enregistrez les modifications dans un nouveau fichier


				$outputFilePath = public_path("Contrat_gage-" . $contract->verbal_trial->applicant_first_name_.$contract->verbal_trial->applicant_last_name.".docx");
				$outputFilePath1 = public_path("Billet-" . $contract->verbal_trial->applicant_first_name_.$contract->verbal_trial->applicant_last_name . ".docx");
				$outputFilePath2 = public_path("Declaration_cession-" . $contract->verbal_trial->applicant_first_name_.$contract->verbal_trial->applicant_last_name . ".docx");
				$outputFilePath3 = public_path("Contrat_PEP-" . $contract->verbal_trial->applicant_first_name_.$contract->verbal_trial->applicant_last_name . ".docx");
				$outputFilePath4 = public_path("Contrat_tranfert_fudiciaire-" . $contract->verbal_trial->applicant_first_name_.$contract->verbal_trial->applicant_last_name . ".docx");
				$outputFilePath5= public_path("Abandon_de_droit-" . $contract->verbal_trial->applicant_first_name_.$contract->verbal_trial->applicant_last_name . ".docx");
				$outputFilePath6= public_path("Contrat_de_delegation-" . $contract->verbal_trial->applicant_first_name_.$contract->verbal_trial->applicant_last_name . ".docx");
				$outputFilePath7= public_path("Contrat_de_natissement-" . $contract->verbal_trial->applicant_first_name_.$contract->verbal_trial->applicant_last_name . ".docx");
				$outputFilePath8= public_path("Collecte-" . $contract->verbal_trial->applicant_first_name_.$contract->verbal_trial->applicant_last_name . ".docx");
				$outputFilePath9= public_path("Engagement_domiciliation-" . $contract->verbal_trial->applicant_first_name_.$contract->verbal_trial->applicant_last_name . ".docx");
				$outputFilePath10= public_path("Billet_a_ordre-" . $contract->verbal_trial->applicant_first_name_.$contract->verbal_trial->applicant_last_name . ".docx");
				$outputFilePath11= public_path("Contrat_cautionnement-personne-physique-" . $contract->verbal_trial->applicant_first_name_.$contract->verbal_trial->applicant_last_name . ".docx");
				$outputFilePath12= public_path("Contrat_de_pret_personne_morale" . $contract->verbal_trial->applicant_first_name_.$contract->verbal_trial->applicant_last_name . ".docx");
				$outputFilePath13= public_path("Contrat-" . $contract->verbal_trial->applicant_first_name_.$contract->verbal_trial->applicant_last_name . ".docx");
				$outputFilePath14= public_path("Contrat-personne-morale-" . $contract->verbal_trial->applicant_first_name_.$contract->verbal_trial->applicant_last_name . ".docx");
				$outputFilePath15= public_path("Contrat_pret-personne-morale-" . $contract->verbal_trial->applicant_first_name_.$contract->verbal_trial->applicant_last_name . ".docx");
				$outputFilePath16= public_path("Contrat_nantissement-" . $contract->verbal_trial->applicant_first_name_.$contract->verbal_trial->applicant_last_name . ".docx");
				$outputFilePath17= public_path("Billet_a_ordre-" . $contract->verbal_trial->applicant_first_name_.$contract->verbal_trial->applicant_last_name . ".docx");
				$outputFilePath18= public_path("Note_information-" . $contract->verbal_trial->applicant_first_name_.$contract->verbal_trial->applicant_last_name . ".docx");
				$outputFilePath19= public_path("Contrat_PAH-" . $contract->verbal_trial->applicant_first_name_.$contract->verbal_trial->applicant_last_name . ".docx");
				$outputFilePath20= public_path("RCCM_LOYERS-" . $contract->verbal_trial->applicant_first_name_.$contract->verbal_trial->applicant_last_name . ".docx");
				$outputFilePath21= public_path("Attestation_capacite-" . $contract->verbal_trial->applicant_first_name_.$contract->verbal_trial->applicant_last_name . ".docx");
				$outputFilePath22= public_path("RCCM-" . $contract->verbal_trial->applicant_first_name_.$contract->verbal_trial->applicant_last_name . ".docx");
				$outputFilePath23= public_path("Nantissement_de_compte_bancaire-" . $contract->verbal_trial->applicant_first_name_.$contract->verbal_trial->applicant_last_name . ".docx");
				$outputFilePath24= public_path("Delegation-" . $contract->verbal_trial->applicant_first_name_.$contract->verbal_trial->applicant_last_name . ".docx");
				$outputFilePath25= public_path("Nantissement_de_creances-" . $contract->verbal_trial->applicant_first_name_.$contract->verbal_trial->applicant_last_name . ".docx");
				$outputFilePath26= public_path("Contrat_gage_autre-" . $contract->verbal_trial->applicant_first_name_.$contract->verbal_trial->applicant_last_name . ".docx");
				$outputFilePath27= public_path("Collecte_fiche-" . $contract->verbal_trial->applicant_first_name_.$contract->verbal_trial->applicant_last_name . ".docx");
				$outputFilePath28= public_path("Attestation_sur_honneur-" . $contract->verbal_trial->applicant_first_name_.$contract->verbal_trial->applicant_last_name . ".docx");
				$outputFilePath29= public_path("Contrat_de_nantissement_de_fonds_de_commerce-" . $contract->verbal_trial->applicant_first_name_.$contract->verbal_trial->applicant_last_name . ".docx");
				$outputFilePath30= public_path("Contrat_de_gage_du_stock-" . $contract->verbal_trial->applicant_first_name_.$contract->verbal_trial->applicant_last_name . ".docx");
				$outputFilePath31= public_path("Contrat_de_gage_autre-" . $contract->verbal_trial->applicant_first_name_.$contract->verbal_trial->applicant_last_name . ".docx");
				$this->saveAndProtect($templateProcessor, $outputFilePath);
				$this->saveAndProtect($templateProcessor1, $outputFilePath1);
				$this->saveAndProtect($templateProcessor2, $outputFilePath2);
				$this->saveAndProtect($templateProcessor3, $outputFilePath3);
				$this->saveAndProtect($templateProcessor4, $outputFilePath4);
				if($contract->type == "particular"){
					$this->saveAndProtect($templateProcessor30, $outputFilePath13);
					$this->saveAndProtect($templateProcessor31, $outputFilePath19);
					$this->saveAndProtect($templateProcessor32, $outputFilePath26);

				}
				if($contract->type == "particular" && $data["verbal_trial.type_of_credit.name"] == "CREDIT  CONSO"){
					// $templateProcessor5->setValues($data);
					$this->saveAndProtect($templateProcessor5, $outputFilePath5);
					$this->saveAndProtect($templateProcessor6, $outputFilePath6);
					$this->saveAndProtect($templateProcessor7, $outputFilePath7);
					$this->saveAndProtect($templateProcessor8, $outputFilePath13);
					$this->saveAndProtect($templateProcessor9, $outputFilePath18);
					$this->saveAndProtect($templateProcessor10, $outputFilePath19);
					$this->saveAndProtect($templateProcessor11, $outputFilePath20);
					$this->saveAndProtect($templateProcessor12, $outputFilePath28);
					// dd("ok");

				}
				if($contract->type == "particular" &&($data["verbal_trial.type_of_credit.name"] == "PP COMMERCANT" || $data["verbal_trial.type_of_credit.name"] == "COMMERCANT PP"))
					{
						$this->saveAndProtect($templateProcessor8, $outputFilePath8);
						$this->saveAndProtect($templateProcessor9, $outputFilePath9);
						$this->saveAndProtect($templateProcessor10, $outputFilePath13);
					}
				if($contract->type == "particular" && ($data["verbal_trial.type_of_credit.name"] =="CREDIT CONSO/IMMO"))
					{
						
						$this->saveAndProtect($templateProcessor40, $outputFilePath8);
						$this->saveAndProtect($templateProcessor41, $outputFilePath24);
						$this->saveAndProtect($templateProcessor42, $outputFilePath25);
						$this->saveAndProtect($templateProcessor43, $outputFilePath22);
						$this->saveAndProtect($templateProcessor44, $outputFilePath18);
						$this->saveAndProtect($templateProcessor45, $outputFilePath28);

					}
				
				if($contract->type == "individual_business" && ($data["verbal_trial.type_of_credit.name"] == "CREDIT FDR" || $data["verbal_trial.type_of_credit.name"] == "CREDIT  BFR"))
					{
						// dd("dedans");
						$this->saveAndProtect($templateProcessor5, $outputFilePath10);
						$this->saveAndProtect($templateProcessor6, $outputFilePath11);
						$this->saveAndProtect($templateProcessor7, $outputFilePath12);
						$this->saveAndProtect($templateProcessor8, $outputFilePath13);
						$this->saveAndProtect($templateProcessor9, $outputFilePath8);
						$this->saveAndProtect($templateProcessor10, $outputFilePath19);
						$this->saveAndProtect($templateProcessor11, $outputFilePath27);
						$this->saveAndProtect($templateProcessor12, $outputFilePath5);
						$this->saveAndProtect($templateProcessor13, $outputFilePath30);
						$this->saveAndProtect($templateProcessor14, $outputFilePath29);
					}
				if($contract->type == "individual_business" && ($data["verbal_trial.type_of_credit.name"] == "CREDIT D'INVESTISSEMENT" ||
					$data["verbal_trial.type_of_credit.name"] == "AVANCE MARCHE/BC" || $data["verbal_trial.type_of_credit.name"] == "AVANCE SUR FACTURE" ||
					$data["verbal_trial.type_of_credit.name"] == "PP COMMERCANT"))
					{
						$this->saveAndProtect($templateProcessor9, $outputFilePath11);
						$this->saveAndProtect($templateProcessor10, $outputFilePath15);
						$this->saveAndProtect($templateProcessor11, $outputFilePath16);
						$this->saveAndProtect($templateProcessor12, $outputFilePath17);
						$this->saveAndProtect($templateProcessor13, $outputFilePath13);
						$this->saveAndProtect($templateProcessor14, $outputFilePath20);
						$this->saveAndProtect($templateProcessor15, $outputFilePath19);
						$this->saveAndProtect($templateProcessor16, $outputFilePath9);
						$this->saveAndProtect($templateProcessor17, $outputFilePath5);
						$this->saveAndProtect($templateProcessor18, $outputFilePath27);
					}
			
				if($contract->type == "company" && ($data["verbal_trial.type_of_credit.name"] == "AVANCE SUR FACTURE" ||
					$data["verbal_trial.type_of_credit.name"] == "AVANCE MARCHE/BC" ||
					$data["verbal_trial.type_of_credit.name"] == "AVANCE MARCHE/BC_SOLO"))
					{
						
						$this->saveAndProtect($templateProcessor5, $outputFilePath21);
						$this->saveAndProtect($templateProcessor6, $outputFilePath22);
						$this->saveAndProtect($templateProcessor7, $outputFilePath16);
						$this->saveAndProtect($templateProcessor8, $outputFilePath13);
						$this->saveAndProtect($templateProcessor11, $outputFilePath19);
						$this->saveAndProtect($templateProcessor12, $outputFilePath10);
						$this->saveAndProtect($templateProcessor13, $outputFilePath8);
						$this->saveAndProtect($templateProcessor14, $outputFilePath11);
						$this->saveAndProtect($templateProcessor15, $outputFilePath5);
						$this->saveAndProtect($templateProcessor16, $outputFilePath27);
						$this->saveAndProtect($templateProcessor17, $outputFilePath29);
						// $templateProcessor9->saveAs($outputFilePath8);
					}
				if($contract->type == "company" && ($data["verbal_trial.type_of_credit.name"] == "CREDIT  CONSO" || $data["verbal_trial.type_of_credit.name"] = "CREDIT CONSO/IMMO"))
					{
						// dd("ok");
						$this->saveAndProtect($templateProcessor5, $outputFilePath21);
						$this->saveAndProtect($templateProcessor6, $outputFilePath22);
						$this->saveAndProtect($templateProcessor7, $outputFilePath16);
						$this->saveAndProtect($templateProcessor8, $outputFilePath13);
						$this->saveAndProtect($templateProcessor9, $outputFilePath23);
						$this->saveAndProtect($templateProcessor10, $outputFilePath9);
						$this->saveAndProtect($templateProcessor11, $outputFilePath19);
						$this->saveAndProtect($templateProcessor12, $outputFilePath10);
						$this->saveAndProtect($templateProcessor13, $outputFilePath8);
						$this->saveAndProtect($templateProcessor14, $outputFilePath11);
						$this->saveAndProtect($templateProcessor15, $outputFilePath5);
						$this->saveAndProtect($templateProcessor16, $outputFilePath27);
						$this->saveAndProtect($templateProcessor17, $outputFilePath29);
						$this->saveAndProtect($templateProcessor18, $outputFilePath30);
						$this->saveAndProtect($templateProcessor19, $outputFilePath31);
					}

				// Vérifiez si les fichiers ont été créés

				if (file_exists($outputFilePath) && file_exists($outputFilePath1)) {
					$zip->addFile($outputFilePath, basename($outputFilePath));
					$zip->addFile($outputFilePath1, basename($outputFilePath1));
					$zip->addFile($outputFilePath2, basename($outputFilePath2));
					$zip->addFile($outputFilePath3, basename($outputFilePath3));
					$zip->addFile($outputFilePath4, basename($outputFilePath4));
					if($contract->type == "particular"){
						$zip->addFile($outputFilePath13, basename($outputFilePath13));
						$zip->addFile($outputFilePath19, basename($outputFilePath19));
						$zip->addFile($outputFilePath26, basename($outputFilePath26));
					}
					if($contract->type == "particular" && $data["verbal_trial.type_of_credit.name"] == "CREDIT  CONSO"){
						// $templateProcessor5->setValues($data);
						$zip->addFile($outputFilePath5, basename($outputFilePath5));
						$zip->addFile($outputFilePath6, basename($outputFilePath6));
						$zip->addFile($outputFilePath7, basename($outputFilePath7));
						$zip->addFile($outputFilePath13, basename($outputFilePath13));
						$zip->addFile($outputFilePath18, basename($outputFilePath18));
						$zip->addFile($outputFilePath19, basename($outputFilePath19));
						$zip->addFile($outputFilePath20, basename($outputFilePath20));
						$zip->addFile($outputFilePath28, basename($outputFilePath28));
					}
					if($contract->type == "particular" &&($data["verbal_trial.type_of_credit.name"] == "PP COMMERCANT" || $data["verbal_trial.type_of_credit.name"] == "COMMERCANT PP"))
						{
							$zip->addFile($outputFilePath8, basename($outputFilePath8));
							$zip->addFile($outputFilePath9, basename($outputFilePath9));
							$zip->addFile($outputFilePath13, basename($outputFilePath13));

						}
				if($contract->type == "particular" && ($data["verbal_trial.type_of_credit.name"] =="CREDIT CONSO/IMMO"))
					{
						$zip->addFile($outputFilePath8, basename($outputFilePath8));
						$zip->addFile($outputFilePath24, basename($outputFilePath24));
						$zip->addFile($outputFilePath25, basename($outputFilePath25));
						$zip->addFile($outputFilePath22, basename($outputFilePath22));
						$zip->addFile($outputFilePath18, basename($outputFilePath18));
						$zip->addFile($outputFilePath28, basename($outputFilePath28));
					}
						if($contract->type == "individual_business" && ($data["verbal_trial.type_of_credit.name"] == "CREDIT FDR" || $data["verbal_trial.type_of_credit.name"] == "CREDIT  BFR"))
						{
							// dd("ok");
							$zip->addFile($outputFilePath10, basename($outputFilePath10));
							$zip->addFile($outputFilePath11, basename($outputFilePath11));
							$zip->addFile($outputFilePath12, basename($outputFilePath12));
							$zip->addFile($outputFilePath13, basename($outputFilePath13));
							$zip->addFile($outputFilePath8, basename($outputFilePath8));
							$zip->addFile($outputFilePath19, basename($outputFilePath19));
							$zip->addFile($outputFilePath27, basename($outputFilePath27));
							$zip->addFile($outputFilePath5, basename($outputFilePath5));
							$zip->addFile($outputFilePath30, basename($outputFilePath30));
							$zip->addFile($outputFilePath29, basename($outputFilePath29));	
							
						}
					if($contract->type == "individual_business" && ($data["verbal_trial.type_of_credit.name"] == "CREDIT D'INVESTISSEMENT" ||
						$data["verbal_trial.type_of_credit.name"] == "AVANCE MARCHE/BC" || $data["verbal_trial.type_of_credit.name"] == "AVANCE SUR FACTURE" ||
						$data["verbal_trial.type_of_credit.name"] == "PP COMMERCANT"))
						{
							$zip->addFile($outputFilePath11, basename($outputFilePath11));
							$zip->addFile($outputFilePath15, basename($outputFilePath15));
							$zip->addFile($outputFilePath16, basename($outputFilePath16));
							$zip->addFile($outputFilePath17, basename($outputFilePath17));
							$zip->addFile($outputFilePath13, basename($outputFilePath13));
							$zip->addFile($outputFilePath20, basename($outputFilePath20));
							$zip->addFile($outputFilePath19, basename($outputFilePath19));
							$zip->addFile($outputFilePath9, basename($outputFilePath9));
							$zip->addFile($outputFilePath9, basename($outputFilePath9));
							$zip->addFile($outputFilePath9, basename($outputFilePath9));
							$zip->addFile($outputFilePath5, basename($outputFilePath5));
							$zip->addFile($outputFilePath27, basename($outputFilePath27));

						}
				
				if($contract->type =="company" && ($data["verbal_trial.type_of_credit.name"] == "AVANCE SUR FACTURE" ||
					$data["verbal_trial.type_of_credit.name"] == "AVANCE MARCHE/BC" || 
					$data["verbal_trial.type_of_credit.name"] == "AVANCE MARCHE/BC_SOLO"))
					{

						// dd("ok");
						$zip->addFile($outputFilePath21,basename($outputFilePath21));
						$zip->addFile($outputFilePath22,basename($outputFilePath22));
						$zip->addFile($outputFilePath16,basename($outputFilePath16));
						$zip->addFile($outputFilePath13,basename($outputFilePath13));
						$zip->addFile($outputFilePath19,basename($outputFilePath19));
						$zip->addFile($outputFilePath10,basename($outputFilePath10));
						$zip->addFile($outputFilePath8,basename($outputFilePath8));
						$zip->addFile($outputFilePath11,basename($outputFilePath11));
						$zip->addFile($outputFilePath5,basename($outputFilePath5));
						$zip->addFile($outputFilePath27,basename($outputFilePath27));
						$zip->addFile($outputFilePath29,basename($outputFilePath29));
						// $zip->addFile($outputFilePath8,basename($outputFilePath8));
					}
				if($contract->type == "company" && ($data["verbal_trial.type_of_credit.name"] == "CREDIT  CONSO" || $data["verbal_trial.type_of_credit.name"] = "CREDIT CONSO/IMMO"))
					{
						// dd($data);
						$zip->addFile($outputFilePath21,basename($outputFilePath21));
						$zip->addFile($outputFilePath22,basename($outputFilePath22));
						$zip->addFile($outputFilePath16,basename($outputFilePath16));
						$zip->addFile($outputFilePath13,basename($outputFilePath13));
						$zip->addFile($outputFilePath23,basename($outputFilePath23));
						$zip->addFile($outputFilePath9,basename($outputFilePath9));
						$zip->addFile($outputFilePath19,basename($outputFilePath19));
						$zip->addFile($outputFilePath10,basename($outputFilePath10));
						$zip->addFile($outputFilePath8,basename($outputFilePath8));
						$zip->addFile($outputFilePath11,basename($outputFilePath11));
						$zip->addFile($outputFilePath5,basename($outputFilePath5));
						$zip->addFile($outputFilePath27,basename($outputFilePath27));
						$zip->addFile($outputFilePath29,basename($outputFilePath29));
						$zip->addFile($outputFilePath30,basename($outputFilePath30));
						$zip->addFile($outputFilePath31,basename($outputFilePath31));
						
					}
					$zip->close();
	
					// Supprimez les fichiers temporaires après avoir ajouté au ZIP
					unlink($outputFilePath);
					unlink($outputFilePath1);
					unlink($outputFilePath2);
					unlink($outputFilePath3);
					unlink($outputFilePath4);
				
	
					return response()->download($zipFilePath)->deleteFileAfterSend(true);
				} else {
					return $this->responseError(["error" => "Les fichiers Word n'ont pas été créés correctement"], 500);
				}
			} else {
				return $this->responseError(["auth" => [$authorisation->message()]], 403);
			}
		} else {
			return $this->responseError(["id" => "Le contrat n'existe pas"], 404);
		}
	}
	/**
	 * Télécharge le billet à ordre d'un contrat
	 *
	 * @urlParam    id                                                      int     required    L'ID du contrat.                                                        Example: 1
	 *
	 * @response 200
	 */
	public function promissory_note(Request $request, int $id)
	{
		$contract = Contract::find($id);
		if ($contract) {
			if (($authorisation = Gate::inspect('download', $contract))->allowed()) {
				$templateProcessor = new TemplateProcessor("../document_templates/Contracts/$contract->type/billet_a_ordre_$contract->type.docx");
				$data = $contract->toArray();
				$data = array_merge($data, collect($contract->verbal_trial)->mapWithKeys(function ($value, $key) {
					return ['verbal_trial.' . $key => $value];
				})->all());

				$data = array_merge($data, collect($contract->verbal_trial->type_of_credit)->mapWithKeys(function ($value, $key) {
					return ['verbal_trial.type_of_credit.' . $key => $value];
				})->all());
				$data = array_merge($data, collect($contract->verbal_trial->type_of_credit->type_of_applicant)->mapWithKeys(function ($value, $key) {
					return ['verbal_trial.type_of_credit.type_of_applicant.' . $key => $value];
				})->all());
				if ($contract->type == "company") {
					$data = array_merge($data, collect($contract->company)->mapWithKeys(function ($value, $key) {
						return ['company.' . $key => $value];
					})->all());
				} elseif ($contract->type == "individual_business") {
					$data = array_merge($data, collect($contract->individual_business)->mapWithKeys(function ($value, $key) {
						return ['individual_business.' . $key => $value];
					})->all());
				}

				$data["ht_rate"] = "17";
				$data["current_date"] = Carbon::now()->format("d/m/Y");
				$data["verbal_trial.amount.fr"] = SpellNumber::value((float) $data["verbal_trial.amount"])->locale('fr')->toLetters();
				$data["total_amount_of_interest.fr"] = SpellNumber::value((float) $data["total_amount_of_interest"])->locale('fr')->toLetters();
				$data["verbal_trial.duration.fr"] = SpellNumber::value((float) $data["verbal_trial.duration"])->locale('fr')->toLetters();
				$data["verbal_trial.due_amount.fr"] = SpellNumber::value((float) $data["verbal_trial.due_amount"])->locale('fr')->toLetters();
				$data["total_to_pay"] = (float) $data["total_amount_of_interest"] + (float) $data["verbal_trial.amount"] +(((float) $data["total_amount_of_interest"] / 100)*18);
				$data["total_to_pay.fr"] = SpellNumber::value((float) $data["total_to_pay"])->locale('fr')->toLetters();
				$data["verbal_trial.duration.fr"] = SpellNumber::value((float) $data["verbal_trial.duration"])->locale('fr')->toLetters();
				$data["signatory"] = (((float) $data["verbal_trial.amount"]) <= 10000000) ? "Madame Ameh Délali MESSANGAN épouse AMEDEMEGNAH, Responsable juridique" : "Mr. Koffi Djramedo GAMADO, Head Crédit";
				$data["verbal_trial.periodicity.fr"] = ["mensual" => "Mensuel", "quarterly" => "Trimestrielle", "semi-annual" => "Semestrielle", "annual" => "Annuel", "in-fine" => "A la fin"][$data["verbal_trial.periodicity"]];
				$data["verbal_trial.periodicity.fr2"] = ["mensual" => "chaque mois", "quarterly" => "chaque trimestre", "semi-annual" => "chaque semestre", "annual" => "chaque année", "in-fine" => "A la fin."][$data["verbal_trial.periodicity"]];
				$data["verbal_trial.periodicity.fr3"] = ["mensual" => "mensualité", "quarterly" => "trimestre", "semi-annual" => "semestre", "annual" => "année", "in-fine" => "echéance."][$data["verbal_trial.periodicity"]];
				$data["line_review_bonus"] = (((float) $data["verbal_trial.duration"]) < 18) ? "" : "Prime de révision de ligne      : « 1% du capital restant dû après 12 mois »";
				$data["representative_type_of_identity_document"] = [
					"cni" => "Carte d'identité nationale",
					"passport" => "Passeport",
					"residence_certificate" => "Certificat de résidence",
					"driving_licence" => "Permis de conduire"
				][$data["representative_type_of_identity_document"]];

				$data["verbal_trial.amount"] = number_format(((float) $data["verbal_trial.amount"]), 0, ',', ' ');
				$data["total_amount_of_interest"] = number_format(((float) $data["total_amount_of_interest"]), 0, ',', ' ');
				$data["verbal_trial.due_amount"] = number_format(((float) $data["verbal_trial.due_amount"]), 0, ',', ' ');
				$data["total_to_pay"] = number_format(((float) $data["total_to_pay"]), 0, ',', ' ');

				unset($data["observations"]);
				unset($data["guarantors"]);
				$templateProcessor->setValues($data);

				// Enregistrez les modifications dans un nouveau fichier
				$outputFilePath = public_path("Billet-a-ordre-" . $contract->verbal_trial->committee_id . ".docx");
				$this->saveAndProtect($templateProcessor, $outputFilePath);

				// return response()->download($outputFilePath)->deleteFileAfterSend(true);
				return Response::file($outputFilePath, ["Content-Type" => "application/vnd.openxmlformats-officedocument.wordprocessingml.document"]);
			} else {
				return $this->responseError(["auth" => [$authorisation->message()]], 403);
			}
		} else {
			return $this->responseError(["id" => "Le contrat n'existe pas"], 404);
		}
	}

	/**
	 * Créer un nouveau contrat
	 *
	 * @bodyParam   verbal_trial_id                                         int                 L'ID du PV.                                                             Example: 1
	 * @bodyParam   representative_birth_date                               string              La date de naissance du demandeur.                                      Example: 1988-05-01
	 * @bodyParam   representative_birth_place                              string              Le lieu de naissance du demandeur.                                      Example: Lomé
	 * @bodyParam   representative_nationality                              string              La nationalité du demandeur.                                            Example: Togolaise
	 * @bodyParam   representative_home_address                             string              L'addresse du domicile du demandeur.                                    Example: Zip 85
	 * @bodyParam   representative_type_of_identity_document                string              Le type de la pièce d'identité du demandeur.                           Example: cni
	 * @bodyParam   representative_number_of_identity_document              string              Le numéro de la pièce d'identité du demandeur.                         Example: CND-4D8-84S-52S
	 * @bodyParam   representative_date_of_issue_of_identity_document       string              La date de délivrance de la pièce d'identité du demandeur.              Example: 2020-01-01
	 * @bodyParam   representative_phone_number                             string              Le numéro de téléphone du demandeur.                                    Example: +228 90 90 90 90
	 * @bodyParam   risk_premium_percentage                                 int                 La prime de risque (en pourcentage) du crédit du demandeur.             Example: 2
	 * @bodyParam   total_amount_of_interest                                int                 Le montant total des intérêts du crédit du demandeur.                   Example: 152369
	 * @bodyParam   number_of_due_dates                                     int                 Le nombre d'échéance du crédit.                                         Example: 3
	 * @bodyParam   type                                                    string              Le type du contrat.                                                     Example: company
	 * @bodyParam   has_pledges                                             string              La présence de gage.                                                    Example: 0
	 *
	 * @response 200
	 */
	public function store(Request $request)
	{
		// dd($request);
		if (($authorisation = Gate::inspect('create', Contract::class))->allowed()) {
			$requestData = $request->all();
			$validator = Validator::make($requestData, [
				'verbal_trial_id' => "required|exists:verbals_trials,id|unique:contracts",
				'representative_birth_date' => 'required|date',
				'representative_birth_place' => 'required|min:2',
				'representative_nationality' => 'required|min:2',
				'representative_home_address' => 'required|min:2',
				'representative_type_of_identity_document' => 'required|in:cni,passport,residence_certificate,driving_licence,carte_sej,recep',
				'representative_number_of_identity_document' => 'required|min:2',
				'representative_office_delivery' => 'required|min:2',
				'representative_date_of_issue_of_identity_document' => 'required|date',
				'representative_phone_number' => 'required|min:2',
				// 'risk_premium_percentage' => 'required|numeric',
				'total_amount_of_interest' => 'required|numeric',
				'number_of_due_dates' => 'required|numeric',
				'type' => 'required|in:particular,company,individual_business,ong,professions_libérales',
				'number_of_pret'=>'required|min:2',
				'has_pledges' => 'required|boolean',
			]);
			if ($validator->fails()) {
				return $this->responseError($validator->errors(), 400);
			}

			DB::beginTransaction();
			try {
				$relationList = ["verbal_trial", "verbal_trial.type_of_credit.type_of_applicant", "verbal_trial.guarantees"];
				$requestData["creator_id"] = $request->user()->id;
				$contract = Contract::create($requestData);
				if ($requestData["type"] == "company") {
					$validator = Validator::make($requestData, [
						'company_denomination' => "required|min:2",
						'company_legal_status' => "required|min:2",
						'company_head_office_address' => "required|min:2",
						'company_rccm_number' => "required|min:2",
						'company_phone_number' => "required|min:2",
					]);

					if ($validator->fails()) {
						return $this->responseError($validator->errors(), 400);
					} else {
						Company::create([
							"contract_id" => $contract->id,
							"denomination" => $requestData["company_denomination"],
							"legal_status" => $requestData["company_legal_status"],
							"head_office_address" => $requestData["company_head_office_address"],
							"rccm_number" => $requestData["company_rccm_number"],
							"phone_number" => $requestData["company_phone_number"],
							"nif"=>$requestData["company_nif"],
							"bp"=>$requestData["company_bp"],
							"commune"=>$requestData["company_commune"]
						]);
					}

					$relationList[] = "company";
				} elseif ($requestData["type"] == "individual_business") {
					$validator = Validator::make($requestData, [
						'individual_business_denomination' => "required|min:2",
						'individual_business_head_office_address' => "required|min:2",
						'individual_business_rccm_number' => "required|min:2",
						'individual_business_nif_number' => "required|min:2",
						'individual_business_phone_number' => "required|min:2",
						// 'individual_business_number_phone'=> 'required|min:2',
						'individual_business_commune'=>'required|min:2',
						'individual_business_bp'=>'required|min:2',
						// 'individual_business_date_naiss'=>'required|min:2',
						// 'individual_business_date_delivrance'=>'required|min:2',
						// 'individual_business_lieux_naiss'=>'required|min:2',
						// 'individual_business_office_delivery'=>'required|min:2',
						// 'individual_business_home_address'=>'required|min:2',
						// 'individual_business_num_piece'=>'required|min:2',
						// 'individual_business_first_name'=>'required|min:2',
						// 'individual_business_last_name'=>'required|min:2',
						// 'individual_business_nationalite'=>'required|min:2',
						
						// 'individual_business_civility'=>'required|in:Mr,Mme,Mlle',
						// 'individual_business_type_of_identity_document' => 'required|in:cni,passport,residence_certificate,driving_licence,carte_sej',
					]);

					if ($validator->fails()) {
						return $this->responseError($validator->errors(), 400);
					} else {
						IndividualBusiness::create([
							"contract_id" => $contract->id,
							"denomination" => $requestData["individual_business_denomination"],
							"head_office_address" => $requestData["individual_business_head_office_address"],
							"rccm_number" => $requestData["individual_business_rccm_number"],
							"nif" => $requestData["individual_business_nif_number"],
							"phone_number" => $requestData["individual_business_phone_number"],
							"commune"=> $requestData["individual_business_commune"],
							"bp"=> $requestData["individual_business_bp"],
							// "phone_number" => $requestData["individual_business_phone_number"],
							// "date_naiss"=>$requestData["individual_business_date_naiss"],
							// "date_delivrance"=>$requestData["individual_business_date_delivrance"],
							// "lieux_naiss"=>$requestData["individual_business_lieux_naiss"],
							// "office_delivery"=> $requestData["individual_business_office_delivery"],
							// "home_address"=> $requestData["individual_business_home_address"],
							// "num_piece"=>$requestData["individual_business_num_piece"],
							// "first_name"=>$requestData["individual_business_first_name"],
							// "last_name"=>$requestData["individual_business_last_name"],
							// "nationalite"=>$requestData["individual_business_nationalite"],
							// "number_phone"=>$requestData["individual_business_number_phone"],
							// "civility"=>$requestData["individual_business_civility"],
							// "type_of_identity_document" => $requestData["individual_business_type_of_identity_document"]
						]);
					}

					$relationList[] = "individual_business";
				}
				// dd($request);
				if (isset($requestData["has_pledges"])) {
					if ($requestData["has_pledges"]) {
						$validator = Validator::make($requestData, [
							"pledges" => "required|array|min:1",
							"pledges.*.type" => "required|in:vehicle,stock",
							"pledges.*.montant_estime" => "required|min:2",
							"pledges.*.marque" => "required|min:2",
							"pledges.*.genre" => "required|min:2",
							"pledges.*.immatriculation" => "required|min:2",
							"pledges.*.numero_serie" => "required|min:2",
							"pledges.*.model" => "required|min:2",
							"pledges.*.date_mise_en_circulation" => 'required|date',
							"pledges.*.date_carte_crise" => 'required|date',
						]);
						if ($validator->fails()) {
							return $this->responseError($validator->errors(), 400);
						}
						foreach ($requestData["pledges"] as $pledge) {
							Pledge::create([
								"contract_id" => $contract->id,
								"type" => $pledge["type"],
								"montant_estime" => $pledge["montant_estime"],
								"marque" => $pledge["marque"],
								"genre" => $pledge["genre"],
								"model" => $pledge["model"],
								"immatriculation" => $pledge["immatriculation"],
								"numero_serie" => $pledge["numero_serie"],
								"date_mise_en_circulation" => $pledge["date_mise_en_circulation"],
								"date_carte_crise" => $pledge["date_carte_crise"],
								"civility" => $pledge["civility"],
								"nom" => $pledge["nom"],
								"prenom" => $pledge["prenom"],
								"date_naiss" => $pledge["date_naiss"],
								"lieux_naiss" => $pledge["lieux_naiss"],
								"identity_document" => $pledge["identity_document"],
								"num_identity_document" => $pledge["num_identity_document"],
								"date_delivery_document" => $pledge["date_delivery_document"],
								"office_delivery" => $pledge["office_delivery"],
								"adresse" => $pledge["adresse"],
								"nationalite" => $pledge["nationalite"],
								"phone" => $pledge["phone"],
							]);
						}
						$relationList[] = "pledges";
					}
				}
				$contract->load($relationList);
			} catch (Exception $e) {
				DB::rollback();
				throw $e;
			}
			DB::commit(); // Valider les opérations
			$receiver = $contract->verbal_trial->caf;
			$link = env("APP_URL") . "/contract";
			// 	SendEmail::dispatch(
			// 		$receiver->email,
			// 		"Notification de mise en place d'un pv",
			// 		"
			//     <h1 style='color: #333333;text-align: center; font-size: 24px; margin-bottom: 20px;'>Cher(e) $receiver->full_name,</U></h1>

			//     <p style='color: #666666; font-size: 16px; line-height: 1.5;'>Nous vous prions de vous connecter à l'application cofina credit digital et de prendre en charge immédiatement le contrat en attente de signature par le client: <a href='$link'>Consulter l</a></p>

			//     <p style='color: #666666; font-size: 16px; line-height: 1.5;'>Si vous avez des questions ou des préoccupations, n'hésitez pas à nous contacter. Nous sommes là pour vous aider !</p>

			//     <hr style='border: none; border-top: 1px solid #dddddd; margin: 20px 0;'>

			//     <p style='color: #999999; font-size: 12px;'>Cet e-mail est généré automatiquement. Veuillez ne pas y répondre.</p>
			// "
			// 	);
			return $this->responseOk([
				"contract" => $contract
			], status: 201);
		} else {
			return $this->responseError(["auth" => [$authorisation->message()]], 403);
		}
	}

	/**
	 * Mettre à jour un contrat
	 *
	 * @urlParam    id                                                      int     required    L'ID du contrat.                                                        Example: 1
	 *
	 * @bodyParam   verbal_trial_id                                         int                 L'ID du PV.                                                             Example: 1
	 * @bodyParam   representative_birth_date                               string              La date de naissance du demandeur.                                      Example: 1988-05-01
	 * @bodyParam   representative_birth_place                              string              Le lieu de naissance du demandeur.                                      Example: Lomé
	 * @bodyParam   representative_nationality                              string              La nationalité du demandeur.                                            Example: Togolaise
	 * @bodyParam   representative_home_address                             string              L'addresse du domicile du demandeur.                                    Example: Zip 85
	 * @bodyParam   representative_type_of_identity_document                string              Le type de la pièce d'identité du demandeur.                           Example: cni
	 * @bodyParam   representative_number_of_identity_document              string              Le numéro de la pièce d'identité du demandeur.                         Example: CND-4D8-84S-52S
	 * @bodyParam   representative_date_of_issue_of_identity_document       string              La date de délivrance de la pièce d'identité du demandeur.              Example: 2020-01-01
	 * @bodyParam   representative_phone_number                             string              Le numéro de téléphone du demandeur.                                    Example: +228 90 90 90 90
	 * @bodyParam   risk_premium_percentage                                 int                 La prime de risque (en pourcentage) du crédit du demandeur.             Example: 2
	 * @bodyParam   total_amount_of_interest                                int                 Le montant total des intérêts du crédit du demandeur.                   Example: 152369
	 * @bodyParam   number_of_due_dates                                     int                 Le nombre d'échéance du crédit.                                         Example: 3
	 * @bodyParam   type                                                    string              Le type du contrat.                                                     Example: company
	 *
	 * @response 200
	 *
	 */
	public function update(Request $request, int $id)
	{
		$contract = Contract::find($id);
		if ($contract) {
			if (($authorisation = Gate::inspect('update', $contract))->allowed()) {
				$requestData = $request->all();
				$validator = Validator::make($requestData, [
					'verbal_trial_id' => "required|exists:verbals_trials,id|unique:contracts,verbal_trial_id," . $id,
					'representative_birth_date' => 'required|date',
					'representative_birth_place' => 'required|min:2',
					'representative_nationality' => 'required|min:2',
					'representative_home_address' => 'required|min:2',
					'representative_type_of_identity_document' => 'required|in:cni,passport,residence_certificate,driving_licence',
					'representative_number_of_identity_document' => 'required|min:2',
					'representative_date_of_issue_of_identity_document' => 'required|date',
					'representative_phone_number' => 'required|min:2',
					'risk_premium_percentage' => 'required|numeric',
					'total_amount_of_interest' => 'required|numeric',
					'number_of_due_dates' => 'required|numeric',
					'type' => 'required|in:particular,company,individual_business',
					'has_pledges' => 'required|boolean',
				]);
				if ($validator->fails()) {
					return $this->responseError($validator->errors(), 400);
				}

				DB::beginTransaction();
				try {
					$relationList = ["verbal_trial", "verbal_trial.type_of_credit.type_of_applicant", "verbal_trial.guarantees"];
					$requestData["creator_id"] = $request->user()->id;
					$requestData["status"] = "waiting";
					$contract->update($requestData);
					$contract->company?->delete();
					$contract->individual_business?->delete();
					if ($requestData["type"] == "company") {
						$validator = Validator::make($requestData, [
							'company_denomination' => "required|min:2",
							'company_legal_status' => "required|min:2",
							'company_head_office_address' => "required|min:2",
							'company_rccm_number' => "required|min:2",
							'company_phone_number' => "required|min:2",
						]);

						if ($validator->fails()) {
							return $this->responseError($validator->errors(), 400);
						} else {
							Company::create([
								"contract_id" => $contract->id,
								"denomination" => $requestData["company_denomination"],
								"legal_status" => $requestData["company_legal_status"],
								"head_office_address" => $requestData["company_head_office_address"],
								"rccm_number" => $requestData["company_rccm_number"],
								"phone_number" => $requestData["company_phone_number"],
							]);
						}

						$relationList[] = "company";
					} elseif ($requestData["type"] == "individual_business") {
						$validator = Validator::make($requestData, [
							'individual_business_denomination' => "required|min:2",
							'individual_business_corporate_purpose' => "required|min:2",
							'individual_business_head_office_address' => "required|min:2",
							'individual_business_rccm_number' => "required|min:2",
							'individual_business_phone_number' => "required|min:2",
						]);

						if ($validator->fails()) {
							return $this->responseError($validator->errors(), 400);
						} else {
							IndividualBusiness::create([
								"contract_id" => $contract->id,
								"denomination" => $requestData["individual_business_denomination"],
								"corporate_purpose" => $requestData["individual_business_corporate_purpose"],
								"head_office_address" => $requestData["individual_business_head_office_address"],
								"rccm_number" => $requestData["individual_business_rccm_number"],
								"phone_number" => $requestData["individual_business_phone_number"],
							]);
						}

						$relationList[] = "individual_business";
					}

					if (isset($requestData["has_pledges"])) {
						if ($requestData["has_pledges"]) {
							$validator = Validator::make($requestData, [
								"pledges" => "required|array|min:1",
								"pledges.*.type" => "required|in:vehicle,stock",
								"pledges.*.comment" => "required|min:2"
							]);
							if ($validator->fails()) {
								return $this->responseError($validator->errors(), 400);
							}
							foreach ($requestData["pledges"] as $pledge) {
								Pledge::create([
									"contract_id" => $contract->id,
									"type" => $pledge["type"],
									"comment" => $pledge["comment"],
								]);
							}
							$relationList[] = "pledges";
						}
					}
					$contract->load($relationList);
				} catch (Exception $e) {
					DB::rollback();
					throw $e;
				}
				DB::commit(); // Valider les opérations
				return $this->responseOk([
					"contract" => $contract
				]);
			} else {
				return $this->responseError(["auth" => [$authorisation->message()]], 403);
			}
		} else {
			return $this->responseError(["id" => "Le contrat n'existe pas"], 404);
		}
	}

	/**
	 * Sauvegarde le contrat ou procès verbal signé
	 *
	 * @urlParam    id                                                      int     required    L'ID du contrat.                                                        Example: 1
	 *
	 * @response 204
	 */

	public function upload(Request $request, int $id)
	{
		$contract = Contract::find($id);
		if ($contract) {
			if (($authorisation = Gate::inspect('upload', $contract))->allowed()) {
				DB::beginTransaction();
				if ($request->has('signed_contract')) {
					$document_category = "contract";
					$base64Document = $request->input('signed_contract');
				} else if ($request->has('signed_promissory_note')) {
					$document_category = "promissory_note";
					$base64Document = $request->input('signed_promissory_note');
				} else {
					DB::rollBack();
					return $this->responseError(["error" => "Vous devez uploader un contrat signé ou un billet à ordre signé"], 400);
				}

				// Vérifier si le document est un PDF
				if (strpos($base64Document, 'data:application/pdf;base64,') === 0) {
					// Le document est un PDF
					$extension = 'pdf';
				} elseif (strpos($base64Document, 'data:image/') === 0) {
					// Le document est une image
					// Extraire l'extension de l'image
					$start = strpos($base64Document, '/') + 1;
					$end = strpos($base64Document, ';');
					$extension = substr($base64Document, $start, $end - $start);
				} else {
					// Type de document non pris en charge
					DB::rollBack();
					return $this->responseError(["error" => "Le document doit être un pdf ou une image"], 400);
				}

				$documentData = base64_decode(preg_replace('/^data:\w+\/\w+;base64,/', '', $base64Document));
				$path = 'upload/Contracts/signed_' . $document_category . 's/' . $contract->verbal_trial->committee_id . '-signed.' . $extension;
				Storage::disk("public")->put($path, $documentData);
				$contract->update(["signed_{$document_category}_path" => "/storage/" . $path, "status" => "waiting"]);

				DB::commit();
				return $this->responseOk(["contract" => $contract]);
			} else {
				return $this->responseError(["auth" => [$authorisation->message()]], 403);
			}
		} else {
			return $this->responseError(["id" => "Le contrat n'existe pas"], 404);
		}
	}

	/**
	 * Mettre à jour le statut d'un contrat
	 *
	 * @urlParam    id      required                    int             L'ID du contrat.                                        Example: 1
	 *
	 * @bodyParam   status                              string          Le nouveau statut                                       Example: rejected
	 * @bodyParam   comment                             string          Commentaire du changement                               Example: Trop bas
	 *
	 * @response 200
	 *
	 */
	public function change_status(Request $request, $id)
	{
		$contract = Contract::find($id);
		if ($contract) {
			if (($authorisation = Gate::inspect("change_status", $contract))->allowed()) {
				$requestData = $request->all();
				$validator = Validator::make($requestData, [
					'status' => 'required|in:rejected,validated',
					'comment' => "min:0",
				]);
				if ($validator->fails()) {
					return $this->responseError($validator->errors(), 400);
				} else {
					$contract->update([
						"status" => $requestData["status"],
						"status_observation" => $requestData["comment"],
					]);
				}
			} else {
				return $this->responseError(["auth" => [$authorisation->message()]], 403);
			}
		} else {
			return $this->responseError(["id" => ["Le CAT n'existe pas"]], 404);
		}
	}

	/**
	 * Supprime un contrat
	 *
	 * @urlParam    id                                                      int     required    L'ID du contrat.                                                        Example: 1
	 *
	 * @response 204
	 */
	public function destroy(int $id)
	{
		$contract = Contract::find($id);
		if ($contract) {
			if (($authorisation = Gate::inspect('delete', $contract))->allowed()) {
				if ($contract->delete()) {
					return $this->responseOk(messages: ["contract" => "Le contrat a été supprimé"], status: 204);
				} else {
					return $this->responseError(["server" => "Erreur du serveur"], 500);
				}
			} else {
				return $this->responseError(["auth" => [$authorisation->message()]], 403);
			}
		} else {
			return $this->responseError(["id" => ["Le contrat n'existe pas"]], 404);
		}
	}

}

