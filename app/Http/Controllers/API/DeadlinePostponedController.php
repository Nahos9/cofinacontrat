<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\DeadlinePostponed;
use App\Models\DeadlinePostponedFile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


/**
 * @group Report d'échéance
 * 
 * Endpoints pour gérer les reports d'échéances
 */
class DeadlinePostponedController extends Controller
{


	/**
	 * Affiche les report d'échéance
	 *
	 * @queryParam	caf_id													int					Filtrer par ID du caf initiateur.										 No-example
	 * @queryParam	credit_number											string				Filtrer par numéro du crédit.											 No-example
	 * @queryParam	deadline_number											int					Filtrer par numéro d'échéance.											 No-example
	 * @queryParam	old_date												string				Filtrer par ancienne date.												 No-example
	 * @queryParam	new_date												string				Filtrer par nouvelle date.												 No-example
	 * @queryParam	request_path											string				Filtrer par lien du document de la demande.								 No-example
	 * @queryParam	memo_path												string				Filtrer par lien du mémo de la demande.									 No-example
	 * @queryParam	status													string				Filtrer par status du report.											 No-example
	 * @queryParam	comment													string				Filtrer par commentaire sur le report.									 No-example
	 * @queryParam	extension												int					Filtrer par rallonge.													 No-example
	 * @queryParam	beneficiary_label										string				Filtrer par applelation du bénéficiaire.								 No-example
	 * @queryParam	loan_amount												float				Filtrer par montant du crédit.											 No-example
	 * @queryParam	representative_civility									string				Filtrer par civilité du représentant.									 No-example
	 * @queryParam	representative_last_name								string				Filtrer par nom du représentant.										 No-example
	 * @queryParam	representative_first_name								string				Filtrer par prénom du représentant.										 No-example
	 *
	 * @queryParam	with_caf												int					Afficher le caf.														Example: 0
	 * @queryParam	paginate												int					Utiliser la pagination.													Example: 0
	 *
	 * @response 200
	 */
	public function index(Request $request)
	{
		if (($authorisation = Gate::inspect('viewAny', DeadlinePostponed::class))->allowed()) {
			$requestData = $request->all();
			$currentUser = $request->user();
			$deadlinePostponedList = DeadlinePostponed::query();

			($search = $request->search) ? $deadlinePostponedList = $this->querySearch($deadlinePostponedList, ["credit_number"], $search) : null;
			$deadlinePostponedList = $this->queryFilter($deadlinePostponedList, ["caf_id", "credit_number", "deadline_number", "new_date", "request_path", "memo_path", "comment", "extension", "beneficiary_label", "loan_amount", "old_date", "representative_civility", "representative_last_name", "representative_first_name"], $requestData);
			$deadlinePostponedList = $this->queryMultipeValvueFilter($deadlinePostponedList, ["status"], $requestData, ["wc" => "waiting_ca", "rc" => "rejected_by_ca", "wd" => "waiting_dex", "rd" => "rejected_by_dex", "wh" => "waiting_head", "rh" => "rejected_by_head", "wm" => "waiting_md", "rm" => "rejected_by_md", "wca" => "waiting_credit_admin", "rca" => "rejected_by_credit_admin", "wr" => "waiting_report", "r" => "reported"]);
			$deadlinePostponedList = $this->queryRelation($deadlinePostponedList, ["with_caf" => "caf"], $requestData);

			$currentUser->profile == "caf" ? $deadlinePostponedList->where('caf_id', $currentUser->id) : null;


			return $this->responseIndexOk($deadlinePostponedList, $requestData);
		} else {
			return $this->responseError(["auth" => [$authorisation->message()]], 403);
		}
	}

	/**
	 * Affiche un report d'échéance
	 * 
	 * @urlParam	id														int	required		L'ID du report.															Example: 1
	 * 
	 * @queryParam	with_caf												int					Afficher le caf.														Example: 0
	 * 
	 * @response 200
	 */
	public function show(Request $request, int $id)
	{
		$deadlinePostponed = DeadlinePostponed::find($id);
		$requestData = $request->all();
		if ($deadlinePostponed) {
			if (($authorisation = Gate::inspect('view', $deadlinePostponed))->allowed()) {
				$deadlinePostponed = $this->modelRelationLoad($deadlinePostponed, ["with_caf" => "caf"], $requestData);
				return $this->responseOk(["deadlinePostponed" => $deadlinePostponed]);
			} else {
				return $this->responseError(["auth" => [$authorisation->message()]], 403);
			}
		} else {
			return $this->responseError(["id" => "Le report d'échéance n'existe pas"], 404);
		}
	}

	/**
	 * Créer un nouveau report d'échéance
	 *
	 * @bodyParam	caf_id													int		required	L'ID du caf initiateur.													Example: 1
	 * @bodyParam	credit_number											string	required	Le numéro du crédit.													Example: d4s1d4sds
	 * @bodyParam	deadline_number											int		required	Le numéro d'échéance.													Example: 1
	 * @bodyParam	old_date												string	required	L'ancienne date.														Example: 2024-03-03
	 * @bodyParam	new_date												string	required	La nouvelle date d'échéance.											Example: 2024-04-04
	 * @bodyParam	request													string	required	Le document de la demande.												Example: test
	 * @bodyParam	memo													string	required	Le mémo de la demande.													Example: test
	 * @bodyParam	extension												int		required	La rallonge.															Example: 0
	 * @bodyParam	beneficiary_label										string	required	L'applelation du bénéficiaire.											Example: David GUETTA
	 * @bodyParam	loan_amount												float	required	Le montant du crédit.													Example: 15000000
	 * @bodyParam	representative_civility									string	required	La civilité du représentant.											Example: Mr
	 * @bodyParam	representative_last_name								string	required	Le nom du représentant.										 			Example: GUETTA
	 * @bodyParam	representative_first_name								string	required	Le prénom du représentant.										 		Example: David
	 *
	 *
	 * @response 200
	 */
	public function store(Request $request)
	{
		return $this->modelStore(
			"App\Models\DeadlinePostponed",
			$request->all(),
			[
				"credit_number" => "required",
				"deadline_number" => "required|numeric",
				"new_date" => "required|date",
				"request" => "required",
				"memo" => "required",
				"extension" => "required|numeric",
				"beneficiary_label" => "required",
				"loan_amount" => "required|numeric",
				"old_date" => "required",
				"representative_civility" => "required",
				"representative_last_name" => "required",
				"representative_first_name" => "required",
			],
			manualValidations: function ($requestData) {
				$errors = [];
				foreach (["request" => "document", "memo" => "memo"] as $document => $documentFr) {
					if (!$this->checkIsBase64Validated($requestData[$document]))
						$errors[$document] = ["Le $documentFr de la demande doit être une base64 pdf ou image"];
				}
				if ($errors) {
					return $this->responseError($errors, 400);
				}
			},
			beforeCreate: function ($requestData) use ($request) {
				$requestData["caf_id"] = $request->user()->id;
				$requestData["status"] = "waiting_ca";

				foreach (["request", "memo"] as $document) {
					if ($this->checkIsBase64Validated($requestData[$document], ["pdf"])) {
						$extension = 'pdf';
					} else {
						$start = strpos($requestData[$document], '/') + 1;
						$end = strpos($requestData[$document], ';');
						$extension = substr($requestData[$document], $start, $end - $start);
					}
					$documentData = base64_decode(preg_replace('/^data:\w+\/\w+;base64,/', '', $requestData[$document]));
					$requestData[$document . "_path"] = 'upload/deadlinePostponed/' . $document . 's/' . Str::random(15) . "." . $extension;
					Storage::disk("public")->put($requestData[$document . "_path"], $documentData);
				}

				return $requestData;
			}
		);
	}

	/**
	 * Met à jour un report d'échéance
	 * 
	 * @urlParam	id														int					L'ID du report d'échéance.												Example: 1
	 *
	 * @bodyParam	caf_id													int		required	L'ID du caf initiateur.													Example: 1
	 * @bodyParam	credit_number											string	required	Le numéro du crédit.													Example: d4s1d4sds
	 * @bodyParam	deadline_number											int		required	Le numéro d'échéance.													Example: 1
	 * @bodyParam	old_date												string	required	L'ancienne date.														Example: 2024-03-03
	 * @bodyParam	new_date												string	required	La nouvelle date d'échéance.											Example: 2024-04-04
	 * @bodyParam	request													string				Le document de la demande.												Example: test
	 * @bodyParam	memo													string				Le mémo de la demande.													Example: test
	 * @bodyParam	extension												int		required	La rallonge.															Example: 0
	 * @bodyParam	beneficiary_label										string	required	L'applelation du bénéficiaire.											Example: David GUETTA
	 * @bodyParam	loan_amount												float	required	Le montant du crédit.													Example: 15000000
	 * @bodyParam	representative_civility									string	required	La civilité du représentant.											Example: Mr
	 * @bodyParam	representative_last_name								string	required	Le nom du représentant.										 			Example: GUETTA
	 * @bodyParam	representative_first_name								string	required	Le prénom du représentant.										 		Example: David
	 *
	 *
	 * @response 200
	 */
	public function update(Request $request, int $id)
	{
		return $this->modelUpdate(
			modelId: $id,
			modelClass: "App\Models\DeadlinePostponed",
			requestData: $request->all(),
			validations: [
				"credit_number" => "required",
				"deadline_number" => "required|numeric",
				"old_date" => "required",
				"new_date" => "required|date",
				"extension" => "required|numeric",
				"beneficiary_label" => "required",
				"loan_amount" => "required|numeric",
				"representative_civility" => "required",
				"representative_last_name" => "required",
				"representative_first_name" => "required",
			],
			manualValidations: function ($requestData) {
				$errors = [];
				foreach (["request" => "document", "memo" => "memo"] as $document => $documentFr) {
					if (isset ($requestData[$document]) && !$this->checkIsBase64Validated($requestData[$document]))
						$errors[$document] = ["Le $documentFr de la demande doit être une base64 pdf ou image"];
				}
				if ($errors) {
					return $this->responseError($errors, 400);
				}
			},
			beforeUpdate: function ($requestData, $model) {
				$requestData["caf_id"] = $model->caf_id;
				$requestData["status"] = "waiting_ca";

				foreach (["request", "memo"] as $document) {
					if (isset ($requestData[$document])) {
						if ($this->checkIsBase64Validated($requestData[$document], ["pdf"])) {
							$extension = 'pdf';
						} else {
							$start = strpos($requestData[$document], '/') + 1;
							$end = strpos($requestData[$document], ';');
							$extension = substr($requestData[$document], $start, $end - $start);
						}
						$documentData = base64_decode(preg_replace('/^data:\w+\/\w+;base64,/', '', $requestData[$document]));
						$requestData[$document . "_path"] = 'upload/deadlinePostponed/' . $document . 's/' . Str::random(15) . "." . $extension;
						Storage::disk("public")->delete($model[$document . "_path"]);
						Storage::disk("public")->put($requestData[$document . "_path"], $documentData);
					}
				}

				return $requestData;
			}
		);
	}


	/**
	 * Supprime un report d'échéance
	 *
	 * @urlParam	id														int					L'ID du report d'échéance.												Example: 1
	 *
	 * @response 204
	 */
	public function destroy(int $id)
	{
		return $this->modelDelete(
			modelId: $id,
			modelClass: "App\Models\DeadlinePostponed",
			afterDelete: function ($model) {
				Storage::disk("public")->delete($model["request_path"]);
				Storage::disk("public")->delete($model["memo_path"]);
			}
		);
	}
}
