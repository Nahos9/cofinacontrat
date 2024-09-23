<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Traits\CustomResponseTrait;
use App\Models\TypeOfCredit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;


/**
 * @group Type de crédit
 *
 * EndPoints pour gérer les types de crédit
 */
class TypeOfCreditController extends Controller
{

    

    /**
     * Affiche les types de crédit
     *
     * @queryParam  name                        string  Filtrer par nom.                           No-example
     * @queryParam  type_of_applicant_id        int     Filtrer par type de demandeur de crédit.        No-example
     *
     * @queryParam  with_type_of_applicant      int     Afficher le demandeur.                          Example: 0
     * @queryParam  with_verbals_trials         int     Afficher les PV.                                Example: 0
     * @queryParam  paginate                    int     Utiliser la pagination.                         Example: 0
     *
     * @response 200
     */
    public function index(Request $request)
    {
        if (($authorisation = Gate::inspect('viewAny', TypeOfCredit::class))->allowed()) {
            $typeOfCreditList = TypeOfCredit::query();
            if ($search = $request->search) {
                $typeOfCreditList
                    ->where('name', 'LIKE', "%$search%")
                    ->orWhere('type_of_applicant_id', 'LIKE', "%$search%")
                ;
            }

            foreach (["name", "type_of_applicant_id"] as $filter) {
                if (isset($request[$filter]) && $request[$filter]) {
                    $typeOfCreditList->where($filter, $request[$filter]);
                }
            }

            foreach (["with_type_of_applicant" => "type_of_applicant", "with_verbals_trials" => "verbals_trials"] as $key => $value) {
                if (isset($request[$key]) && $request[$key]) {
                    $typeOfCreditList->with($value);
                }
            }

            if (isset($request["paginate"]) && ($request->paginate == false)) {
                $typeOfCreditList = $typeOfCreditList->orderByDesc('created_at')->get();
                $data = ["data" => $typeOfCreditList, "total" => count($typeOfCreditList)];
            } else {
                $data = $typeOfCreditList->orderByDesc('created_at')->paginate(8)->toArray();
            }

            return $this->responseOkPaginate($data);
        } else {
            return $this->responseError(["auth" => [$authorisation->message()]], 403);
        }
    }

    /**
     * Affiche un type de crédit
     *
     * @urlParam    id                          int required    L'ID du type de crédit.     Example: 1
     *
     * @queryParam  with_type_of_applicant      int             Afficher le demandeur.      Example: 0
     * @queryParam  with_verbals_trials         int             Afficher les PV.            Example: 0
     *
     * @response 200
     */
    public function show(Request $request, int $id)
    {
        $typeOfCredit = TypeOfCredit::find($id);
        if ($typeOfCredit) {
            if (($authorisation = Gate::inspect('view', $typeOfCredit))->allowed()) {
                $suplementList = [];
                foreach (["with_type_of_applicant" => "type_of_applicant", "with_verbals_trials" => "verbals_trials"] as $key => $value) {
                    if (isset($request[$key]) && $request[$key]) {
                        $suplementList[] = $value;
                    }
                }
                $typeOfCredit->load($suplementList);
                return $this->responseOk(["typeOfCredit" => $typeOfCredit]);
            } else {
                return $this->responseError(["auth" => [$authorisation->message()]], 403);
            }
        } else {
            return $this->responseError(["id" => "Le type de crédit n'existe pas"], 404);
        }
    }

    /**
     * Créer un nouveau type de crédit
     *
     * @bodyParam   name                    string  required    Le nom du type de crédit.                                   Example: BFR
     * @bodyParam   min_month               int     required    Le nombre de mois minimum du type de crédit                 Example: 0
     * @bodyParam   max_month               int     required    Le nombre de mois maximum du type de crédit                 Example: 6
     * @bodyParam   type_of_applicant_id    int     required    L'identifiant du type de demandeur de crédit.               Example: 1
     *
     * @response 200
     */
    public function store(Request $request)
    {
        if (($authorisation = Gate::inspect('create', TypeOfCredit::class))->allowed()) {
            $requestData = $request->all();

            $validator = Validator::make($requestData, [
                'name' => 'required|min:2',
                'min_month' => 'required|numeric|min:0',
                'max_month' => 'required|numeric|min:1',
            ]);
            if ($validator->fails()) {
                return $this->responseError($validator->errors(), 400);
            } else {
                if ($requestData["min_month"] > $requestData["max_month"]) {
                    return $this->responseError(["min_month" => "Le mois minimum doit être inférieur au mois maximum"]);
                }
                if (TypeOfCredit::where('name', $requestData["name"])->where('min_month', $requestData["min_month"])->where('min_month', $requestData["min_month"])->exists()) {
                    return $this->responseError(["typeOfCredit" => "Le type de crédit existe déjà"]);
                }
                $typeOfCredit = TypeOfCredit::create($requestData);
                $typeOfCredit->load(["type_of_applicant", "verbals_trials"]);
                return $this->responseOk([
                    "typeOfCredit" => $typeOfCredit
                ], status: 201);
            }
        } else {
            return $this->responseError(["auth" => [$authorisation->message()]], 403);
        }
    }

    /**
     * Mettre à jour un type de crédit
     *
     * @urlParam    id                              required    L'ID du type de crédit.                                     Example: 1
     *
     * @bodyParam   name                    string  required    Le nom du type de crédit.                                   Example: BFR
     * @bodyParam   min_month               int     required    Le nombre de mois minimum du type de crédit                 Example: 0
     * @bodyParam   max_month               int     required    Le nombre de mois maximum du type de crédit                 Example: 6
     * @bodyParam   type_of_applicant_id    int     required    L'identifiant du type de demandeur de crédit.               Example: 1
     *
     * @response 200
     *
     */
    public function update(Request $request, int $id)
    {
        $typeOfCredit = TypeOfCredit::find($id);
        if ($typeOfCredit) {
            if (($authorisation = Gate::inspect('update', $typeOfCredit))->allowed()) {
                $requestData = $request->all();
                $validator = Validator::make($requestData, [
                    'name' => 'required|unique:types_of_credit,name,' . $id,
                    'type_of_applicant_id' => 'exists:types_of_applicant,id',
                    'min_month' => 'required|numeric|min:0',
                    'max_month' => 'required|numeric|min:0',
                ]);
                if ($validator->fails()) {
                    return $this->responseError($validator->errors(), 400);
                } else {
                    $typeOfCredit->update($requestData);
                    $typeOfCredit->load(["type_of_applicant", "verbals_trials"]);
                    return $this->responseOk([
                        "typeOfCredit" => $typeOfCredit
                    ]);
                }
            } else {
                return $this->responseError(["auth" => [$authorisation->message()]], 403);
            }
        } else {
            return $this->responseError(["id" => "Le type de crédit n'existe pas"], 404);
        }
    }

    /**
     * Supprime un type de crédit
     *
     * @urlParam id int required L'ID du type de crédit
     *
     * @response 204
     */
    public function destroy(int $id)
    {
        $typeOfCredit = TypeOfCredit::find($id);
        if ($typeOfCredit) {
            if (($authorisation = Gate::inspect('delete', $typeOfCredit))->allowed()) {
                if ($typeOfCredit->delete()) {
                    return $this->responseOk(messages: ["typeOfCredit" => "Type de crédit supprimé"], status: 204);
                } else {
                    return $this->responseError(["server" => "Erreur du serveur"], 500);
                }
            } else {
                return $this->responseError(["auth" => [$authorisation->message()]], 403);
            }
        } else {
            return $this->responseError(["id" => ["Le type de crédit n'existe pas"]], 404);
        }

    }
}
