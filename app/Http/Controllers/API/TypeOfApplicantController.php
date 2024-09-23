<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Traits\CustomResponseTrait;
use App\Models\TypeOfApplicant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

/**
 * @group Type de demandeur
 *
 * EndPoints pour gérer les types de demandeur de crédit
 */
class TypeOfApplicantController extends Controller
{

	

	/**
	 * Affiche les types de demandeur de crédit
	 *
	 * @queryParam  name                        string  Filtrer par nom.                No-example
	 *
	 * @queryParam  with_types_of_credit        int     Afficher les types de crédit.   Example: 0
	 * @queryParam  with_verbals_trials         int     Afficher les PV.                Example: 0
	 * @queryParam  paginate                    int     Utiliser la pagination.         Example: 0
	 *
	 * @response 200
	 */
	public function index(Request $request)
	{
		if (($authorisation = Gate::inspect('viewAny', TypeOfApplicant::class))->allowed()) {
			$typeOfApplicantList = TypeOfApplicant::query();
			if ($search = $request->search) {
				$typeOfApplicantList
					->where('name', 'LIKE', "%$search%")
				;
			}

			foreach (["name"] as $filter) {
				if (isset($request[$filter]) && $request[$filter]) {
					$typeOfApplicantList->where($filter, $request[$filter]);
				}
			}

			foreach (["with_types_of_credit" => "types_of_credit", "with_verbals_trials" => "types_of_credit.verbals_trials"] as $key => $value) {
				if (isset($request[$key]) && $request[$key]) {
					$typeOfApplicantList->with($value);
				}
			}

			if (isset($request["paginate"]) && ($request->paginate == false)) {
				$typeOfApplicantList = $typeOfApplicantList->orderByDesc('created_at')->get();
				$data = ["data" => $typeOfApplicantList, "total" => count($typeOfApplicantList)];
			} else {
				$data = $typeOfApplicantList->orderByDesc('created_at')->paginate(8)->toArray();
			}

			return $this->responseOkPaginate($data);
		} else {
			return $this->responseError(["auth" => [$authorisation->message()]], 403);
		}
	}

	/**
	 * Affiche un type de demandeur de crédit
	 *
	 * @urlParam    id                          int required    L'ID du type de demandeur de crédit.    Example: 1
	 *
	 * @queryParam  with_types_of_credit        int             Afficher les types de crédit.           Example: 0
	 * @queryParam  with_verbals_trials         int             Afficher les PV.                        Example: 0
	 *
	 * @response 200
	 */
	public function show(Request $request, int $id)
	{
		$typeOfApplicant = TypeOfApplicant::find($id);
		if ($typeOfApplicant) {
			if (($authorisation = Gate::inspect('view', $typeOfApplicant))->allowed()) {
				$suplementList = [];
				foreach (["with_types_of_applicant" => "types_of_credit", "with_verbals_trials" => "types_of_credit.verbals_trials"] as $key => $value) {
					if (isset($request[$key]) && $request[$key]) {
						$suplementList[] = $value;
					}
				}
				$typeOfApplicant->load($suplementList);
				return $this->responseOk(["typeOfApplicant" => $typeOfApplicant]);
			} else {
				return $this->responseError(["auth" => [$authorisation->message()]], 403);
			}
		} else {
			return $this->responseError(["id" => "Le type de demandeur de crédit n'existe pas"], 404);
		}
	}

	/**
	 * Créer un nouveau type de demandeur de crédit
	 *
	 * @bodyParam   name    string  required    Le nom du type de demandeur de crédit.                Example: mawena
	 *
	 * @response 200
	 */
	public function store(Request $request)
	{
		if (($authorisation = Gate::inspect('create', TypeOfApplicant::class))->allowed()) {
			$requestData = $request->all();
			$validator = Validator::make($requestData, [
				'name' => 'required|unique:types_of_applicant',
			]);
			if ($validator->fails()) {
				return $this->responseError($validator->errors(), 400);
			} else {
				$typeOfApplicant = TypeOfApplicant::create($requestData);
				$typeOfApplicant->load(["types_of_credit", "verbals_trials"]);
				return $this->responseOk([
					"typeOfApplicant" => $typeOfApplicant
				], status: 201);
			}
		} else {
			return $this->responseError(["auth" => [$authorisation->message()]], 403);
		}
	}

	/**
	 * Mettre à jour un type de demandeur de crédit
	 *
	 * @urlParam id required L'ID du type de demandeur de crédit. Example: 1
	 *
	 * @bodyParam   name    string  required    Le nom du type de demandeur de crédit.                Example: mawena
	 *
	 * @response 200
	 *
	 */
	public function update(Request $request, int $id)
	{
		$typeOfApplicant = TypeOfApplicant::find($id);
		if ($typeOfApplicant) {
			if (($authorisation = Gate::inspect('update', $typeOfApplicant))->allowed()) {
				$requestData = $request->all();
				$validator = Validator::make($requestData, [
					'name' => 'required|unique:types_of_applicant,name,' . $id,
				]);
				if ($validator->fails()) {
					return $this->responseError($validator->errors(), 400);
				} else {
					$typeOfApplicant->update($requestData);
					$typeOfApplicant->load(["types_of_credit", "verbals_trials"]);
					return $this->responseOk([
						"typeOfApplicant" => $typeOfApplicant
					]);
				}
			} else {
				return $this->responseError(["auth" => [$authorisation->message()]], 403);
			}
		} else {
			return $this->responseError(["id" => "Le type de demandeur de crédit n'existe pas"], 404);
		}
	}

	/**
	 * Supprime un type de demandeur de crédit
	 *
	 * @urlParam id int required L'ID du type de demandeur de crédit
	 *
	 * @response 204
	 */
	public function destroy(int $id)
	{
		$typeOfApplicant = TypeOfApplicant::find($id);
		if ($typeOfApplicant) {
			if (($authorisation = Gate::inspect('delete', $typeOfApplicant))->allowed()) {
				if ($typeOfApplicant->delete()) {
					return $this->responseOk(messages: ["typeOfApplicant" => "Type de demandeur de crédit supprimé"], status: 204);
				} else {
					return $this->responseError(["server" => "Erreur du serveur"], 500);
				}
			} else {
				return $this->responseError(["auth" => [$authorisation->message()]], 403);
			}
		} else {
			return $this->responseError(["id" => ["Le type de demandeur de crédit n'existe pas"]], 404);
		}
	}
}

