<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Traits\CustomResponseTrait;
use App\Models\CAT;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpWord\TemplateProcessor;


/**
 * @group CAT
 *
 * EndPoints pour gérer les CAT
 */
class CATController extends Controller
{

	

	/**
	 * Affiche les CAT
	 *
	 * @queryParam  contract_id                                             int                 Filtrer par ID du contrat.                                              No-example
	 * @queryParam  credit_number                                           string              Filtrer par numéro du crédit.                                           No-example
	 * @queryParam  sector                                                  string              Filtrer par secteur.                                                    No-example
	 * @queryParam  first_deadline                                          string              Filtrer par date de première échéance.                                  No-example
	 * @queryParam  last_deadline                                           string              Filtrer par date de dernière échéance.                                  No-example
	 * @queryParam  source_of_reimbursement                                 string              Filtrer par source du remboursement.                                    No-example
	 * @queryParam  instructions_from_the_risk_and_credit_department        string              Filtrer par instructions du département risque et crédit.               No-example
	 * @queryParam  outstanding_number_ready_to_settle                      string              Filtrer par numéro encours prêt à solder.                               No-example
	 * @queryParam  other_expenses                                          string              Filtrer par autres frais.                                               No-example
	 * @queryParam  teg                                                     int                 Filtrer par TEG.                                                        No-example
	 * @queryParam  has_notification                                        int                 Filtrer par présence de notification.                                   No-example
	 * @queryParam  has_contract                                            int                 Filtrer par présence de contrat.                                        No-example
	 * @queryParam  is_simple                                               int                 Filtrer par type de notifcation.                                        No-example
	 *
	 * @queryParam  with_contract                                           int                 Afficher le contrat.                                                    Example: 0
	 * @queryParam  with_notification                                       int                 Afficher le notification.                                               Example: 0
	 * @queryParam  with_verbal_trial                                       int                 Afficher le PV.                                                         Example: 0
	 * @queryParam  with_type_of_credit                                     int                 Afficher le type de crédit.                                             Example: 0
	 * @queryParam  with_type_of_applicant                                  int                 Afficher le type de demandeur.                                          Example: 0
	 * @queryParam  with_guarantees                                         int                 Afficher les garanties.                                                 Example: 0
	 * @queryParam  with_creator                                            int                 Afficher le créateur du contrat.                                        Example: 0
	 * @queryParam  paginate                                                int                 Utiliser la pagination.                                                 Example: 0
	 *
	 * @response 200
	 */
	public function index(Request $request)
	{
		if (($authorisation = Gate::inspect('viewAny', CAT::class))->allowed()) {
			$catList = CAT::query();
			if ($search = $request->search) {
				$catList
					->where(function ($query) use ($search) {
						$query
							->where('contract_id', 'LIKE', "%$search%")
							->orWhere('credit_number', 'LIKE', "%$search%")
							->orWhere('sector', 'LIKE', "%$search%")
							->orWhere('first_deadline', 'LIKE', "%$search%")
							->orWhere('last_deadline', 'LIKE', "%$search%")
							->orWhere('source_of_reimbursement', 'LIKE', "%$search%")
							->orWhere('instructions_from_the_risk_and_credit_department', 'LIKE', "%$search%")
							->orWhere('outstanding_number_ready_to_settle', 'LIKE', "%$search%")
							->orWhere('other_expenses', 'LIKE', "%$search%")
							->orWhere('teg', 'LIKE', "%$search%")
							->orWhereHas('contract', function ($query) use ($search) {
								$query->whereHas('verbal_trial', function ($query) use ($search) {
									$query->where('committee_id', 'LIKE', "%$search%")
										->orWhere(DB::raw("CONCAT(applicant_first_name, ' ', applicant_last_name)"), 'LIKE', "%$search%");
								});
							})
							->orWhereHas('notification', function ($query) use ($search) {
								$query->whereHas('verbal_trial', function ($query) use ($search) {
									$query->where('committee_id', 'LIKE', "%$search%")
										->orWhere(DB::raw("CONCAT(applicant_first_name, ' ', applicant_last_name)"), 'LIKE', "%$search%");
								});
							})
						;
					});
			}

			$parentRelation = "contract";

			if (isset($request["has_notification"])) {
				$has_notification = (int) $request["has_notification"];
				if ($has_notification == 1) {
					$parentRelation = "notification";
					$catList->whereHas('notification');
				} else if ($has_notification == 0) {
					$catList->whereDoesntHave('notification');
				}
			}

			if (isset($request["is_simple"])) {
				$is_simple = (int) $request["is_simple"];
				$catList->whereHas('notification', function ($query) use ($is_simple) {
					$query->where('is_simple', $is_simple);
				});
			}

			if (isset($request["has_contract"])) {
				$has_contract = (int) $request["has_contract"];
				if ($has_contract == 1) {
					$parentRelation = "contract";
					$catList->whereHas('contract');
				} else if ($has_contract == 0) {
					$catList->whereDoesntHave('contract');
				}
			}

			foreach (["contract_id", "credit_number", "sector", "first_deadline", "last_deadline", "source_of_reimbursement", "instructions_from_the_risk_and_credit_department", "outstanding_number_ready_to_settle", "other_expenses", "teg"] as $filter) {
				if (isset($request[$filter]) && $request[$filter]) {
					$catList->where($filter, $request[$filter]);
				}
			}
			foreach (["with_contract" => "contract", "with_notification" => "notification", "with_verbal_trial" => "$parentRelation.verbal_trial", "with_type_of_credit" => "$parentRelation.verbal_trial.type_of_credit", "with_type_of_applicant" => "$parentRelation.verbal_trial.type_of_credit.type_of_applicant", "with_guarantees" => "$parentRelation.verbal_trial.guarantees", "with_creator" => "$parentRelation.creator"] as $key => $value) {
				if (isset($request[$key]) && $request[$key]) {
					$catList->with($value);
				}
			}

			if (isset($request["paginate"]) && ($request->paginate == false)) {
				$catList = $catList->orderByDesc('created_at')->get();
				$data = ["data" => $catList, "total" => count($catList)];
			} else {
				$data = $catList->orderByDesc('created_at')->paginate(8)->toArray();
			}

			return $this->responseOkPaginate($data);
		} else {
			return $this->responseError(["auth" => [$authorisation->message()]], 403);
		}
	}

	/**
	 * Affiche un CAT
	 *
	 * @urlParam    id                                                      int     required    L'ID du CAT.                                                            Example: 1
	 *
	 * @queryParam  with_contract                                           int                 Afficher le contrat.                                                    Example: 0
	 * @queryParam  with_verbal_trial                                       int                 Afficher le PV.                                                         Example: 0
	 * @queryParam  with_type_of_credit                                     int                 Afficher le type de crédit.                                             Example: 0
	 * @queryParam  with_type_of_applicant                                  int                 Afficher le type de demandeur.                                          Example: 0
	 * @queryParam  with_guarantees                                         int                 Afficher les garanties.                                                 Example: 0
	 * @queryParam  with_creator                                            int                 Afficher le créateur du contrat.                                        Example: 0
	 * @queryParam  paginate                                                int                 Utiliser la pagination.                                                 Example: 0
	 *
	 * @response 200
	 */
	public function show(Request $request, int $id)
	{
		$cat = CAT::find($id);
		if ($cat) {
			if (($authorisation = Gate::inspect('view', $cat))->allowed()) {
				$suplementList = [];
				$parentRelation = ($cat->contract_id) ? "contract" : "notification";
				foreach (["with_contract" => "contract", "with_verbal_trial" => "$parentRelation.verbal_trial", "with_type_of_credit" => "$parentRelation.verbal_trial.type_of_credit", "with_type_of_applicant" => "$parentRelation.verbal_trial.type_of_credit.type_of_applicant", "with_guarantees" => "$parentRelation.verbal_trial.guarantees", "with_creator" => "$parentRelation.creator"] as $key => $value) {
					if (isset($request[$key]) && $request[$key]) {
						$suplementList[] = $value;
					}
				}
				$cat->load($suplementList);
				return $this->responseOk(["c_a_t" => $cat]);
			} else {
				return $this->responseError(["auth" => [$authorisation->message()]], 403);
			}
		} else {
			return $this->responseError(["id" => "Le CAT n'existe pas"], 404);
		}
	}

	/**
	 * Télécharge la version word d'un CAT
	 *
	 * @urlParam    id                                                      int     required    L'ID du CAT.                                                            Example: 1
	 *
	 * @response 200
	 */
	public function download(Request $request, int $id)
	{
		$cat = CAT::find($id);
		if ($cat) {
			// if (($authorisation = Gate::inspect('view', $cat))->allowed()) {
			$parentRelation = ($cat->notification) ? "notification" : "contract";
			// $templateProcessor = new TemplateProcessor("../document_templates/CATs/CAT-$parentRelation.docx");
			$templateProcessor = new TemplateProcessor("../document_templates/CATs/cat-$parentRelation.docx");
			$data = $cat->toArray();
			$data = array_merge($data, collect($cat->$parentRelation)->mapWithKeys(function ($value, $key) use ($parentRelation) {
				return ["$parentRelation." . $key => $value];
			})->all());
			$data = array_merge($data, collect($cat->$parentRelation->verbal_trial)->mapWithKeys(function ($value, $key) use ($parentRelation) {
				return ["$parentRelation.verbal_trial." . $key => $value];
			})->all());
			$data = array_merge($data, collect($cat->$parentRelation->verbal_trial->type_of_credit)->mapWithKeys(function ($value, $key) use ($parentRelation) {
				return ["$parentRelation.verbal_trial.type_of_credit." . $key => $value];
			})->all());
			$data = array_merge($data, collect($cat->$parentRelation->verbal_trial->caf)->mapWithKeys(function ($value, $key) use ($parentRelation) {
				return ["$parentRelation.verbal_trial.caf." . $key => $value];
			})->all());
			$data = array_merge($data, collect($cat->$parentRelation->verbal_trial->type_of_credit->type_of_applicant)->mapWithKeys(function ($value, $key) use ($parentRelation) {
				return ["$parentRelation.verbal_trial.type_of_credit.type_of_applicant." . $key => $value];
			})->all());
			$source_of_reimbursementTranslate = [
				"revenue_from_the_activity" => "Recettes de l’activité",
				"final_payer_settlement" => "Règlement du payeur final",
				"resale_of_goods" => "Reventes des marchandise",
				"salary"=>"salaire"
			];

			$data["ht_rate"] = "17";
			$data["source_of_reimbursement.fr"] = $source_of_reimbursementTranslate[$data["source_of_reimbursement"]];
			$data["date_of_approval"] = Carbon::parse($cat->$parentRelation->verbal_trial->created_at)->format("d/m/Y");
			$data["first_deadline"] = Carbon::parse($cat->first_deadline)->format("d/m/Y");
			$data["last_deadline"] = Carbon::parse($cat->last_deadline)->format("d/m/Y");
			$data["current_date"] = Carbon::now()->format('d/m/Y');

			$data["$parentRelation.verbal_trial.tax_fee_interest_rate.value"] = number_format((float) ($data["$parentRelation.verbal_trial.tax_fee_interest_rate"] * $data["$parentRelation.verbal_trial.amount"] / 100), 0, ',', ' ');
			$data["$parentRelation.verbal_trial.administrative_fees_percentage.value"] = number_format((float) ($data["$parentRelation.verbal_trial.administrative_fees_percentage"] * $data["$parentRelation.verbal_trial.amount"] / 100), 0, ',', ' ');
			$data["$parentRelation.risk_premium_percentage.value"] = number_format((float) ($data["$parentRelation.risk_premium_percentage"] * $data["$parentRelation.verbal_trial.amount"] / 100), 0, ',', ' ');
			$data["guarantee_amount_total"] = number_format($cat->$parentRelation->verbal_trial->guarantees->sum("value"), 0, ',', ' ');
			$data["security_deposit"] = number_format($data["$parentRelation.verbal_trial.amount"] * 0.2, 0, ',', ' ');
			$data["teg"] = number_format($data["teg"], 0, ',', ' ');
			$data["$parentRelation.verbal_trial.amount"] = number_format($data["$parentRelation.verbal_trial.amount"], 0, ',', ' ');
			if ($data["$parentRelation.verbal_trial.duration"] < 6) {
				$data["credit_type"] = "COURT TERME";
			} elseif ($data["$parentRelation.verbal_trial.duration"] < 12) {
				$data["credit_type"] = "MOYEN TERME";
			} else {
				$data["credit_type"] = "LONG TERME";
			}
			$guaranteeList = [];
			foreach ($cat->$parentRelation->verbal_trial->guarantees as $guarantee) {
				$tmp = $guarantee->toArray();
				// $tmp["value"] = number_format((float) $tmp["value"], 0, ',', ' ');
				$guaranteeList[] = array_merge($tmp, collect($guarantee->type_of_guarantee)->mapWithKeys(function ($value, $key) {
					return ['type_of_guarantee.' . $key => $value];
				})->all());
			}
			dd($guaranteeList);
			$templateProcessor->cloneBlock('guaranteeList', 0, true, false, $guaranteeList);
			unset($data["$parentRelation.observations"]);
			unset($data["$parentRelation.guarantors"]);
			unset($data["$parentRelation.verbal_trial.caf.ability_rules"]);
			unset($data["$parentRelation"]);
			unset($data["status"]);
			// dd($data);
			$templateProcessor->setValues($data);
			// return $data;

			// Enregistrez les modifications dans un nouveau fichier
			$outputFilePath = public_path("CAT-" . $cat->$parentRelation->verbal_trial->committee_id . ".docx");
			$templateProcessor->saveAs($outputFilePath);

			return response()->download($outputFilePath)->deleteFileAfterSend(true);
			// return $this->responseOk(["c_a_t" => $cat]);
			// } else {
			//     return $this->responseError(["auth" => [$authorisation->message()]], 403);
			// }
		} else {
			return $this->responseError(["id" => "Le CAT n'existe pas"], 404);
		}
	}

	/**
	 * Créer un nouveau CAT
	 *
	 * @bodyParam   contract_id                                             int                 ID du contrat.                                              Example: 1
	 * @bodyParam   credit_number                                           string              Numéro du crédit.                                           Example: 1534820
	 * @bodyParam   sector                                                  string              Secteur.                                                    Example: Transport
	 * @bodyParam   first_deadline                                          string              Date de première échéance.                                  Example: 2025-01-01
	 * @bodyParam   last_deadline                                           string              Date de dernière échéance.                                  Example: 2025-12-31
	 * @bodyParam   source_of_reimbursement                                 string              Source du remboursement.                                    Example: revenue_from_the_activity
	 * @bodyParam   instructions_from_the_risk_and_credit_department        string              Instructions du département risque et crédit.               Example: Restriction sur le compte sous reserve du retrait du tableau d’amortissement et du contrat
	 * @bodyParam   outstanding_number_ready_to_settle                      string              Numéro encours prêt à solder.                               Example: 1534820
	 * @bodyParam   other_expenses                                          string              Autres frais.                                               Example: 25000
	 * @bodyParam   teg                                                     int                 TEG.                                                        Example: 58563242
	 *
	 * @response 200
	 */
	public function store(Request $request)
	{
		if (($authorisation = Gate::inspect('create', CAT::class))->allowed()) {
			$requestData = $request->all();
			$validator = Validator::make($requestData, [
				'contract_id' => "required|exists:contracts,id|unique:c_a_t_s",
			]);
			if ($validator->fails()) {
				$validator = Validator::make($requestData, [
					'notification_id' => "required|exists:notifications,id|unique:c_a_t_s",
				]);
				if ($validator->fails()) {
					return $this->responseError(["id" => ["Le contrat ou la notification est manquante"]], 403);
				}
			}

			$validator = Validator::make($requestData, [
				'credit_number' => 'required|unique:c_a_t_s',
				'sector' => 'required|min:2',
				'code_sector' =>'required|min:2',
				'first_deadline' => 'required|date',
				'last_deadline' => 'required|date',
				'source_of_reimbursement' => 'required|in:revenue_from_the_activity,final_payer_settlement,resale_of_goods,salary',
				'instructions_from_the_risk_and_credit_department' => 'required|min:2',
				'outstanding_number_ready_to_settle' => 'required|min:2',
				'other_expenses' => 'required|numeric',
				'teg' => 'required|numeric',
			]);

			if ($validator->fails()) {
				return $this->responseError($validator->errors(), 400);
			}

			DB::beginTransaction();
			try {
				$requestData["validation_status"] = "waiting";
				$requestData["unblock_status"] = "waiting";
				$cat = CAT::create($requestData);
				$cat->load(["contract.verbal_trial.type_of_credit.type_of_applicant", "contract.verbal_trial.guarantees"]);
			} catch (\Exception $e) {
				DB::rollback();
				throw $e;
			}
			DB::commit(); // Valider les opérations
			return $this->responseOk(["c_a_t" => $cat], status: 201);
		} else {
			return $this->responseError(["auth" => [$authorisation->message()]], 403);
		}
	}

	/**
	 * Mettre à jour un CAT
	 *
	 * @urlParam    id                                                      int     required    L'ID du CAT.                                                Example: 1
	 *
	 * @bodyParam   contract_id                                             int                 ID du contrat.                                              Example: 1
	 * @bodyParam   credit_number                                           string              Numéro du crédit.                                           Example: 1534820
	 * @bodyParam   sector                                                  string              Secteur.                                                    Example: Transport
	 * @bodyParam   first_deadline                                          string              Date de première échéance.                                  Example: 2025-01-01
	 * @bodyParam   last_deadline                                           string              Date de dernière échéance.                                  Example: 2025-12-31
	 * @bodyParam   source_of_reimbursement                                 string              Source du remboursement.                                    Example: revenue_from_the_activity
	 * @bodyParam   instructions_from_the_risk_and_credit_department        string              Instructions du département risque et crédit.               Example: Restriction sur le compte sous reserve du retrait du tableau d’amortissement et du contrat
	 * @bodyParam   outstanding_number_ready_to_settle                      string              Numéro encours prêt à solder.                               Example: 1534820
	 * @bodyParam   other_expenses                                          string              Autres frais.                                               Example: 25000
	 * @bodyParam   teg                                                     int                 TEG.                                                        Example: 58563242
	 *
	 * @response 200
	 *
	 */
	public function update(Request $request, int $id)
	{
		$cat = CAT::find($id);
		if ($cat) {
			if (($authorisation = Gate::inspect('update', $cat))->allowed()) {
				$requestData = $request->all();
				$validator = Validator::make($requestData, [
					'contract_id' => "required|exists:contracts,id|unique:c_a_t_s,contract_id," . $id,
				]);
				if ($validator->fails()) {
					$validator = Validator::make($requestData, [
						'notification_id' => "required|exists:notifications,id|unique:c_a_t_s,notification_id," . $id,
					]);
					if ($validator->fails()) {
						return $this->responseError(["id" => ["Le contrat ou la notification est manquante"]], 403);
					}
				}
				$validator = Validator::make($requestData, [
					'credit_number' => 'required|unique:c_a_t_s,credit_number,' . $id,
					'sector' => 'required|min:2',
					'first_deadline' => 'required|date',
					'last_deadline' => 'required|date',
					'source_of_reimbursement' => 'required|in:revenue_from_the_activity,final_payer_settlement,resale_of_goods',
					'instructions_from_the_risk_and_credit_department' => 'required|min:2',
					'outstanding_number_ready_to_settle' => 'required|min:2',
					'other_expenses' => 'required|numeric',
					'teg' => 'required|numeric',
				]);
				if ($validator->fails()) {
					return $this->responseError($validator->errors(), 400);
				} else {
					$cat->update($requestData);
					$cat->load(["contract.verbal_trial.type_of_credit.type_of_applicant", "contract.verbal_trial.guarantees"]);
					return $this->responseOk(["c_a_t" => $cat]);
				}
			} else {
				return $this->responseError(["auth" => [$authorisation->message()]], 403);
			}
		} else {
			return $this->responseError(["id" => "Le CAT n'existe pas"], 404);
		}
	}

	/**
	 * Valider un CAT
	 *
	 * @urlParam    id                                                      int     required    L'ID du CAT.                                                Example: 1
	 *
	 * @bodyParam   comment                                                 string              Le commentaire de la validation.                            Example: Bon
	 *
	 * @response 200
	 *
	 */
	public function validate_cat(Request $request, int $id)
	{
		$cat = CAT::find($id);
		if ($cat) {
			if (($authorisation = Gate::inspect('validate', $cat))->allowed()) {
				$cat->update([
					"validation_status" => "validated",
					"validation_user_id" => $request->user()->id,
				]);
				return $cat;
			} else {
				return $this->responseError(["auth" => [$authorisation->message()]], 403);
			}
		} else {
			return $this->responseError(["id" => ["Le CAT n'existe pas"]], 404);
		}
	}

	/**
	 * Débloquer un CAT
	 *
	 * @urlParam    id                                                      int     required    L'ID du CAT.                                                Example: 1
	 *
	 * @bodyParam   comment                                                 string              Le commentaire de la déblocage.                             Example: Bon
	 *
	 * @response 200
	 *
	 */
	public function unblock(Request $request, int $id)
	{
		$cat = CAT::find($id);
		if ($cat) {
			if (($authorisation = Gate::inspect('unblock', $cat))->allowed()) {
				$cat->update([
					"unblock_status" => "validated",
					"unblock_user_id" => $request->user()->id,
				]);
				return $cat;
			} else {
				return $this->responseError(["auth" => [$authorisation->message()]], 403);
			}
		} else {
			return $this->responseError(["id" => ["Le CAT n'existe pas"]], 404);
		}
	}

	/**
	 * Rejeter un CAT
	 *
	 * @urlParam    id                                                      int     required    L'ID du CAT.                                                Example: 1
	 *
	 * @bodyParam   comment                                                 string              Le commentaire de refus de validation.                      Example: Manque ...
	 *
	 * @response 200
	 *
	 */
	public function reject_validation(Request $request, int $id)
	{
		$cat = CAT::find($id);
		if ($cat) {
			if (($authorisation = Gate::inspect('reject_validation', $cat))->allowed()) {
				$requestData = $request->all();
				$validator = Validator::make($requestData, [
					'comment' => "min:1",
				]);
				if ($validator->fails()) {
					return $this->responseError($validator->errors(), 400);
				} else {
					$cat->update([
						"validation_status" => "rejected",
						"validation_comment" => $requestData["comment"],
						"validation_user_id" => $request->user()->id,
					]);
					return $cat;
				}
			} else {
				return $this->responseError(["auth" => [$authorisation->message()]], 403);
			}
		} else {
			return $this->responseError(["id" => ["Le CAT n'existe pas"]], 404);
		}
	}

	/**
	 * Rejeter le déblocage d'un CAT
	 *
	 * @urlParam    id                                                      int     required    L'ID du CAT.                                                Example: 1
	 *
	 * @bodyParam   comment                                                 string              Le commentaire de refus de déblocage.                       Example: Manque ...
	 *
	 * @response 200
	 *
	 */
	public function reject_unblock(Request $request, int $id)
	{
		$cat = CAT::find($id);
		if ($cat) {
			if (($authorisation = Gate::inspect('reject_unblock', $cat))->allowed()) {
				$requestData = $request->all();
				$validator = Validator::make($requestData, [
					'comment' => "min:1",
				]);
				if ($validator->fails()) {
					return $this->responseError($validator->errors(), 400);
				} else {
					$cat->update([
						"unblock_status" => "rejected",
						"unblock_comment" => $requestData["comment"],
						"unblock_user_id" => $request->user()->id,
					]);
					return $cat;
				}
			} else {
				return $this->responseError(["auth" => [$authorisation->message()]], 403);
			}
		} else {
			return $this->responseError(["id" => ["Le CAT n'existe pas"]], 404);
		}
	}

	/**
	 * Supprime un CAT
	 *
	 * @urlParam    id                                                      int     required    L'ID du CAT.                                                        Example: 1
	 *
	 * @response 204
	 */
	public function destroy(int $id)
	{
		$cat = CAT::find($id);
		if ($cat) {
			if (($authorisation = Gate::inspect('delete', $cat))->allowed()) {
				if ($cat->delete()) {
					return $this->responseOk(messages: ["c_a_t" => "Le CAT a été supprimé"], status: 204);
				} else {
					return $this->responseError(["server" => "Erreur du serveur"], 500);
				}
			} else {
				return $this->responseError(["auth" => [$authorisation->message()]], 403);
			}
		} else {
			return $this->responseError(["id" => ["Le CAT n'existe pas"]], 404);
		}
	}
}
