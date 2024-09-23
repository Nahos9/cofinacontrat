<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Traits\CustomResponseTrait;
use App\Models\TypeOfGuarantee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;


/**
 * @group Type de Garantie
 *
 * EndPoints pour gérer les types de garanties
 */
class TypeOfGuaranteeController extends Controller
{

    

    /**
     * Affiche les types de garantie
     *
     * @queryParam  name                        string  Filtrer par nom.                                No-example
     *
     * @queryParam  paginate                    int     Utiliser la pagination.                         Example: 0
     *
     * @response 200
     */
    public function index(Request $request)
    {
        if (($authorisation = Gate::inspect('viewAny', TypeOfGuarantee::class))->allowed()) {
            $typeOfGuaranteeList = TypeOfGuarantee::query();
            if ($search = $request->search) {
                $typeOfGuaranteeList
                    ->where('name', 'LIKE', "%$search%")
                ;
            }

            foreach (["name"] as $filter) {
                if (isset($request[$filter]) && $request[$filter]) {
                    $typeOfGuaranteeList->where($filter, $request[$filter]);
                }
            }

            // foreach (["with_type_of_applicant" => "type_of_applicant"] as $key => $value) {
            //     if (isset($request[$key]) && $request[$key]) {
            //         $typeOfGuaranteeList->with($value);
            //     }
            // }

            if (isset($request["paginate"]) && ($request->paginate == false)) {
                $typeOfGuaranteeList = $typeOfGuaranteeList->orderByDesc('created_at')->get();
                $data = ["data" => $typeOfGuaranteeList, "total" => count($typeOfGuaranteeList)];
            } else {
                $data = $typeOfGuaranteeList->orderByDesc('created_at')->paginate(8)->toArray();
            }

            return $this->responseOkPaginate($data);
        } else {
            return $this->responseError(["auth" => [$authorisation->message()]], 403);
        }
    }

    /**
     * Affiche un type de garantie
     *
     * @urlParam    id                          int required    L'ID du type de garantie.  Example: 1
     *
     * @response 200
     */
    public function show(Request $request, int $id)
    {
        $typeOfGuarantee = TypeOfGuarantee::find($id);
        if ($typeOfGuarantee) {
            if (($authorisation = Gate::inspect('view', $typeOfGuarantee))->allowed()) {
                // $suplementList = [];
                // foreach (["with_type_of_applicant" => "type_of_applicant"] as $key => $value) {
                //     if (isset($request[$key]) && $request[$key]) {
                //         $suplementList[] = $value;
                //     }
                // }
                // $typeOfGuarantee->load($suplementList);
                return $this->responseOk(["typeOfGuarantee" => $typeOfGuarantee]);
            } else {
                return $this->responseError(["auth" => [$authorisation->message()]], 403);
            }
        } else {
            return $this->responseError(["id" => "Le type de garantie n'existe pas"], 404);
        }
    }

    /**
     * Créer un nouveau type de garantie
     *
     * @bodyParam   name                    string  required    Le nom du type de garantie.                                   Example: BFR
     *
     * @response 200
     */
    public function store(Request $request)
    {
        if (($authorisation = Gate::inspect('create', TypeOfGuarantee::class))->allowed()) {
            $requestData = $request->all();

            $validator = Validator::make($requestData, [
                'name' => 'required|unique:types_of_guarantee',
            ]);
            if ($validator->fails()) {
                return $this->responseError($validator->errors(), 400);
            } else {
                $typeOfGuarantee = TypeOfGuarantee::create($requestData);
                return $this->responseOk([
                    "typeOfGuarantee" => $typeOfGuarantee
                ], status: 201);
            }
        } else {
            return $this->responseError(["auth" => [$authorisation->message()]], 403);
        }
    }

    /**
     * Mettre à jour un type de garantie
     *
     * @urlParam    id                              required    L'ID du type de garantie.                                     Example: 1
     *
     * @bodyParam   name                    string  required    Le nom du type de garantie.                                   Example: BFR
     *
     * @response 200
     *
     */
    public function update(Request $request, int $id)
    {
        $typeOfGuarantee = TypeOfGuarantee::find($id);
        if ($typeOfGuarantee) {
            if (($authorisation = Gate::inspect('update', $typeOfGuarantee))->allowed()) {
                $requestData = $request->all();
                $validator = Validator::make($requestData, [
                    'name' => 'required|unique:types_of_guarantee,name,' . $id,
                ]);
                if ($validator->fails()) {
                    return $this->responseError($validator->errors(), 400);
                } else {
                    $typeOfGuarantee->update($requestData);
                    return $this->responseOk([
                        "typeOfGuarantee" => $typeOfGuarantee
                    ]);
                }
            } else {
                return $this->responseError(["auth" => [$authorisation->message()]], 403);
            }
        } else {
            return $this->responseError(["id" => "Le type de garantie n'existe pas"], 404);
        }
    }

    /**
     * Supprime un type de garantie
     *
     * @urlParam id int required L'ID du type de garantie
     *
     * @response 204
     */
    public function destroy(int $id)
    {
        $typeOfGuarantee = TypeOfGuarantee::find($id);
        if ($typeOfGuarantee) {
            if (($authorisation = Gate::inspect('delete', $typeOfGuarantee))->allowed()) {
                if ($typeOfGuarantee->delete()) {
                    return $this->responseOk(messages: ["typeOfGuarantee" => "Type de garantie supprimé"], status: 204);
                } else {
                    return $this->responseError(["server" => "Erreur du serveur"], 500);
                }
            } else {
                return $this->responseError(["auth" => [$authorisation->message()]], 403);
            }
        } else {
            return $this->responseError(["id" => ["Le type de garantie n'existe pas"]], 404);
        }

    }
}

