<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Traits\CustomResponseTrait;
use App\Models\Guarantor;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpWord\TemplateProcessor;
use Rmunate\Utilities\SpellNumber;


/**
 * @group Caution
 *
 * EndPoints pour gérer les cautions
 */
class GuarantorController extends Controller
{

    

    /**
     * Affiche les cautions
     *
     * @queryParam  contract_id                             int                 Filtrer par ID du contract.                                             No-example
     * @queryParam  notification_id                         int                 Filtrer par ID de la notification.                                      No-example
     * @queryParam  first_name                              string              Filtrer par prénom de la caution.                                       No-example
     * @queryParam  last_name                               string              Filtrer par nom de la caution.                                          No-example
     * @queryParam  birth_date                              string              Filtrer par date de naissance de la caution.                            No-example
     * @queryParam  birth_place                             string              Filtrer par lieu de naissance de la caution.                            No-example
     * @queryParam  nationality                             string              Filtrer par nationalité de la caution                                   No-example
     * @queryParam  home_address                            string              Filtrer par addresse de domicile de la caution                          No-example
     * @queryParam  type_of_identity_document               string              Filtrer par type de la pièce d'identité de la caution.                  No-example
     * @queryParam  number_of_identity_document             string              Filtrer par numéro de la pièce d'identité de la caution.                No-example
     * @queryParam  date_of_issue_of_identity_document      int                 Filtrer par date de délivrance de la pièce d'identité de la caution.    No-example
     * @queryParam  function                                int                 Filtrer par fonction de la caution                                      No-example
     * @queryParam  phone_number                            int                 Filtrer par numéro de téléphone de la caution                           No-example
     *
     * @queryParam  with_contract                           int                 Afficher le contrat.                                                    Example: 0
     * @queryParam  with_verbal_trial                       int                 Afficher le procès verbal.                                              Example: 0
     * @queryParam  with_notification                       int                 Afficher la notification.                                               Example: 0
     * @queryParam  paginate                                int                 Utiliser la pagination.                                                 Example: 0
     *
     * @response 200
     */
    public function index(Request $request)
    {
        if (($authorisation = Gate::inspect('viewAny', Guarantor::class))->allowed()) {
            $guarantorList = Guarantor::query();
            if ($search = $request->search) {
                $guarantorList
                    ->where(function ($query) use ($search) {
                        $query
                            ->where('contract_id', 'LIKE', "%$search%")
                            ->where('notification_id', 'LIKE', "%$search%")
                            ->orWhere('first_name', 'LIKE', "%$search%")
                            ->orWhere('last_name', 'LIKE', "%$search%")
                            ->orWhere('birth_date', 'LIKE', "%$search%")
                            ->orWhere('birth_place', 'LIKE', "%$search%")
                            ->orWhere('nationality', 'LIKE', "%$search%")
                            ->orWhere('home_address', 'LIKE', "%$search%")
                            ->orWhere('type_of_identity_document', 'LIKE', "%$search%")
                            ->orWhere('number_of_identity_document', 'LIKE', "%$search%")
                            ->orWhere('date_of_issue_of_identity_document', 'LIKE', "%$search%")
                            ->orWhere('function', 'LIKE', "%$search%")
                            ->orWhere('phone_number', 'LIKE', "%$search%")
                            ->orWhere(DB::raw("CONCAT(first_name, ' ', last_name)"), 'LIKE', "%$search%")
                        ;
                    });
            }

            foreach (["contract_id", "notification_id", "first_name", "last_name", "birth_date", "birth_place", "nationality", "home_address", "type_of_identity_document", "number_of_identity_document", "date_of_issue_of_identity_document", "function", "phone_number"] as $filter) {
                if (isset($request[$filter]) && $request[$filter]) {
                    $guarantorList->where($filter, $request[$filter]);
                }
            }

            foreach (["with_contract" => "contract", "with_verbal_trial" => "contract.verbal_trial", "with_notification" => "notification"] as $key => $value) {
                if (isset($request[$key]) && $request[$key]) {
                    $guarantorList->with($value);
                }
            }

            if (isset($request["paginate"]) && ($request->paginate == false)) {
                $guarantorList = $guarantorList->orderByDesc('created_at')->get();
                $data = ["data" => $guarantorList, "total" => count($guarantorList)];
            } else {
                $data = $guarantorList->orderByDesc('created_at')->paginate(8)->toArray();
            }

            return $this->responseOkPaginate($data);
        } else {
            return $this->responseError(["auth" => [$authorisation->message()]], 403);
        }
    }

    /**
     * Affiche une caution
     *
     * @urlParam    id                                      int     required    L'ID du caution.                                                        Example: 1
     *
     * @queryParam  with_contract                           int                 Afficher le contrat.                                                    Example: 0
     * @queryParam  with_verbal_trial                       int                 Afficher le PV.                                                         Example: 0
     * @queryParam  with_type_of_credit                     int                 Afficher le type de crédit.                                             Example: 0
     * @queryParam  with_type_of_applicant                  int                 Afficher le type de demandeur.                                          Example: 0
     * @queryParam  with_guarantees                         int                 Afficher les garanties.                                                 Example: 0
     *
     * @response 200
     */
    public function show(Request $request, int $id)
    {
        $guarantor = Guarantor::find($id);
        if ($guarantor) {
            if (($authorisation = Gate::inspect('view', $guarantor))->allowed()) {
                $suplementList = [];
                foreach (["with_contract" => "contract", "with_verbal_trial" => "contract.verbal_trial", "with_type_of_credit" => "contract.verbal_trial.type_of_credit", "with_type_of_applicant" => "contract.verbal_trial.type_of_credit.type_of_applicant", "with_guarantees" => "contract.verbal_trial.guarantees"] as $key => $value) {
                    if (isset($request[$key]) && $request[$key]) {
                        $suplementList[] = $value;
                    }
                }
                $guarantor->load($suplementList);
                return $this->responseOk(["guarantor" => $guarantor]);
            } else {
                return $this->responseError(["auth" => [$authorisation->message()]], 403);
            }
        } else {
            return $this->responseError(["id" => ["La caution n'existe pas"]], 404);
        }
    }

    /**
     * Télécharge la version word d'une caution
     *
     * @urlParam    id                                      int     required    L'ID du caution.                                                        Example: 1
     *
     * @response 200
     */
    public function download(Request $request, int $id)
    {
        $guarantor = Guarantor::find($id);
        if ($guarantor) {
            
            // if (($authorisation = Gate::inspect('view', $guarantor))->allowed()) {
            // $guarantor->load(["verbal_trial.type_of_credit.type_of_applicant", "verbal_trial.guarantees"]);
            $templateProcessor = new TemplateProcessor('../document_templates/Contracts/particular/contrat_cautionnement_particular.docx');
            // $templateProcessor = new TemplateProcessor('../document_templates/Contracts/particular/contract_caution_particular.docx');

            $data = $guarantor->toArray();
            $data = array_merge($data, collect($guarantor->contract)->mapWithKeys(function ($value, $key) {
                return ['contract.' . $key => $value];
            })->all());
            $data = array_merge($data, collect($guarantor->contract->verbal_trial)->mapWithKeys(function ($value, $key) {
                return ['contract.verbal_trial.' . $key => $value];
            })->all());
            $data = array_merge($data, collect($guarantor->contract->verbal_trial->type_of_credit)->mapWithKeys(function ($value, $key) {
                return ['contract.verbal_trial.type_of_credit.' . $key => $value];
            })->all());
            $data = array_merge($data, collect($guarantor->contract->verbal_trial->type_of_credit->type_of_applicant)->mapWithKeys(function ($value, $key) {
                return ['contract.verbal_trial.type_of_credit.type_of_applicant.' . $key => $value];
            })->all());

            $data["ht_rate"] = "17";
            $data["current_date"] = Carbon::now()->translatedFormat('d F Y');
            $data["amount_plus_interets"] = (float)$data["contract.verbal_trial.amount"] + (float)$data["contract.verbal_trial.due_amount"];
            $data["contract.verbal_trial.amount.fr"] = SpellNumber::value((float) $data["contract.verbal_trial.amount"])->locale('fr')->toLetters();
            $data["contract.total_amount_of_interest.fr"] = SpellNumber::value((float) $data["contract.total_amount_of_interest"])->locale('fr')->toLetters();
            $data["contract.verbal_trial.duration.fr"] = SpellNumber::value((float) $data["contract.verbal_trial.duration"])->locale('fr')->toLetters();
            $data["contract.verbal_trial.due_amount.fr"] = SpellNumber::value((float) $data["contract.verbal_trial.due_amount"])->locale('fr')->toLetters();
            $data["contract.total_to_pay"] = (float) $data["contract.total_amount_of_interest"] + (float) $data["contract.verbal_trial.amount"];
            $data["contract.total_to_pay.fr"] = SpellNumber::value((float) $data["contract.total_to_pay"])->locale('fr')->toLetters();
            $data["contract.verbal_trial.duration.fr"] = SpellNumber::value((float) $data["contract.verbal_trial.duration"])->locale('fr')->toLetters();
            $data["contract.verbal_trial.periodicity.fr"] = ["mensual" => "Mensuel", "quarterly" => "Trimestrielle", "semi-annual" => "Semestrielle", "annual" => "Annuel", "in-fine" => "A la fin"][$data["contract.verbal_trial.periodicity"]];
            $data["contract.verbal_trial.periodicity.fr2"] = ["mensual" => "chaque mois", "quarterly" => "chaque trimestre", "semi-annual" => "chaque semestre", "annual" => "chaque année", "in-fine" => "A la fin."][$data["contract.verbal_trial.periodicity"]];
            $data["contract.verbal_trial.periodicity.fr3"] = ["mensual" => "mensualité", "quarterly" => "trimestre", "semi-annual" => "semestre", "annual" => "année", "in-fine" => "echéance."][$data["contract.verbal_trial.periodicity"]];
            $data["line_review_bonus"] = (((float) $data["contract.verbal_trial.duration"]) < 18) ? "" : "Prime de révision de ligne      : « 1% du capital restant dû après 12 mois »";
            $data["signatory"] = (((float) $data["contract.verbal_trial.amount"]) <= 10000000) ? "Madame Ameh Délali MESSANGAN épouse AMEDEMEGNAH, Responsable juridique" : "Mr. Koffi Djramedo GAMADO, Head Crédit";

            $data["contract.verbal_trial.amount"] = number_format(((float) $data["contract.verbal_trial.amount"]), 0, ',', ' ');
            $data["amount_plus_interets"] = number_format(((float) $data["amount_plus_interets"]),0,',','');
            // dd($data["amount_plus_interets"]);
            $data["contract.total_amount_of_interest"] = number_format(((float) $data["contract.total_amount_of_interest"]), 0, ',', ' ');
            $data["contract.verbal_trial.due_amount"] = number_format(((float) $data["contract.verbal_trial.due_amount"]), 0, ',', ' ');
            $data["contract.verbal_trial.administrative_fees_percentage"] = number_format(((float) $data["contract.verbal_trial.administrative_fees_percentage"]), 0, ',', ' ');
            $data["contract.verbal_trial.insurance_premium"] = number_format(((float) $data["contract.verbal_trial.insurance_premium"]), 0, ',', ' ');
            $data["contract.total_to_pay"] = number_format(((float) $data["contract.total_to_pay"]), 0, ',', ' ');
            $data["date_of_issue_of_identity_document"] = Carbon::createFromFormat('Y-m-d', $data["date_of_issue_of_identity_document"])->translatedFormat('d F Y');
            $data["birth_date"] = Carbon::createFromFormat('Y-m-d', $data["birth_date"])->translatedFormat('d F Y');
            $data["contract.representative_birth_date"] = Carbon::createFromFormat('Y-m-d', $data["contract.representative_birth_date"])->translatedFormat('d F Y');
            $data["contract.representative_date_of_issue_of_identity_document"] = Carbon::createFromFormat('Y-m-d', $data["contract.representative_date_of_issue_of_identity_document"])->translatedFormat('d F Y');
            $guaranteeList = [];
            foreach ($guarantor->contract->verbal_trial->guarantees as $guarantee) {
                $guaranteeList[] = array_merge($guarantee->toArray(), collect($guarantee->type_of_guarantee)->mapWithKeys(function ($value, $key) {
                    return ['type_of_guarantee.' . $key => $value];
                })->all());
            }
            unset($data["observations"]);
            unset($data["contract.observations"]);
            unset($data["contract.guarantors"]);
            // dd($data);
            $templateProcessor->setValues($data);
            $templateProcessor->cloneBlock('guaranteeList', 0, true, false, $guaranteeList);

            // Enregistrez les modifications dans un nouveau fichier
            $outputFilePath = public_path("Contrat-caution-" . $guarantor->contract->verbal_trial->committee_id . ".docx");
            $templateProcessor->saveAs($outputFilePath);

            return Response::file($outputFilePath, ["Content-Type" => "application/vnd.openxmlformats-officedocument.wordprocessingml.document"]);
        } else {
            return $this->responseError(["id" => "La caution n'existe pas"], 404);
        }
    }
    /**
     * Télécharge le billet à ordre d'une caution
     *
     * @urlParam    id                                      int     required    L'ID du caution.                                                        Example: 1
     *
     * @response 200
     */
    public function promissory_note(Request $request, int $id)
    {
        $guarantor = Guarantor::find($id);
        if ($guarantor) {
            // if (($authorisation = Gate::inspect('view', $guarantor))->allowed()) {
            $templateProcessor = new TemplateProcessor('../document_templates/Contracts/particular/billet_a_ordre_caution_particular.docx');

            $data = $guarantor->toArray();
            $data = array_merge($data, collect($guarantor->contract)->mapWithKeys(function ($value, $key) {
                return ['contract.' . $key => $value];
            })->all());
            $data = array_merge($data, collect($guarantor->contract->verbal_trial)->mapWithKeys(function ($value, $key) {
                return ['contract.verbal_trial.' . $key => $value];
            })->all());
            $data = array_merge($data, collect($guarantor->contract->verbal_trial->type_of_credit)->mapWithKeys(function ($value, $key) {
                return ['contract.verbal_trial.type_of_credit.' . $key => $value];
            })->all());
            $data = array_merge($data, collect($guarantor->contract->verbal_trial->type_of_credit->type_of_applicant)->mapWithKeys(function ($value, $key) {
                return ['contract.verbal_trial.type_of_credit.type_of_applicant.' . $key => $value];
            })->all());

            $data["ht_rate"] = "17";
            $data["current_date"] = Carbon::now()->translatedFormat('d F Y');
            $data["contract.verbal_trial.amount.fr"] = SpellNumber::value((float) $data["contract.verbal_trial.amount"])->locale('fr')->toLetters();
            $data["contract.total_amount_of_interest.fr"] = SpellNumber::value((float) $data["contract.total_amount_of_interest"])->locale('fr')->toLetters();
            $data["contract.verbal_trial.duration.fr"] = SpellNumber::value((float) $data["contract.verbal_trial.duration"])->locale('fr')->toLetters();
            $data["contract.verbal_trial.due_amount.fr"] = SpellNumber::value((float) $data["contract.verbal_trial.due_amount"])->locale('fr')->toLetters();
            $data["contract.total_to_pay"] = (float) $data["contract.total_amount_of_interest"] + (float) $data["contract.verbal_trial.amount"];
            $data["contract.total_to_pay.fr"] = SpellNumber::value((float) $data["contract.total_to_pay"])->locale('fr')->toLetters();
            $data["contract.verbal_trial.duration.fr"] = SpellNumber::value((float) $data["contract.verbal_trial.duration"])->locale('fr')->toLetters();
            $data["contract.verbal_trial.periodicity.fr"] = ["mensual" => "Mensuel", "quarterly" => "Trimestrielle", "semi-annual" => "Semestrielle", "annual" => "Annuel", "in-fine" => "A la fin"][$data["contract.verbal_trial.periodicity"]];
            $data["contract.verbal_trial.periodicity.fr2"] = ["mensual" => "chaque mois", "quarterly" => "chaque trimestre", "semi-annual" => "chaque semestre", "annual" => "chaque année", "in-fine" => "A la fin."][$data["contract.verbal_trial.periodicity"]];
            $data["contract.verbal_trial.periodicity.fr3"] = ["mensual" => "mensualité", "quarterly" => "trimestre", "semi-annual" => "semestre", "annual" => "année", "in-fine" => "echéance."][$data["contract.verbal_trial.periodicity"]];
            $data["line_review_bonus"] = (((float) $data["contract.verbal_trial.duration"]) < 18) ? "" : "Prime de révision de ligne      : « 1% du capital restant dû après 12 mois »";
            $data["signatory"] = (((float) $data["contract.verbal_trial.amount"]) <= 10000000) ? "Madame Ameh Délali MESSANGAN épouse AMEDEMEGNAH, Responsable juridique" : "Mr. Koffi Djramedo GAMADO, Head Crédit";


            $data["contract.verbal_trial.amount"] = number_format(((float) $data["contract.verbal_trial.amount"]), 0, ',', ' ');
            $data["contract.total_amount_of_interest"] = number_format(((float) $data["contract.total_amount_of_interest"]), 0, ',', ' ');
            $data["contract.verbal_trial.due_amount"] = number_format(((float) $data["contract.verbal_trial.due_amount"]), 0, ',', ' ');
            $data["contract.total_to_pay"] = number_format(((float) $data["contract.total_to_pay"]), 0, ',', ' ');

            unset($data["observations"]);
            unset($data["contract.observations"]);
            unset($data["contract.guarantors"]);
            $templateProcessor->setValues($data);
            // dd($data);
            // Enregistrez les modifications dans un nouveau fichier
            $outputFilePath = public_path("Billet-a-ordre-caution-" . $guarantor->contract->verbal_trial->committee_id . ".docx");
            $templateProcessor->saveAs($outputFilePath);

            return Response::file($outputFilePath, ["Content-Type" => "application/vnd.openxmlformats-officedocument.wordprocessingml.document"]);
        } else {
            return $this->responseError(["id" => ["La caution n'existe pas"]], 404);
        }
    }

    /**
     * Créer une nouvelLa caution
     *
     * @bodyParam  contract_id                              int                 ID du contract en cas de contrat normal.                                Example: 1
     * @bodyParam  notification_id                          int                 ID de la notification en cas contrat hypothécaire.                      Example: 1
     * @bodyParam  civility                                 string              Civilité de la caution.                                                 Example: Mr
     * @bodyParam  first_name                               string              Prénom de la caution.                                                   Example: Charles
     * @bodyParam  last_name                                string              Nom de la caution.                                                      Example: Xavier
     * @bodyParam  birth_date                               string              Date de naissance de la caution.                                        Example: 2000-03-03
     * @bodyParam  birth_place                              string              Lieu de naissance de la caution.                                        Example: Los Afagnan
     * @bodyParam  nationality                              string              Nationalité de la caution                                               Example: Togolaise
     * @bodyParam  home_address                             string              Addresse de domicile de la caution                                      Example: Adewi
     * @bodyParam  type_of_identity_document                string              Type de la pièce d'identité de la caution.                              Example: cni
     * @bodyParam  number_of_identity_document              string              Numéro de la pièce d'identité de la caution.                            Example: BP785632
     * @bodyParam  date_of_issue_of_identity_document       string              Date de délivrance de la pièce d'identité de la caution.                Example: 2022-03-03
     * @bodyParam  function                                 string              Fonction de la caution                                                  Example: Agent CIA
     * @bodyParam  phone_number                             string              Numéro de téléphone de la caution                                       Example: +01 587-45-632-15
     *
     * @response 200
     */
    public function store(Request $request)
    {
        if (($authorisation = Gate::inspect('create', Guarantor::class))->allowed()) {
            $requestData = $request->all();
            $validator = Validator::make($requestData, ["contract_id" => "required|exists:contracts,id"]);
            if ($validator->fails()) {
                $validator = Validator::make($requestData, ["notification_id" => "required|exists:notifications,id"]);
                if ($validator->fails()) {
                    return $this->responseError(["error" => ["Le contrat ou la notification est manquante"]], 400);
                }
            }

            $validator = Validator::make($requestData, [
                'civility' => 'required|in:Mr,Mme,Mlle',
                'first_name' => 'required|min:2',
                'last_name' => 'required|min:2',
                'birth_date' => 'required|date',
                'birth_place' => 'required|min:2',
                'nationality' => 'required|min:2',
                'home_address' => 'required|min:2',
                'type_of_identity_document' => 'required|in:cni,passport,residence_certificate,driving_licence,carte_sej',
                'number_of_identity_document' => 'required|min:2',
                'date_of_issue_of_identity_document' => 'required|date',
                'function' => 'required|min:2',
                'phone_number' => 'required|min:2',
            ]);
            if ($validator->fails()) {
                return $this->responseError($validator->errors(), 400);
            } else {
                $guarantor = Guarantor::create($requestData);
                $guarantor->load(["contract", "contract.verbal_trial", "contract.verbal_trial.type_of_credit.type_of_applicant", "contract.verbal_trial.guarantees"]);
                return $this->responseOk([
                    "guarantor" => $guarantor
                ], status: 201);
            }
        } else {
            return $this->responseError(["auth" => [$authorisation->message()]], 403);
        }
    }

    /**
     * Mettre à jour une caution
     *
     * @urlParam    id                                      int     required    L'ID du caution.                                                        Example: 1
     *
     * @bodyParam  contract_id                              int                 ID du contract en cas de contrat normal.                                Example: 1
     * @bodyParam  notification_id                          int                 ID de la notification en cas contrat hypothécaire.                      Example: 1
     * @bodyParam  civility                                 string              Civilité de la caution.                                                 Example: Mr
     * @bodyParam  first_name                               string              Prénom de la caution.                                                   Example: Charles
     * @bodyParam  last_name                                string              Nom de la caution.                                                      Example: Xavier
     * @bodyParam  birth_date                               string              Date de naissance de la caution.                                        Example: 2000-03-03
     * @bodyParam  birth_place                              string              Lieu de naissance de la caution.                                        Example: Los Afagnan
     * @bodyParam  nationality                              string              Nationalité de la caution                                               Example: Togolaise
     * @bodyParam  home_address                             string              Addresse de domicile de la caution                                      Example: Adewi
     * @bodyParam  type_of_identity_document                string              Type de la pièce d'identité de la caution.                              Example: cni
     * @bodyParam  number_of_identity_document              string              Numéro de la pièce d'identité de la caution.                            Example: BP785632
     * @bodyParam  date_of_issue_of_identity_document       string              Date de délivrance de la pièce d'identité de la caution.                Example: 2022-03-03
     * @bodyParam  function                                 string              Fonction de la caution                                                  Example: Agent CIA
     * @bodyParam  phone_number                             string              Numéro de téléphone de la caution                                       Example: +01 587-45-632-15

     * @response 200
     *
     */
    public function update(Request $request, int $id)
    {
        $guarantor = Guarantor::find($id);
        if ($guarantor) {
            if (($authorisation = Gate::inspect('update', $guarantor))->allowed()) {
                $requestData = $request->all();
                $validator = Validator::make($requestData, ["contract_id" => "required|exists:contracts,id"]);
                if ($validator->fails()) {
                    $validator = Validator::make($requestData, ["notification_id" => "required|exists:notifications,id"]);
                    if ($validator->fails()) {
                        return $this->responseError(["error" => ["Le contrat ou la notification est manquante"]], 400);
                    }
                }
                $validator = Validator::make($requestData, [
                    'civility' => 'required|in:Mr,Mme,Mlle',
                    'first_name' => 'required|min:2',
                    'last_name' => 'required|min:2',
                    'birth_date' => 'required|date',
                    'birth_place' => 'required|min:2',
                    'nationality' => 'required|min:2',
                    'home_address' => 'required|min:2',
                    'type_of_identity_document' => 'required|in:cni,passport,residence_certificate,driving_licence',
                    'number_of_identity_document' => 'required|min:2',
                    'date_of_issue_of_identity_document' => 'required|date',
                    'function' => 'required|min:2',
                    'phone_number' => 'required|min:2',
                ]);
                if ($validator->fails()) {
                    return $this->responseError($validator->errors(), 400);
                } else {
                    $guarantor->update($requestData);
                    $guarantor->load(["contract.verbal_trial", "contract.verbal_trial.type_of_credit.type_of_applicant", "contract.verbal_trial.guarantees"]);
                    return $this->responseOk([
                        "guarantor" => $guarantor
                    ]);
                }
            } else {
                return $this->responseError(["auth" => [$authorisation->message()]], 403);
            }
        } else {
            return $this->responseError(["id" => ["La caution n'existe pas"]], 404);
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
        $guarantor = Guarantor::find($id);
        if ($guarantor) {
            if (($authorisation = Gate::inspect('upload', $guarantor))->allowed()) {
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
                $path = 'upload/guarantors/signed_' . $document_category . 's/' . $guarantor->id . '-signed.' . $extension;
                Storage::disk("public")->put($path, $documentData);
                $guarantor->update(["signed_{$document_category}_path" => "/storage/" . $path]);

                DB::commit();
                return $this->responseOk(["guarantor" => $guarantor]);
            } else {
                return $this->responseError(["auth" => [$authorisation->message()]], 403);
            }
        } else {
            return $this->responseError(["id" => "La garantie n'existe pas"], 404);
        }
    }

    /**
     * Supprime une caution
     *
     * @urlParam    id                                      int     required    L'ID du caution.                                                        Example: 1
     *
     * @response 204
     */
    public function destroy(int $id)
    {
        $guarantor = Guarantor::find($id);
        if ($guarantor) {
            if (($authorisation = Gate::inspect('delete', $guarantor))->allowed()) {
                if ($guarantor->delete()) {
                    return $this->responseOk(messages: ["guarantor" => "La caution a été supprimé"], status: 204);
                } else {
                    return $this->responseError(["server" => "Erreur du serveur"], 500);
                }
            } else {
                return $this->responseError(["auth" => [$authorisation->message()]], 403);
            }
        } else {
            return $this->responseError(["id" => ["La caution n'existe pas"]], 404);
        }

    }
}
