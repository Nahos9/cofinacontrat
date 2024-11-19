<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

/**
 * @group Utilisateur
 *
 * EndPoints pour gérer les utilisateurs
 */
class UserController extends Controller
{

	//  * @apiResourceCollection App\Http\Resources\UserResource
	//  * @apiResourceModel App\Models\User
	//  * @apiResourceAdditional status=200 messages="Utilisateurs récupérés avec succès"
	/**
	 * Affiche les utilisateurs
	 *
	 * @queryParam  name                        string  Filtrer par username.                           No-example
	 * @queryParam  full_name                   string  Filtrer par nom complet.                        No-example
	 * @queryParam  email                       string  Filtrer par email.                              No-example
	 * @queryParam  profile                     string  Filtrer par profil.                             No-example
	 * @queryParam  activated                   int     Filtrer par statut d'activation                 No-example
	 * @queryParam  password_change_required    int     Filtrer par statut de mot de passe à changer    No-example
	 *
	 * @queryParam  paginate                    int     Utiliser la pagination.                         Example: 0
	 *
	 * @response 200
	 */
	public function index(Request $request)
	{
		if (($authorisation = Gate::inspect('viewAny', User::class))->allowed()) {
			$userList = User::query();
			if ($search = $request->search) {
				$userList->where(function ($query) use ($search) {
					$query
						->where('name', 'LIKE', "%$search%")
						->orWhere('full_name', 'LIKE', "%$search%")
						->orWhere('email', 'LIKE', "%$search%")
						->orWhere('profile', 'LIKE', "%$search%");
				});
			}


			foreach (["name", "full_name", "email", "profile"] as $filter) {
				if (isset($request[$filter]) && $request[$filter]) {
					$userList->where($filter, $request[$filter]);
				}
			}
			// foreach (["with_agency" => "agency", "with_head" => "agency.head"] as $key => $value) {
			//     if (isset($request[$key]) && $request[$key]) {
			//         $userList->with($value);
			//     }
			// }

			$connectedUser = $request->user();

			if ($connectedUser->profile == "credit_analyst") {
				$userList->where(function ($query) {
					$query->where('profile', 'caf')->orWhere('profile', 'credit_admin');
				});
			}

			if (isset($request["paginate"]) && ($request->paginate == false)) {
				$userList = $userList->orderByDesc('created_at')->get();
				$data = ["data" => $userList, "total" => count($userList)];
			} else {
				$data = $userList->orderByDesc('created_at')->paginate(8)->toArray();
			}

			return $this->responseOkPaginate($data);
		} else {
			return $this->responseError(["auth" => [$authorisation->message()]], 403);
		}
	}

	//  * @apiResource App\Http\Resources\UserResource
	//  * @apiResourceModel App\Models\User
	//  * @apiResourceAdditional status=200 messages="Utilisateur récupéré avec succès"
	/**
	 * Affiche un utilisateur
	 *
	 * @urlParam    id              int required    L'ID de l'utilisateur.          Example: 1
	 *
	 * @response 200
	 */
	public function show(Request $request, int $id)
	{
		$user = User::find($id);
		if ($user) {
			if (($authorisation = Gate::inspect('view', $user))->allowed()) {
				// $suplementList = [];
				// foreach (["with_agency" => "agency", "with_head" => "agency.head"] as $key => $value) {
				//     if (isset($request[$key]) && $request[$key]) {
				//         $suplementList[] = $value;
				//     }
				// }
				// $user->load($suplementList);
				return $this->responseOk(["user" => $user]);
			} else {
				return $this->responseError(["auth" => [$authorisation->message()]], 403);
			}
		} else {
			return $this->responseError(["id" => "L'utilisateur n'existe pas"], 404);
		}
	}

	//  * @apiResource App\Http\Resources\UserResource
	//  * @apiResourceModel App\Models\User
	//  * @apiResourceAdditional status=200 messages=[]
	/**
	 * Créer un nouvel utilisateur
	 *
	 * @bodyParam   name                        string  required    Le username de l'utilsateur.                Example: mawena
	 * @bodyParam   full_name                   string  required    Le nom complet de l'utilisateur.            Example: Charles GAMLIGO
	 * @bodyParam   profile                     string  required    Le profil de l'utilisateur.                 Example: admin
	 * @bodyParam   activated                   int     required    Le statut d'activation de l'utilisateur     Example: 1
	 * @bodyParam   password                    string  required    Le mot de passe de l'utilisateur.           Example: password
	 * @bodyParam   password_change_required    int     required    Le statut d'activation de l'utilisateur     Example: 1
	 *
	 *
	 * @response 200
	 */
	public function store(Request $request)
	{
		if (($authorisation = Gate::inspect('create', User::class))->allowed()) {
			$requestData = $request->all();
			$validator = Validator::make($requestData, [
				'name' => 'required|unique:users',
				'full_name' => 'required|unique:users',
				"profile" => 'required|in:admin,credit_analyst,credit_admin,head_credit,operation,legal,dex,caf,ca,md',
				'email' => 'required|unique:users',
				"activated" => 'required|boolean',
				"password" => 'required|min:8',
				"password_change_required" => 'required|boolean',
			]);
			if ($validator->fails()) {
				return $this->responseError($validator->errors(), 400);
			} else {
				$requestData["password"] = Hash::make($request->password);
				$requestData["email_verified_at"] = Carbon::now();
				$user = User::create($requestData);
				// $user->load("agency.head");
				return $this->responseOk([
					"user" => $user
				], status: 201);
			}
		} else {
			return $this->responseError(["auth" => [$authorisation->message()]], 403);
		}
	}

	/**
	 * Mettre à jour un utilisateur
	 *
	 * @urlParam id required L'ID de l'utilisateur. Example: 1
	 *
	 * @bodyParam   name                        string  required    Le username de l'utilsateur.                Example: mawena
	 * @bodyParam   full_name                   string  required    Le nom complet de l'utilisateur.            Example: Charles GAMLIGO
	 * @bodyParam   profile                     string  required    Le profil de l'utilisateur.                 Example: admin
	 * @bodyParam   email                       string  required    L'email de l'utilisateur.                   Example: gamligocharles@gmail.com
	 * @bodyParam   activated                   int     required    Le statut d'activation de l'utilisateur     Example: 1
	 * @bodyParam   password                    string              Le mot de passe de l'utilisateur.           No-example
	 * @bodyParam   password_change_required    int     required    Le statut d'activation de l'utilisateur     Example: 1
	 *
	 * @response 200
	 *
	 */
	public function update(Request $request, int $id)
	{
		$user = User::find($id);
		if ($user) {
			if (($authorisation = Gate::inspect('update', $user))->allowed()) {
				$requestData = $request->all();
				$validator = Validator::make($requestData, [
					'name' => 'required',
					'full_name' => 'required|unique:users,full_name,' . $id,
					'email' => 'required|unique:users,email,' . $id,
					"profile" => 'required|in:admin,credit_analyst,credit_admin,head_credit,operation,legal,dex,caf,ca,md',
					"password" => 'min:8',
					"activated" => 'required|boolean',
					"password_change_required" => 'required|boolean',
				]);
				if ($validator->fails()) {
					return $this->responseError($validator->errors(), 400);
				} else {
					if (isset($requestData["password"])) {
						$requestData["password"] = Hash::make($request->password);
						$requestData["password_change_required"] = true;
					}
					$user->update($requestData);
					// $user->load("agency.head");
					return $this->responseOk([
						"user" => $user
					]);
				}
			} else {
				return $this->responseError(["auth" => [$authorisation->message()]], 403);
			}
		} else {
			return $this->responseError(["id" => "L'utilisateur n'existe pas"], 404);
		}
	}
	/**
	 * Mettre à jour le mot de passe de l'utilisateur connecté utilisateur
	 *
	 * @bodyParam   old_password                    string  required    L'ancien mot de passe de l'utilisateur.                     No-example
	 * @bodyParam   new_password                    string  required    Le nouveau mot de passe de l'utilisateur.                   No-example
	 * @bodyParam   new_password_confirmation       string  required    La confirmation du nouveau mot de passe de l'utilisateur.   No-example
	 *
	 * @response 200
	 *
	 */
	public function update_password(Request $request)
	{
		$user = $request->user();
		if ($user) {
			if (($authorisation = Gate::inspect('update_password', $user))->allowed()) {
				$validator = Validator::make($request->all(), [
					"old_password" => 'required|min:2',
					"new_password" => 'required|min:8',
					"new_password_confirmation" => 'required|min:8|same:new_password',
				], [
					"same" => "Ce mot de passe est différent"
				]);
				if ($validator->fails()) {
					return $this->responseError($validator->errors(), 400);
				} else {
					if (Hash::check($request->old_password, $user->password)) {
						$user->update(["password" => $request->new_password, "password_change_required" => false]);
						// $user->load("agency.head");
						$request->user()->tokens()->each(function ($token, $key) {
							$token->delete();
						});
						return $this->responseOk([
							"user" => $user
						]);
					} else {
						return $this->responseError(["old_password" => ["Ancien mot de passe incorect"]], 403);
					}
				}
			} else {
				return $this->responseError(["auth" => [$authorisation->message()]], 403);
			}
		} else {
			return $this->responseError(["id" => "L'utilisateur n'existe pas"], 404);
		}
	}

	public function update_signatory(Request $request, int $id)
	{
		$validatedExtentions = ["png", "jpg", "gif", "jpeg"];
		return $this->modelUpdate(
			modelId: $id,
			modelClass: "App\Models\User",
			requestData: $request->all(),
			validations: [
				"signatory" => "required|min:5"
			],
			manualValidations: function ($requestData, $model) use ($validatedExtentions) {
				if (!$this->checkIsBase64Validated($requestData["signatory"], $validatedExtentions)) {
					return ["errors" => $this->responseError(["signatory" => ["le fichier n'est pas une image valide"]], 400)];
				}
				if ($signatory_path = $this->saveImageFromBase64($requestData["signatory"], "/upload/signatory/$model->id/" . Str::random(10) . ".png", $validatedExtentions)) {
					return ["data" => ["signatory_path" => $signatory_path]];
				} else {
					return ["errors" => $this->responseError(["signatory" => ["Une erreur est survenu durant l'insertion de l'image"]])];
				}
			},
			beforeUpdate: function ($requestData, $model, $data) use ($validatedExtentions) {
				$requestData["signatory_path"] = $data["signatory_path"];
				return $requestData;
			},
			authName: "update_signatory"
		);
	}

	/**
	 * Supprime un utilisateur
	 *
	 * @urlParam id int required L'ID de l'utilisateur
	 *
	 * @response 204
	 */
	public function destroy(int $id)
	{
		$user = User::find($id);
		if ($user) {
			if (($authorisation = Gate::inspect('delete', $user))->allowed()) {
				if ($user->delete()) {
					return $this->responseOk(messages: ["user" => "Utilisateur supprimé"], status: 204);
				} else {
					return $this->responseError(["server" => "Erreur du serveur"], 500);
				}
			} else {
				return $this->responseError(["auth" => [$authorisation->message()]], 403);
			}
		} else {
			return $this->responseError(["id" => ["L'utilisateur n'existe pas"]], 404);
		}

	}

	public function clients(){
		// dd("hello");
		$clients = DB::connection('oracle')->select('SELECT * FROM cofina.client' );
		// $clients = DB::connection('oracle')->select('SELECT * FROM cofina.client WHERE ROWNUM <= 1');
		// dd($clients);
		return $this->responseOk([
			"user" => $clients
		]);
		
	}
	public function searchClients(Request $request)
{
    $search = $request->input('search', '');
	$clients = DB::connection('oracle')
    ->table(DB::raw('"COFINA"."CLIENT"'))
    ->where('matricule_client', '=', $search)
    ->get();
	// dd($clients);
    return $this->responseOk([
        "data" => $clients
    ]);
}
// public function comptes(Request $requet){
// 	$search = $requet->input('search', '');
// 	$comptes = DB::connection('oracle')
//     ->table(DB::raw('"COFINA"."CLIENT"'))
// 	// ->join(DB::raw('"COFINA"."COMPTE"'), 'CLIENT.matricule_client', '=', 'COMPTE.matricule_client')
// 	->where('matricule_client', '=', $search)
// 	->get();
// 	return $this->responseOk([
//         "data" => $comptes
//     ]);
// }
public function comptes(Request $requet)
{
    $search = $requet->input('search', '');
    
    // Requête SQL avec noms qualifiés pour éviter des erreurs de casse
    $comptes = DB::connection('oracle')
        ->table(DB::raw('"COFINA"."CLIENT" c'))  // Nom de la table CLIENT avec guillemets
        ->join(DB::raw('"COFINA"."COMPTE" cp'), 
            DB::raw('c."MATRICULE_CLIENT"'), '=', DB::raw('cp."MATRICULE_CLIENT"'))  // Utilisation des guillemets pour la colonne MATRICULE_CLIENT
        ->where(DB::raw('c."MATRICULE_CLIENT"'), '=', $search)  // Utilisation des guillemets pour la colonne MATRICULE_CLIENT
        ->select(DB::raw('c.*'), DB::raw('cp.*'))  // Sélectionner toutes les colonnes des deux tables avec guillemets
        ->get();
    
    return $this->responseOk([
        "data" => $comptes
    ]);
}

// public function prets(Request $requet){
// 	$search = $requet->input('search', '');
// 	$prets = DB::connection('oracle')
//     ->table(DB::raw('"COFINA"."PRET"'))
// 	->where('no_pret', '=', $search)
// 	->get();
// 	return $this->responseOk([
//         "data" => $prets
//     ]);
// }
public function prets(Request $requet){
	$search = $requet->input('search', '');
	$prets = DB::connection('oracle')
    ->table(DB::raw('"COFINA"."PRET" cp'))
	->join(DB::raw('"COFINA"."ECHEANCE" ce'), 
            DB::raw('cp."NO_PRET"'), '=', DB::raw('ce."NO_PRET"'))
	->where(DB::raw('cp."NO_PRET"'), '=', $search)
	->select(DB::raw('cp.*'), DB::raw('ce.*'))
	->get();
	return $this->responseOk([
        "data" => $prets
    ]);
}
}


