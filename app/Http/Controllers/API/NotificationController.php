<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Traits\CustomResponseTrait;
use App\Jobs\SendEmail;
use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpWord\TemplateProcessor;
use Rmunate\Utilities\SpellNumber;

/**
 * @group Notification
 *
 * EndPoints pour gérer les notification
 */
class NotificationController extends Controller
{

    /**
     * Affiche les notification
     *
     * @queryParam  verbal_trial_id                                         int                 Filtrer par ID du PV.                                                   No-example
     * @queryParam  representative_phone_number                             string              Filtrer par Numéro de téléphone du demandeur.                           No-example
     * @queryParam  representative_home_address                             string              Filtrer par statut de validation du head credit                         No-example
     * @queryParam  number_of_due_dates                                     int                 Filtrer par nombre d'échéance.                                          Example: 0
     * @queryParam  risk_premium_percentage                                 int                 Filtrer par prime de risque (en pourcentage) du crédit du demandeur.    No-example
     * @queryParam  head_credit_observation                                 string              Filtrer par présence de cat.                                            Example: 0
     * @queryParam  head_credit_validation                                  string              Filtrer par présence de cat.                                            Example: 0
     * @queryParam  status                                                  string              Filtrer par présence de cat.                                            Example: 0
     * @queryParam  status_observation                                      string              Filtrer par présence de cat.                                            Example: 0
     * @queryParam  signed_notification_path                                string              Filtrer par présence de cat.                                            Example: 0
     * @queryParam  signed_contract_path                                    string              Filtrer par présence de cat.                                            Example: 0
     * @queryParam  signed_promissory_note_path                             string              Filtrer par présence de cat.                                            Example: 0
     * @queryParam  creator_id                                              int                 Filtrer par présence de cat.                                            Example: 0
     * @queryParam  sent                                                    int                 Filtrer par présence de cat.                                            Example: 0
     * @queryParam  total_amount_of_interest                                int                 Filtrer par présence de cat.                                            Example: 0
     * @queryParam  representative_type_of_identity_document                string              Filtrer par présence de cat.                                            Example: 0
     * @queryParam  representative_number_of_identity_document              string              Filtrer par présence de cat.                                            Example: 0
     * @queryParam  representative_date_of_issue_of_identity_document       string              Filtrer par présence de cat.                                            Example: 0
     * @queryParam  type                                                    string              Filtrer par présence de cat.                                            Example: 0
     * @queryParam  business_denomination                                   string              Filtrer par présence de cat.                                            Example: 0
     * @queryParam  is_simple                                               int                 Filtrer par présence de cat.                                            Example: 0
     * 
     * 
     * @queryParam  has_signed_contract                                     int                 Filtrer par présence de contrat signé.                                  Example: 0
     * @queryParam  has_upload_completed                                    int                 Filtrer par billet à ordre.                                             Example: 0
     * @queryParam  has_cat                                                 int                 Filtrer par présence de cat.                                            Example: 0
     *
     * @queryParam  with_verbal_trial                                       int                 Afficher le PV.                                                         Example: 0
     * @queryParam  with_type_of_credit                                     int                 Afficher le type de crédit.                                             Example: 0
     * @queryParam  with_type_of_applicant                                  int                 Afficher le type de demandeur.                                          Example: 0
     * @queryParam  with_caf                                                int                 Afficher le caf en charge du dossier.                                   Example: 0
     * @queryParam  with_guarantees                                         int                 Afficher les garanties.                                                 Example: 0
     * @queryParam  with_type_of_guarantees                                 int                 Afficher les types des garanties.                                       Example: 0
     * @queryParam  with_creator                                            int                 Afficher le créateur de la notification.                                Example: 0
     * @queryParam  paginate                                                int                 Utiliser la pagination.                                                 Example: 0
     *
     * @response 200
     */
    public function index(Request $request)
    {
        if (($authorisation = Gate::inspect('viewAny', Notification::class))->allowed()) {
            $notificationList = Notification::query();
            if ($search = $request->search) {
                $notificationList->where(function ($query) use ($search) {
                    $query
                        ->where('phone_number', 'LIKE', "%$search%")
                        ->where('representative_home_address', 'LIKE', "%$search%")
                        ->where('number_of_due_dates', 'LIKE', "%$search%")
                        ->where('risk_premium_percentage', 'LIKE', "%$search%")
                        ->where('sent', 'LIKE', "%$search%")
                    ;
                })
                ;
            }

            if (isset($request["has_cat"])) {
                $has_cat = (int) $request["has_cat"];
                if ($has_cat == 1) {
                    $notificationList->whereHas('c_a_t');
                } else if ($has_cat == 0) {
                    $notificationList->whereDoesntHave('c_a_t');
                }
            }

            foreach (["verbal_trial_id", "phone_number", "representative_home_address", "number_of_due_dates", "risk_premium_percentage", "sent", "is_simple"] as $filter) {
                if (isset($request[$filter]) && $request[$filter] != "") {
                    $notificationList->where($filter, $request[$filter]);
                }
            }

            if (isset($request["head_credit_validation"])) {
                $notificationList->where(function ($query) use ($request) {
                    foreach (str_split($request["head_credit_validation"]) as $char) {
                        if (in_array($char, ['w', 'v', 'r', 'c'])) {
                            $query->orWhere("head_credit_validation", ["w" => "waiting", "v" => "validated", "r" => "rejected"][$char]);
                        }
                    }
                });
            }

            foreach (["with_verbal_trial" => "verbal_trial", "with_type_of_credit" => "verbal_trial.type_of_credit", "with_type_of_applicant" => "verbal_trial.type_of_credit.type_of_applicant", "with_guarantees" => "verbal_trial.guarantees", "with_caf" => "verbal_trial.caf", "with_type_of_guarantees" => "verbal_trial.guarantees.type_of_guarantee", "with_creator" => "creator", "with_pledges" => "pledges"] as $key => $value) {
                if (isset($request[$key]) && $request[$key]) {
                    $notificationList->with($value);
                }
            }

            if (($currentUser = $request->user())->profile == "caf") {
                $notificationList->whereHas('verbal_trial', function ($query) use ($currentUser) {
                    $query->where('caf_id', $currentUser->id);
                });
            }

            if (isset($request["signed_notification"])) {
                if ($request["signed_notification"]) {
                    $notificationList->whereNotNull('signed_notification_path');
                } else {
                    $notificationList->whereNull('signed_notification_path');
                }
            }

            if (isset($request["has_signed_contract"])) {
                if ($request["has_signed_contract"]) {
                    $notificationList->whereNotNull('signed_contract_path');
                } else {
                    $notificationList->whereNull('signed_contract_path');
                }
            }

            if (isset($request["has_upload_completed"])) {
                if ($request["has_upload_completed"]) {
                    $notificationList->whereNotNull('signed_notification_path')->whereNotNull('signed_contract_path')->whereNotNull('signed_promissory_note_path')->where(function ($query) {
                        $query->whereDoesntHave('guarantors', function ($query) {
                            $query->whereNull('signed_promissory_note_path');
                        });
                    });
                } else {
                    $notificationList->where(function ($query) {
                        $query->whereNull('signed_notification_path')->orWhereNull('signed_contract_path')->orWhereNull('signed_promissory_note_path')->orWhereHas('guarantors', function ($query) {
                            $query->whereNull('signed_promissory_note_path');
                        });
                    });
                }
            }
            // return $notificationList->toSql();


            if (isset($request["paginate"]) && ($request->paginate == false)) {
                $notificationList = $notificationList->orderByDesc('updated_at')->get();
                $data = ["data" => $notificationList, "total" => count($notificationList)];
            } else {
                $data = $notificationList->orderByDesc('updated_at')->paginate(8)->toArray();
            }


            return $this->responseOkPaginate($data);
        } else {
            return $this->responseError(["auth" => [$authorisation->message()]], 403);
        }
    }

    /**
     * Affiche une notification
     *
     * @urlParam    id                                                      int     required    L'ID de la notification.                                                        Example: 1
     *
     * @queryParam  with_verbal_trial                                       int                 Afficher le PV.                                                                 Example: 0
     * @queryParam  with_type_of_credit                                     int                 Afficher le type de crédit.                                                     Example: 0
     * @queryParam  with_type_of_applicant                                  int                 Afficher le type de demandeur.                                                  Example: 0
     * @queryParam  with_caf                                                int                 Afficher le CAF en charge du dossier.                                           Example: 0
     * @queryParam  with_creator                                            int                 Afficher le créateur de la notification.                                        Example: 0
     * @queryParam  with_guarantees                                         int                 Afficher les garanties.                                                         Example: 0
     * @queryParam  with_type_of_guarantees                                 int                 Afficher les types des garanties.                                               Example: 0
     *
     * @response 200
     */
    public function show(Request $request, int $id)
    {
        $notification = Notification::find($id);
        if ($notification) {
            if (($authorisation = Gate::inspect('view', $notification))->allowed()) {
                $suplementList = [];
                foreach (["with_verbal_trial" => "verbal_trial", "with_type_of_credit" => "verbal_trial.type_of_credit", "with_type_of_applicant" => "verbal_trial.type_of_credit.type_of_applicant", "with_guarantees" => "verbal_trial.guarantees", "with_caf" => "verbal_trial.caf", "with_type_of_guarantees" => "verbal_trial.guarantees.type_of_guarantee", "with_creator" => "creator"] as $key => $value) {
                    if (isset($request[$key]) && $request[$key]) {
                        $suplementList[] = $value;
                    }
                }
                $notification->load($suplementList);
                return $this->responseOk(["notification" => $notification]);
            } else {
                return $this->responseError(["auth" => [$authorisation->message()]], 403);
            }
        } else {
            return $this->responseError(["id" => "La notification n'existe pas"], 404);
        }
    }

    /**
     * Télécharge la version word d'une notification
     *
     * @urlParam    id                                                      int     required    L'ID de la notification.                                                        Example: 1
     *
     * @response 200
     */
    public function download(Request $request, int $id)
    {
        $notification = Notification::find($id);
        if ($notification) {
            // if (($authorisation = Gate::inspect('view', $notification))->allowed()) {
            $templateProcessor = new TemplateProcessor(($notification->head_credit_validation == "validated") ? "../document_templates/Notifications/Notification-validated.docx" : "../document_templates/Notifications/Notification.docx");

            $data = $notification->toArray();
            $data = array_merge($data, collect($notification->verbal_trial)->mapWithKeys(function ($value, $key) {
                return ['verbal_trial.' . $key => $value];
            })->all());
            $data = array_merge($data, collect($notification->verbal_trial->type_of_credit)->mapWithKeys(function ($value, $key) {
                return ['verbal_trial.type_of_credit.' . $key => $value];
            })->all());
            $data = array_merge($data, collect($notification->verbal_trial->type_of_credit->type_of_applicant)->mapWithKeys(function ($value, $key) {
                return ['verbal_trial.type_of_credit.type_of_applicant.' . $key => $value];
            })->all());
            if ($notification->type == "company") {
                $data = array_merge($data, collect($notification->company)->mapWithKeys(function ($value, $key) {
                    return ['company.' . $key => $value];
                })->all());
            } elseif ($notification->type == "individual_business") {
                $data = array_merge($data, collect($notification->individual_business)->mapWithKeys(function ($value, $key) {
                    return ['individual_business.' . $key => $value];
                })->all());
            }
            $data["ht_rate"] = "17";
            $data["verbal_trial.civility.2"] = ["Mr" => "Monsieur", "Mme" => "Madame", "Mlle" => "Madame"][$data["verbal_trial.civility"]];
            $data["current_date"] = Carbon::now()->translatedFormat('d F Y');
            $data["verbal_trial.day_due_amount"] = ((float) $data["verbal_trial.due_amount"]) / 20;
            $data["verbal_trial.administrative_fees_percentage.value"] = number_format((float) $data["verbal_trial.administrative_fees_percentage"] * $data["verbal_trial.amount"] / 100, 0, ',', ' ');
            $data["verbal_trial.day_due_amount.fr"] = SpellNumber::value((float) $data["verbal_trial.day_due_amount"])->locale('fr')->toLetters();
            $data["verbal_trial.amount.fr"] = SpellNumber::value((float) $data["verbal_trial.amount"])->locale('fr')->toLetters();
            $data["verbal_trial.duration.fr"] = SpellNumber::value((float) $data["verbal_trial.duration"])->locale('fr')->toLetters();
            $data["verbal_trial.due_amount.fr"] = SpellNumber::value((float) $data["verbal_trial.due_amount"])->locale('fr')->toLetters();
            $data["verbal_trial.duration.fr"] = SpellNumber::value((float) $data["verbal_trial.duration"])->locale('fr')->toLetters();
            $data["verbal_trial.periodicity.fr"] = ["mensual" => "Mensuel", "quarterly" => "Trimestrielle", "semi-annual" => "Semestrielle", "annual" => "Annuel", "in-fine" => "A la fin"][$data["verbal_trial.periodicity"]];
            $data["verbal_trial.periodicity.fr2"] = ["mensual" => "chaque mois", "quarterly" => "chaque trimestre", "semi-annual" => "chaque semestre", "annual" => "chaque année", "in-fine" => "A la fin."][$data["verbal_trial.periodicity"]];
            $data["verbal_trial.periodicity.fr3"] = ["mensual" => "mensualité", "quarterly" => "trimestre", "semi-annual" => "semestre", "annual" => "année", "in-fine" => "echéance."][$data["verbal_trial.periodicity"]];
            $data["line_review_bonus"] = (((float) $data["verbal_trial.duration"]) < 18) ? "" : "Prime de révision de ligne                               : « 1% du capital restant dû après 12 mois »";
            $data["verbal_trial.amount"] = number_format(((float) $data["verbal_trial.amount"]), 0, ',', ' ');
            $data["verbal_trial.day_due_amount"] = number_format(((float) $data["verbal_trial.day_due_amount"]), 0, ',', ' ');
            $data["verbal_trial.due_amount"] = number_format(((float) $data["verbal_trial.due_amount"]), 0, ',', ' ');
            $data["verbal_trial.administrative_fees_percentage"] = number_format(((float) $data["verbal_trial.administrative_fees_percentage"]), 0, ',', ' ');
            $data["verbal_trial.insurance_premium"] = number_format(((float) $data["verbal_trial.insurance_premium"]), 0, ',', ' ');

            $guaranteeList = [];
            foreach ($notification->verbal_trial->guarantees as $guarantee) {
                $tmp = $guarantee->toArray();
                $tmp["value"] = number_format((float) $tmp["value"], 0, ',', ' ');
                $guaranteeList[] = array_merge($tmp, collect($guarantee->type_of_guarantee)->mapWithKeys(function ($value, $key) {
                    return ['type_of_guarantee.' . $key => $value];
                })->all());
            }
            $templateProcessor->cloneBlock('guaranteeList', 0, true, false, $guaranteeList);

            unset($data["observations"]);
            unset($data["guarantors"]);
            $templateProcessor->setValues($data);

            // Enregistrez les modifications dans un nouveau fichier
            $outputFilePath = public_path("Contrat-" . $notification->verbal_trial->committee_id . ".docx");
            $templateProcessor->saveAs($outputFilePath);

            return Response::file($outputFilePath, ["Content-Type" => "application/vnd.openxmlformats-officedocument.wordprocessingml.document"])->deleteFileAfterSend(true);
            // } else {
            //     return $this->responseError(["auth" => [$authorisation->message()]], 403);
            // }
        } else {
            return $this->responseError(["id" => "La notification n'existe pas"], 404);
        }
    }

    /**
     * Télécharge le billet à ordre d'une notification
     *
     * @urlParam    id                                                      int     required    L'ID de la notification.                                                    Example: 1
     *
     * @response 200
     */
    public function promissory_note(Request $request, int $id)
    {
        $notification = Notification::find($id);
        if ($notification) {
            if (($authorisation = Gate::inspect('download', $notification))->allowed()) {
                $templateProcessor = new TemplateProcessor("../document_templates/Notifications/$notification->type/billet_a_ordre_$notification->type.docx");
                $data = $notification->toArray();
                $data = array_merge($data, collect($notification->verbal_trial)->mapWithKeys(function ($value, $key) {
                    return ['verbal_trial.' . $key => $value];
                })->all());

                $data = array_merge($data, collect($notification->verbal_trial->type_of_credit)->mapWithKeys(function ($value, $key) {
                    return ['verbal_trial.type_of_credit.' . $key => $value];
                })->all());
                $data = array_merge($data, collect($notification->verbal_trial->type_of_credit->type_of_applicant)->mapWithKeys(function ($value, $key) {
                    return ['verbal_trial.type_of_credit.type_of_applicant.' . $key => $value];
                })->all());

                // $data["ht_rate"] = "17";
                $data["current_date"] = Carbon::now()->format("d/m/Y");
                $data["total_amount_of_interest.fr"] = SpellNumber::value((float) $data["total_amount_of_interest"])->locale('fr')->toLetters();
                $data["total_to_pay"] = (float) $data["total_amount_of_interest"] + (float) $data["verbal_trial.amount"];
                $data["total_to_pay.fr"] = SpellNumber::value((float) $data["total_to_pay"])->locale('fr')->toLetters();
                $data["signatory"] = (((float) $data["verbal_trial.amount"]) <= 10000000) ? "Madame Ameh Délali MESSANGAN épouse AMEDEMEGNAH, Responsable juridique" : "Mr. Koffi Djramedo GAMADO, Head Crédit";
                $data["line_review_bonus"] = (((float) $data["verbal_trial.duration"]) < 18) ? "" : "Prime de révision de ligne      : « 1% du capital restant dû après 12 mois »";
                $data["representative_type_of_identity_document"] = [
                    "cni" => "Carte d'identité nationale",
                    "passport" => "Passeport",
                    "residence_certificate" => "Certificat de résidence",
                    "driving_licence" => "Permis de conduire"
                ][$data["representative_type_of_identity_document"]];
                $data["total_amount_of_interest"] = number_format(((float) $data["total_amount_of_interest"]), 0, ',', ' ');
                $data["total_to_pay"] = number_format(((float) $data["total_to_pay"]), 0, ',', ' ');

                $data["verbal_trial.periodicity.fr2"] = ["mensual" => "chaque mois", "quarterly" => "chaque trimestre", "semi-annual" => "chaque semestre", "annual" => "chaque année", "in-fine" => "A la fin."][$data["verbal_trial.periodicity"]];
                $data["verbal_trial.periodicity.fr3"] = ["mensual" => "mensualité", "quarterly" => "trimestre", "semi-annual" => "semestre", "annual" => "année", "in-fine" => "echéance."][$data["verbal_trial.periodicity"]];
                $data["verbal_trial.due_amount.fr"] = SpellNumber::value((float) $data["verbal_trial.due_amount"])->locale('fr')->toLetters();
                $data["verbal_trial.amount.fr"] = SpellNumber::value((float) $data["verbal_trial.amount"])->locale('fr')->toLetters();
                $data["verbal_trial.duration.fr"] = SpellNumber::value((float) $data["verbal_trial.duration"])->locale('fr')->toLetters();
                $data["verbal_trial.duration.fr"] = SpellNumber::value((float) $data["verbal_trial.duration"])->locale('fr')->toLetters();
                $data["verbal_trial.periodicity.fr"] = ["mensual" => "Mensuel", "quarterly" => "Trimestrielle", "semi-annual" => "Semestrielle", "annual" => "Annuel", "in-fine" => "A la fin"][$data["verbal_trial.periodicity"]];
                $data["verbal_trial.amount"] = number_format(((float) $data["verbal_trial.amount"]), 0, ',', ' ');
                $data["verbal_trial.due_amount"] = number_format(((float) $data["verbal_trial.due_amount"]), 0, ',', ' ');
                unset($data["observations"]);
                unset($data["guarantors"]);
                $templateProcessor->setValues($data);

                // Enregistrez les modifications dans un nouveau fichier
                $outputFilePath = public_path("Billet-a-ordre-" . $notification->verbal_trial->committee_id . ".docx");
                $templateProcessor->saveAs($outputFilePath);

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
     * Créer un nouveau notification
     *
     * @bodyParam   verbal_trial_id                                         int                 L'ID du PV.                                                             Example: 1
     * @bodyParam   representative_phone_number                             string              Le numéro de téléphone du demandeur.                                    Example: +228 90 90 90 90
     * @bodyParam   representative_home_address                             string              L'addresse du domicile du demandeur.                                    Example: Zip 85
     * @bodyParam   number_of_due_dates                                     int                 Le nombre d'échéance du crédit.                                         Example: 3
     * @bodyParam   risk_premium_percentage                                 int                 La prime de risque (en pourcentage) du crédit du demandeur.             Example: 2
     * @bodyParam   total_amount_of_interest                                int                 La prime de risque (en pourcentage) du crédit du demandeur.             Example: 2
     * @bodyParam   representative_type_of_identity_document                int                 La prime de risque (en pourcentage) du crédit du demandeur.             Example: 2
     * @bodyParam   representative_number_of_identity_document              int                 La prime de risque (en pourcentage) du crédit du demandeur.             Example: 2
     * @bodyParam   representative_date_of_issue_of_identity_document       int                 La prime de risque (en pourcentage) du crédit du demandeur.             Example: 2
     * @bodyParam   type                                 int                 La prime de risque (en pourcentage) du crédit du demandeur.             Example: 2
     *
     * @response 200
     */
    public function store(Request $request)
    {
        if (($authorisation = Gate::inspect('create', Notification::class))->allowed()) {
            $requestData = $request->all();
            $validator = Validator::make($requestData, [
                'verbal_trial_id' => "required|exists:verbals_trials,id|unique:notifications",
                'representative_phone_number' => 'required|min:2',
                'representative_home_address' => 'required|min:2',
                'number_of_due_dates' => 'required|numeric',
                'risk_premium_percentage' => 'required|numeric',
                'total_amount_of_interest' => 'required|numeric',
                'representative_type_of_identity_document' => 'required|in:cni,passport,residence_certificate,driving_licence',
                'representative_number_of_identity_document' => 'required|min:2',
                'representative_date_of_issue_of_identity_document' => 'required|date',
                'type' => 'required|in:particular,company,individual_business',
            ]);
            if ($validator->fails()) {
                return $this->responseError($validator->errors(), 400);
            }

            if ($requestData["type"] != "particual") {
                $validator = Validator::make($requestData, [
                    'business_denomination' => "required|min:2",
                ]);
                if ($validator->fails()) {
                    return $this->responseError($validator->errors(), 400);
                }
            }
            $requestData["creator_id"] = $request->user()->id;
            $requestData["sent"] = false;

            $relationList = ["verbal_trial", "verbal_trial.type_of_credit.type_of_applicant", "verbal_trial.guarantees"];
            $notification = Notification::create($requestData);
            $notification->load($relationList);

            $receiver = $notification->verbal_trial->caf;
            $receiver->email = "charles.gamligo@cofinacorp.com";
            $receiver->full_name = "Head Crédit";
            $link = env("APP_URL") . "/notification";
//             SendEmail::dispatch(
//                 $receiver->email,
//                 "Notification de mise en place d'un pv",
//                 "
//             <h1 style='color: #333333;text-align: center; font-size: 24px; margin-bottom: 20px;'>Cher(e) $receiver->full_name,</U></h1>

//             <p style='color: #666666; font-size: 16px; line-height: 1.5;'>Nous vous prions de vous connecter à l'application cofina credit digital et de prendre en charge immédiatement la notification de validation: <a href='$link'>Consulter l</a></p>

//             <p style='color: #666666; font-size: 16px; line-height: 1.5;'>Si vous avez des questions ou des préoccupations, n'hésitez pas à nous contacter. Nous sommes là pour vous aider !</p>

//             <hr style='border: none; border-top: 1px solid #dddddd; margin: 20px 0;'>

//             <p style='color: #999999; font-size: 12px;'>Cet e-mail est généré automatiquement. Veuillez ne pas y répondre.</p>
// "
//             );
            return $this->responseOk([
                "notification" => $notification
            ], status: 201);
        } else {
            return $this->responseError(["auth" => [$authorisation->message()]], 403);
        }
    }

    /**
     * Mettre à jour une notification
     *
     * @urlParam    id                                                      int     required    L'ID de la notification.                                                        Example: 1
     *
     * @bodyParam   verbal_trial_id                                         int                 L'ID du PV.                                                             Example: 1
     * @bodyParam   representative_phone_number                             string              Le numéro de téléphone du demandeur.                                    Example: +228 90 90 90 90
     * @bodyParam   representative_home_address                             string              L'addresse du domicile du demandeur.                                    Example: Zip 85
     * @bodyParam   number_of_due_dates                                     int                 Le nombre d'échéance du crédit.                                         Example: 3
     * @bodyParam   risk_premium_percentage                                 int                 La prime de risque (en pourcentage) du crédit du demandeur.             Example: 2
     * @response 200
     *
     */
    public function update(Request $request, int $id)
    {
        $notification = Notification::find($id);
        if ($notification) {
            if (($authorisation = Gate::inspect('update', $notification))->allowed()) {
                $requestData = $request->all();
                $validator = Validator::make($requestData, [
                    'verbal_trial_id' => "required|exists:verbals_trials,id|unique:notifications,verbal_trial_id," . $id,
                    'representative_phone_number' => 'required|min:2',
                    'representative_home_address' => 'required|min:2',
                    'number_of_due_dates' => 'required|numeric',
                    'risk_premium_percentage' => 'required|numeric',
                    'total_amount_of_interest' => 'required|numeric',
                    'representative_type_of_identity_document' => 'required|in:cni,passport,residence_certificate,driving_licence',
                    'representative_number_of_identity_document' => 'required|min:2',
                    'representative_date_of_issue_of_identity_document' => 'required|date',
                    'type' => 'required|in:particular,company,individual_business',
                ]);
                if ($validator->fails()) {
                    return $this->responseError($validator->errors(), 400);
                }

                if ($requestData["type"] != "particual") {
                    $validator = Validator::make($requestData, [
                        'business_denomination' => "required|min:2",
                    ]);
                    if ($validator->fails()) {
                        return $this->responseError($validator->errors(), 400);
                    }
                }

                $relationList = ["verbal_trial", "verbal_trial.type_of_credit.type_of_applicant", "verbal_trial.guarantees"];
                if ($notification->head_credit_validation == "rejected") {
                    $requestData["head_credit_validation"] = "waiting";
                }
                $requestData["status"] = "waiting";
                $notification->update($requestData);
                $notification->load($relationList);
                return $this->responseOk([
                    "notification" => $notification
                ], status: 200);
            } else {
                return $this->responseError(["auth" => [$authorisation->message()]], 403);
            }
        } else {
            return $this->responseError(["id" => "La notification n'existe pas"], 404);
        }
    }


    /**
     * Mettre à jour le statut de validation head_credit d'une notification
     *
     * @urlParam    id      required                    int             L'ID d'une notification.                                Example: 1
     *
     * @bodyParam   head_credit_validation              string          Le nouveau statut                                       Example: rejected
     * @bodyParam   head_credit_observation             string          Commentaire du changement                               Example: Trop bas
     *
     * @response 200
     *
     */
    public function change_head_credit_status(Request $request, $id)
    {
        $notification = Notification::find($id);
        if ($notification) {
            if (($authorisation = Gate::inspect("change_head_credit_status", $notification))->allowed()) {
                $requestData = $request->all();
                $validator = Validator::make($requestData, [
                    'head_credit_validation' => 'required|in:rejected,validated',
                    'head_credit_observation' => "min:0",
                ]);
                if ($validator->fails()) {
                    return $this->responseError($validator->errors(), 400);
                } else {
                    $notification->update([
                        "head_credit_validation" => $requestData["head_credit_validation"],
                        "head_credit_observation" => $requestData["head_credit_observation"],
                    ]);
                }
            } else {
                return $this->responseError(["auth" => [$authorisation->message()]], 403);
            }
        } else {
            return $this->responseError(["id" => ["La notification n'existe pas"]], 404);
        }
    }

    /**
     * Envoyer les modifications à porter au dossier à validation
     *
     * @urlParam    id      required                    int             L'ID d'une notification.                                Example: 1
     *
     * @response 200
     *
     */
    public function send(Request $request, $id)
    {
        $notification = Notification::find($id);
        if ($notification) {
            if (($authorisation = Gate::inspect("send", $notification))->allowed()) {
                if ($notification->observations == []) {
                    $notification->update(["sent" => true, "status" => "waiting"]);
                } else {
                    return $this->responseError(["observations" => ["Le dossier de la notification est incomplet"]]);
                }
            } else {
                return $this->responseError(["auth" => [$authorisation->message()]], 403);
            }
        } else {
            return $this->responseError(["id" => ["La notification n'existe pas"]], 404);
        }
    }

    /**
     * Mettre à jour le statut d'un procès verbal
     *
     * @urlParam    id      required                    int             L'ID du procès verbal.                                  Example: 1
     *
     * @bodyParam   status                              string          Le nouveau statut                                       Example: rejected
     * @bodyParam   comment                             string          Commentaire du changement                               Example: Trop bas
     *
     * @response 200
     *
     */
    public function change_status(Request $request, $id)
    {
        $notification = Notification::find($id);
        if ($notification) {
            if (($authorisation = Gate::inspect("change_status", $notification))->allowed()) {
                $requestData = $request->all();
                $validator = Validator::make($requestData, [
                    'status' => 'required|in:rejected,validated',
                    'comment' => "min:0",
                ]);
                if ($validator->fails()) {
                    return $this->responseError($validator->errors(), 400);
                } else {
                    $data = [
                        "status" => $requestData["status"],
                        "status_observation" => $requestData["comment"],
                    ];
                    if ($requestData["status"] == "rejected") {
                        $data["sent"] = false;
                    }
                    $notification->update($data);
                    return $notification;
                }
            } else {
                return $this->responseError(["auth" => [$authorisation->message()]], 403);
            }
        } else {
            return $this->responseError(["id" => ["La notification n'existe pas"]], 404);
        }
    }

    /**
     * Sauvegarde la notification ou procès verbal signé
     *
     * @urlParam    id                                                      int     required    L'ID de la notification.                                                        Example: 1
     *
     * @response 204
     */

    public function upload(Request $request, int $id)
    {
        $notification = Notification::find($id);
        if ($notification) {
            if (($authorisation = Gate::inspect('upload', $notification))->allowed()) {
                DB::beginTransaction();
                if ($request->has('signed_notification')) {
                    $document_category = "notification";
                    $base64Document = $request->input('signed_notification');
                } else if ($request->has('signed_contract')) {
                    $document_category = "contract";
                    $base64Document = $request->input('signed_contract');
                } else if ($request->has('signed_promissory_note')) {
                    $document_category = "promissory_note";
                    $base64Document = $request->input('signed_promissory_note');
                } else {
                    DB::rollBack();
                    return $this->responseError(["error" => "Vous devez uploader une notification signée, un contrat signé ou un billet à ordre signé"], 400);
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
                $path = 'upload/Notifications/signed_' . $document_category . 's/' . $notification->verbal_trial->committee_id . '-signed.' . $extension;
                Storage::disk("public")->put($path, $documentData);
                $notification->update(["signed_{$document_category}_path" => "/storage/" . $path]);

                DB::commit();
                return $this->responseOk(["notification" => $notification]);
            } else {
                return $this->responseError(["auth" => [$authorisation->message()]], 403);
            }
        } else {
            return $this->responseError(["id" => "La notification n'existe pas"], 404);
        }
    }

    /**
     * Supprime une notification
     *
     * @urlParam    id                                                      int     required    L'ID de la notification.                                                        Example: 1
     *
     * @response 204
     */
    public function destroy(int $id)
    {
        $notification = Notification::find($id);
        if ($notification) {
            if (($authorisation = Gate::inspect('delete', $notification))->allowed()) {
                if ($notification->delete()) {
                    return $this->responseOk(messages: ["notification" => "La notification a été supprimé"], status: 204);
                } else {
                    return $this->responseError(["server" => "Erreur du serveur"], 500);
                }
            } else {
                return $this->responseError(["auth" => [$authorisation->message()]], 403);
            }
        } else {
            return $this->responseError(["id" => ["La notification n'existe pas"]], 404);
        }

    }
}
