<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Traits\CustomResponseTrait;
use App\Jobs\SendEmail;
use App\Models\Guarantee;
use App\Models\Pep;
use App\Models\User;
use App\Models\VerbalTrial;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpWord\TemplateProcessor;
use Rmunate\Utilities\SpellNumber;

/**
 * @group Procès Verbal
 *
 * EndPoints pour gérer les Procès verbaux
 */
class VerbalTrialController extends Controller
{

  use CustomResponseTrait;

  /**
   * Affiche les Procès verbaux
   *
   * @queryParam  committee_id                                            string              Filtrer par ID du procès verbal                                         No-example
   * @queryParam  committee_date                                          string              Filtrer par date du procès verbal                                       No-example
   * @queryParam  civility                                                string              Filtrer par civilité                                                    No-example
   * @queryParam  applicant_first_name                                    string              Filtrer par prénom du demandeur                                         No-example
   * @queryParam  applicant_last_name                                     string              Filtrer par nom du demandeur                                            No-example
   * @queryParam  account_number                                          string              Filtrer par numéro de compte                                            No-example
   * @queryParam  activity                                                string              Filtrer par activé                                                      No-example
   * @queryParam  purpose_of_financing                                    string              Filtrer par objet du financement                                        No-example
   * @queryParam  type_of_credit_id                                       int                 Filtrer par ID du type de credit                                        No-example
   * @queryParam  amount                                                  float               Filtrer par montant                                                     No-example
   * @queryParam  duration                                                int                 Filtrer par durée en mois                                               No-example
   * @queryParam  periodicity                                             string              Filtrer par periodicité                                                 No-example
   * @queryParam  taf                                                     float               Filtrer par TAF                                                         No-example
   * @queryParam  due_amount                                              float               Filtrer par montant d'une échéance                                      No-example
   * @queryParam  administrative_fees_percentage                          float               Filtrer par frais de dossier(pourcentage)                               No-example
   * @queryParam  insurance_premium                                       float               Filtrer par prime d'assurance                                           No-example
   * @queryParam  tax_fee_interest_rate                                   float               Filter par taux d'intérêt hors taxe(%)                                  No-example
   * @queryParam  caf_id                                                  int                 Filtrer par ID du CAF                                                   No-example
   * @queryParam  creator_id                                              int                 Filtrer par ID du créateur                                              No-example
   * @queryParam  has_contract                                            int                 Filtrer par présence de contrat                                         Example: 0
   * @queryParam  has_notification                                        int                 Filtrer par présence de notification                                    Example: 0
   * @queryParam  has_mortgage                                            int                 Filtrer par présence d'hypothèque                                       Example: 0
   * @queryParam  status                                                  string              Filtrer par statut du pv                                                Example: waiting
   *
   * @queryParam  with_type_of_credit                                     int                 Afficher le type de crédit.                                             Example: 0
   * @queryParam  with_type_of_applicant                                  int                 Afficher le type de demandeur du type de crédit.                        Example: 1
   * @queryParam  with_guarantees                                         int                 Afficher les garanties.                                                 Example: 1
   * @queryParam  with_type_of_guarantees                                 int                 Afficher les types des garanties.                                       Example: 1
   * @queryParam  with_contract                                           int                 Afficher le contrat.                                                    Example: 1
   * @queryParam  with_caf                                                int                 Afficher le CAF.                                                        Example: 1
   * @queryParam  with_creator                                            int                 Afficher le créateur du pv.                                             Example: 0
   * @queryParam  paginate                                                int                 Utiliser la pagination.                                 Example: 0
   *
   * @response 200
   */
  public function index(Request $request)
  {
    if (($authorisation = Gate::inspect('viewAny', VerbalTrial::class))->allowed()) {
      $verbalTrialList = VerbalTrial::query();
      $currentUser = $request->user();
      if ($search = $request->search) {
        $verbalTrialList
          ->where(function ($query) use ($search) {
            $query
              ->orWhere('committee_id', 'LIKE', "%$search%")
              ->orWhere('committee_date', 'LIKE', "%$search%")
              ->orWhere('civility', 'LIKE', "%$search%")
              ->orWhere('applicant_first_name', 'LIKE', "%$search%")
              ->orWhere('applicant_last_name', 'LIKE', "%$search%")
              ->orWhere('account_number', 'LIKE', "%$search%")
              ->orWhere('activity', 'LIKE', "%$search%")
              ->orWhere('purpose_of_financing', 'LIKE', "%$search%")
              ->orWhere('type_of_credit_id', 'LIKE', "%$search%")
              ->orWhere('amount', 'LIKE', "%$search%")
              ->orWhere('duration', 'LIKE', "%$search%")
              ->orWhere('periodicity', 'LIKE', "%$search%")
              ->orWhere('taf', 'LIKE', "%$search%")
              ->orWhere('due_amount', 'LIKE', "%$search%")
              ->orWhere('administrative_fees_percentage', 'LIKE', "%$search%")
              ->orWhere('insurance_premium', 'LIKE', "%$search%")
              ->orWhere(DB::raw("CONCAT(applicant_first_name, ' ', applicant_last_name)"), 'LIKE', "%$search%");
          });
        ;
      }

      foreach (["committee_id", "committee_date", "civility", "applicant_first_name", "applicant_last_name", "account_number", "activity", "purpose_of_financing", "type_of_credit_id", "amount", "duration", "periodicity", "taf", "due_amount", "administrative_fees_percentage", "insurance_premium", "caf_id", "creator_id"] as $filter) {
        if (isset($request[$filter]) && $request[$filter] != "") {
          $verbalTrialList->where($filter, $request[$filter]);
        }
      }

      if (isset($request["status"])) {
        $verbalTrialList->where(function ($query) use ($request) {
          foreach (str_split($request["status"]) as $char) {
            if (in_array($char, ['w', 'v', 'r', 'c'])) {
              $query->orWhere("status", ["w" => "waiting", "v" => "validated", "r" => "rejected"][$char]);
            }
          }
        });
      }

      if (isset($request["has_contract"])) {
        $has_contract = (int) $request["has_contract"];
        if ($has_contract == 1) {
          $verbalTrialList->whereHas('contract');
        } else if ($has_contract == 0) {
          $verbalTrialList->whereDoesntHave('contract');
        }
      }

      if (isset($request["has_notification"])) {
        $has_notification = (int) $request["has_notification"];
        if ($has_notification == 1) {
          $verbalTrialList->whereHas('notification');
        } else if ($has_notification == 0) {
          $verbalTrialList->whereDoesntHave('notification');
        }
      }

      if (isset($request["has_mortgage"])) {
        $has_mortgage = (int) $request["has_mortgage"];
        if ($has_mortgage == 1) {
          $verbalTrialList->whereHas('guarantees', function ($query) {
            $query->where('type_of_guarantee_id', 9);
          });
        } else if ($has_mortgage == 0) {
          $verbalTrialList->whereDoesntHave('guarantees', function ($query) {
            $query->where('type_of_guarantee_id', 9);
          });
        }
      }

      foreach (["with_type_of_credit" => "type_of_credit", "with_type_of_applicant" => "type_of_credit.type_of_applicant", "with_guarantees" => "guarantees", "with_type_of_guarantees" => "guarantees.type_of_guarantee", "with_contract" => "contract", "with_caf" => "caf", "with_creator" => "creator"] as $key => $value) {
        if (isset($request[$key]) && $request[$key]) {
          $verbalTrialList->with($value);
        }
      }

      if ($currentUser->profile == "credit_admin") {
        $verbalTrialList->where('credit_admin_id', $currentUser->id);
      }

      if (isset($request["paginate"]) && ($request->paginate == false)) {
        $verbalTrialList = $verbalTrialList->orderByDesc('updated_at')->get();
        $data = ["data" => $verbalTrialList, "total" => count($verbalTrialList)];
      } else {
        $data = $verbalTrialList->orderByDesc('updated_at')->paginate(8)->toArray();
      }

      return $this->responseOkPaginate($data);
    } else {
      return $this->responseError(["auth" => [$authorisation->message()]], 403);
    }
  }

  /**
   * Affiche un procès verbal
   *
   * @urlParam    id                                                      int required    L'ID du procès verbal.                                          Example: 1
   *
   * @queryParam  with_type_of_credit                                     int             Afficher le type de crédit.                                     Example: 0
   * @queryParam  with_type_of_applicant                                  int             Afficher le type de demandeur du type de crédit.                Example: 1
   * @queryParam  with_guarantees                                         int             Afficher les garanties.                                         Example: 1
   * @queryParam  with_type_of_guarantees                                 int             Afficher les types des garanties.                               Example: 1
   * @queryParam  with_contract                                           int             Afficher le contrat.                                            Example: 1
   * @queryParam  with_caf                                                int             Afficher le CAF.                                                Example: 1
   *
   * @response 200
   */
  public function show(Request $request, int $id)
  {
    $verbalTrial = VerbalTrial::find($id);
    if ($verbalTrial) {
      if (($authorisation = Gate::inspect('view', $verbalTrial))->allowed()) {
        $suplementList = [];
        foreach (["with_type_of_credit" => "type_of_credit", "with_type_of_applicant" => "type_of_credit.type_of_applicant", "with_guarantees" => "guarantees", "with_type_of_guarantees" => "guarantees.type_of_guarantee", "with_contract" => "contract", "with_caf" => "caf", "with_creator" => "creator"] as $key => $value) {
          if (isset($request[$key]) && $request[$key]) {
            $suplementList[] = $value;
          }
        }
        $verbalTrial->load($suplementList);
        return $this->responseOk(["verbalTrial" => $verbalTrial]);
      } else {
        return $this->responseError(["auth" => [$authorisation->message()]], 403);
      }
    } else {
      return $this->responseError(["id" => "Le procès verbal n'existe pas"], 404);
    }
  }

  /**
   * Télécharge la version word d'un PV
   *
   * @urlParam    id                                                      int     required    L'ID du PV.                                                         Example: 1
   *
   * @response 200
   */
  public function download(Request $request, int $id)
  {
    $verbalTrial = VerbalTrial::find($id);
    if ($verbalTrial) {
      // if (($authorisation = Gate::inspect('view', $verbalTrial))->allowed()) {
      // $templateProcessor = new TemplateProcessor("../document_templates/PVs/PV-$verbalTrial->status-$verbalTrial->validation_level.docx");
      $templateProcessor = new TemplateProcessor("../document_templates/PVs/PV.docx");
      $data = $verbalTrial->toArray();
      $data = array_merge($data, collect($verbalTrial->caf)->mapWithKeys(function ($value, $key) {
        return ['caf.' . $key => $value];
      })->all());
      $data = array_merge($data, collect($verbalTrial->type_of_credit)->mapWithKeys(function ($value, $key) {
        return ['type_of_credit.' . $key => $value];
      })->all());

      $data["administrative_fees_percentage.value"] = number_format((float) $data["administrative_fees_percentage"] * $data["amount"] / 100, 0, ',', ' ');
      $data["created_at"] = Carbon::parse($verbalTrial->created_at)->format("d/m/Y");
      // $data["amount"] = number_format(((float) $data["amount"]), 0, ',', ' ');
      $data["due_amount"] = number_format(((float) $data["due_amount"]), 0, ',', ' ');
      $data["amount"] = (float) $data["amount"];
      $data["insurance_premium"] = number_format(((float) $data["insurance_premium"]), 0, ',', ' ');
      $data["periodicity.fr"] = ["mensual" => "Mensuel", "quarterly" => "Trimestrielle", "semi-annual" => "Semestrielle", "annual" => "Annuel", "in-fine" => "A la fin"][$data["periodicity"]];
      $data["line_review_bonus"] = (((float) $data["duration"]) < 18) ? "" : "Prime de révision de ligne                                          : « 1% du capital restant dû après 12 mois »";
			$data["amount.fr"] = SpellNumber::value((float) $data["amount"])->locale('fr')->toLetters();


      $guaranteeList = [];
      foreach ($verbalTrial->guarantees as $guarantee) {
        $tmp = $guarantee->toArray();
        $guaranteeList[] = array_merge($tmp, collect($guarantee->type_of_guarantee)->mapWithKeys(function ($value, $key) {
          return ['type_of_guarantee.' . $key => $value];
        })->all());
      }
      foreach (["head_credit", "md"] as $signatoryProfile) {
        $currentSignatory = User::where('profile', $signatoryProfile)->first();
        if ($currentSignatory) {
          ($currentSignatory->signatory_path) ? $templateProcessor->setImageValue($signatoryProfile . "_sign", array("path" => "storage" . $currentSignatory->signatory_path, 'width' => 240, 'height' => 240, 'ratio' => true)) : $templateProcessor->setValue($signatoryProfile . "_sign", "");
        }
      }
      $templateProcessor->cloneBlock('guaranteeList', 0, true, false, $guaranteeList);
      unset($data["caf.ability_rules"]);
      $templateProcessor->setValues($data);
      // return $data;
      // dd($data);
      // Enregistrez les modifications dans un nouveau fichier
      $wordFilePath = public_path("PV-" . $verbalTrial->committee_id . ".docx");
      $templateProcessor->saveAs($wordFilePath);
      // $pdfDirectoryPath = public_path("/");
      // $pdfFileName = public_path("PV-" . $verbalTrial->committee_id . ".pdf");
      // $pdfFilePath = public_path("PV-" . $verbalTrial->committee_id . ".pdf");

      // $command = "soffice --headless --convert-to pdf $wordFilePath";

      // exec($command, $output);

      // foreach ($output as $line) {
      //     echo $line . "<br>";
      // }

      // // $this->wordToPdf($wordFilePath, $pdfFilePath);
      // // exec("dbus-send --type=method_call --dest=org.gnome.ScreenSaver /org/gnome/ScreenSaver org.gnome.ScreenSaver.Lock");
      // // $output = shell_exec('dbus-send --type=method_call --dest=org.gnome.ScreenSaver /org/gnome/ScreenSaver org.gnome.ScreenSaver.Lock');
      // // exec("soffice --headless --convert-to pdf --outdir $pdfDirectoryPath $wordFilePath");


      // \PhpOffice\PhpWord\Settings::setPdfRendererPath(base_path('vendor/dompdf/dompdf'));
      // \PhpOffice\PhpWord\Settings::setPdfRendererName('DomPDF');
      // $Content = \PhpOffice\PhpWord\IOFactory::load($wordFilePath);
      // $PDFWriter = \PhpOffice\PhpWord\IOFactory::createWriter($Content, 'PDF');
      // $PDFWriter->save($pdfFilePath);

      // if (file_exists($wordFilePath)) {
      //     unlink($wordFilePath);
      // }

      // return response()->download($pdfFilePath)->deleteFileAfterSend();
      return Response::file($wordFilePath, ["Content-Type" => "application/vnd.openxmlformats-officedocument.wordprocessingml.document"])->deleteFileAfterSend(true);
    } else {
      return $this->responseError(["id" => "Le contrat n'existe pas"], 404);
    }
  }

  /**
   * Créer un nouveau procès verbal
   *
   * @bodyParam   committee_id                        string          L'ID comitée venant de créditFlow                       Example: CFNTG-044-13-12-23-01212
   * @bodyParam   committee_date                      string          La date du comitée                                      Example: 2024-02-09
   * @bodyParam   civility                            string          La Civilité                                             Example: Mr
   * @bodyParam   applicant_first_name                string          Le prénom du demandeur                                  Example: Cesar
   * @bodyParam   applicant_last_name                 string          Le nom du demandeur                                     Example: Endure
   * @bodyParam   account_number                      string          Le numéro de compte                                     Example: 012345678901
   * @bodyParam   activity                            string          L'activé                                                Example: Homme d'affaire
   * @bodyParam   purpose_of_financing                string          L'objet du financement                                  Example: Achat nouveau locaux
   * @bodyParam   type_of_credit_id                   int             L'ID du type de credit                                  Example: 1
   * @bodyParam   amount                              float           Le montant                                              Example: 10000000
   * @bodyParam   duration                            int             La durée en mois                                        Example: 12
   * @bodyParam   periodicity                         string          La periodicité                                          Example: mensual
   * @bodyParam   taf                                 float           La TAF(%)                                               Example: 10
   * @bodyParam   due_amount                          float           Le montant d'une échéance                               Example: 500000
   * @bodyParam   administrative_fees_percentage      float           Les frais de dossier(%)                                 Example: 2.5
   * @bodyParam   insurance_premium                   float           La prime d'assurance                                    Example: 45000
   * @bodyParam   tax_fee_interest_rate               float           Le taux d'intérêt hors taxe(%)                          Example: 10
   * @bodyParam   caf_id                              int             L'ID du CAF                                             Example: 4
   *
   * @response 200
   */
  public function store(Request $request)
  {
    if (($authorisation = Gate::inspect('create', VerbalTrial::class))->allowed()) {
      $requestData = $request->all();
      $validator = Validator::make($requestData, [
        "committee_id" => "required|unique:verbals_trials",
        "committee_date" => "required|date",
        'civility' => 'required|in:Mr,Mme,Mlle',
        'applicant_first_name' => 'required|min:2',
        'applicant_last_name' => 'required|min:2',
        'account_number' => 'required|min:12',
        'activity' => 'required|min:2',
        'purpose_of_financing' => 'required|min:2',
        'type_of_credit_id' => 'required|exists:types_of_credit,id',
        'amount' => 'required|numeric',
        'duration' => 'required|numeric',
        'periodicity' => 'required|in:mensual,quarterly,semi-annual,annual,in-fine',
        // 'taf' => 'required|numeric',
        'due_amount' => 'required|numeric',
        'administrative_fees_percentage' => 'required|numeric',
        'insurance_premium' => 'required|numeric',
        'tax_fee_interest_rate' => 'required|numeric',
        'caf_id' => 'required|exists:users,id',
        'credit_admin_id' => 'required|exists:users,id',
        "guarantees" => "array",
        "guarantees.*.type_of_guarantee_id" => "required|exists:types_of_guarantee,id",
        "guarantees.*.comment" => "required|min:2",
      ]);
      if ($validator->fails()) {
        return $this->responseError($validator->errors(), 400);
      } else {
        if (User::where("profile", "credit_admin")->where('id', $requestData["credit_admin_id"])->exists()) {
          if (User::where("profile", "caf")->where('id', $requestData["caf_id"])->exists()) {
            DB::beginTransaction();
            try {
              $requestData["creator_id"] = $request->user()->id;
              $requestData["validation_level"] = "credit_admin";
              $verbalTrial = VerbalTrial::create($requestData);
              if (isset($requestData["guarantees"])) {
                $guaranteesCollection = new Collection($requestData["guarantees"]);
                if (
                  $guaranteesCollection->contains(function ($objet) {
                    return $objet["type_of_guarantee_id"] === 9;
                  })
                ) {
                  $verbalTrial->update(["has_mortgage" => true]);
                }
                foreach ($requestData["guarantees"] as $guarantee) {
                  Guarantee::create([
                    "verbal_trial_id" => $verbalTrial->id,
                    "type_of_guarantee_id" => $guarantee["type_of_guarantee_id"],
                    "comment" => $guarantee["comment"]
                  ]);

                  if(isset($guarantee["type_of_guarantee_id"]) && $guarantee["type_of_guarantee_id"] == "7"){
                    Pep::create([
                      "verbal_trial_id" => $verbalTrial->id,
                      "type_of_guarantee_id" => $guarantee["type_of_guarantee_id"],
                      "montant" => $guarantee["montant"],
                      "duree" => $guarantee["duree"],
                      "taux_annuel" => $guarantee["taux_annuel"],
                      "date_debut" => $guarantee["date_debut"],
                    ]);
                  }
                }
              }
              $receiver = User::find($requestData["caf_id"]);
              $link = env("APP_URL") . "/contract/add";
              // foreach (User::where('profile', 'credit_admin')->get() as $receiver) {
              // 	SendEmail::dispatch(
              // 		$receiver->email,
              // 		"Notification de mise en place d'un contrat",
              // 		"
              //         <h1 style='color: #333333;font-size: 24px; margin-bottom: 20px;'>Cher(e) Admin crédit,</U></h1>

              //         <p style='color: #666666; font-size: 16px; line-height: 1.5;'>Nous vous prions de vous connecter à l'application cofina credit digital et de prendre en charge immédiatement le PV $verbalTrial->committee_id en attente de contrat: <a href='$link'>Créer le contrat</a></p>

              //         <p style='color: #666666; font-size: 16px; line-height: 1.5;'>Si vous avez des questions ou des préoccupations, n'hésitez pas à nous contacter. Nous sommes là pour vous aider !</p>

              //         <hr style='border: none; border-top: 1px solid #dddddd; margin: 20px 0;'>

              //         <p style='color: #999999; font-size: 12px;'>Cet e-mail est généré automatiquement. Veuillez ne pas y répondre.</p>
              //     "
              // 	);
              // }
            } catch (\Exception $e) {
              DB::rollback();
              throw $e;
            }
            DB::commit(); // Valider les opérations

            $verbalTrial->load(["type_of_credit.type_of_applicant", "guarantees", "caf"]);
            return $this->responseOk([
              "verbalTrial" => $verbalTrial
            ], status: 201);
          } else {
            return $this->responseError(["caf_id" => ["Le CAF n'existe pas"]], 404);
          }
        } else {
          return $this->responseError(["credit_admin_id" => ["L'admin crédit n'existe pas"]], 404);
        }
      }
    } else {
      return $this->responseError(["auth" => [$authorisation->message()]], 403);
    }
  }

  /**
   * Mettre à jour un procès verbal
   *
   * @urlParam id required L'ID du procès verbal. Example: 1
   *
   * @bodyParam   committee_id                        string          L'ID comitée venant de créditFlow                       Example: CFNTG-044-13-12-23-01212
   * @bodyParam   committee_date                      string          La date du comitée                                      Example: 2024-02-09
   * @bodyParam   civility                            string          La Civilité                                             Example: Mr
   * @bodyParam   applicant_first_name                string          Le prénom du demandeur                                  Example: Cesar
   * @bodyParam   applicant_last_name                 string          Le nom du demandeur                                     Example: Endure
   * @bodyParam   account_number                      string          Le numéro de compte                                     Example: 012345678901
   * @bodyParam   activity                            string          L'activé                                                Example: Homme d'affaire
   * @bodyParam   purpose_of_financing                string          L'objet du financement                                  Example: Achat nouveau locaux
   * @bodyParam   type_of_credit_id                   int             L'ID du type de credit                                  Example: 1
   * @bodyParam   amount                              float           Le montant                                              Example: 10000000
   * @bodyParam   duration                            int             La durée en mois                                        Example: 12
   * @bodyParam   periodicity                         string          La periodicité                                          Example: mensual
   * @bodyParam   taf                                 float           La TAF(%)                                               Example: 10
   * @bodyParam   due_amount                          float           Le montant d'une échéance                               Example: 500000
   * @bodyParam   administrative_fees_percentage      float           Les frais de dossier(%)                                 Example: 2.5
   * @bodyParam   insurance_premium                   float           La prime d'assurance                                    Example: 45000
   * @bodyParam   tax_fee_interest_rate               float           Le taux d'intérêt hors taxe(%)                          Example: 10
   * @bodyParam   caf_id                              int             L'ID du CAF                                             Example: 4
   *
   * @response 200
   *
   */
  public function update(Request $request, int $id)
  {
    $verbalTrial = VerbalTrial::find($id);
    if ($verbalTrial) {
      if (($authorisation = Gate::inspect('update', $verbalTrial))->allowed()) {
        $requestData = $request->all();
        $validator = Validator::make($requestData, [
          "committee_id" => "required|unique:verbals_trials,committee_id," . $id,
          "committee_date" => "required|date",
          'civility' => 'required|in:Mr,Mme,Mlle',
          'applicant_first_name' => 'required|min:2',
          'applicant_last_name' => 'required|min:2',
          'account_number' => 'required|min:10',
          'activity' => 'required|min:2',
          'purpose_of_financing' => 'required|min:2',
          'type_of_credit_id' => 'required|exists:types_of_credit,id',
          'amount' => 'required|numeric',
          'duration' => 'required|numeric',
          'periodicity' => 'required|in:mensual,quarterly,semi-annual,annual,in-fine',
          'taf' => 'required|numeric',
          'due_amount' => 'required|numeric',
          'administrative_fees_percentage' => 'required|numeric',
          'insurance_premium' => 'required|numeric',
          'tax_fee_interest_rate' => 'required|numeric',
          'caf_id' => 'required|exists:users,id',
          'credit_admin_id' => 'required|exists:users,id',
          "guarantees" => "array",
          "guarantees.*.type_of_guarantee_id" => "required|exists:types_of_guarantee,id",
          "guarantees.*.comment" => "required|min:2",
        ]);
        if ($validator->fails()) {
          return $this->responseError($validator->errors(), 400);
        } else {
          if (User::where("profile", "credit_admin")->where('id', $requestData["credit_admin_id"])->exists()) {
            if (User::where("profile", "caf")->where('id', $requestData["caf_id"])->exists()) {
              DB::beginTransaction();
              try {
                $requestData["creator_id"] = $request->user()->id;
                $requestData["validation_level"] = "credit_admin";
                $verbalTrial->guarantees()->delete();
                if (isset($requestData["guarantees"])) {
                  $guaranteesCollection = new Collection($requestData["guarantees"]);
                  $requestData["has_mortgage"] = $guaranteesCollection->contains(function ($objet) {
                    return $objet["type_of_guarantee_id"] === 9;
                  });
                  foreach ($requestData["guarantees"] as $guarantee) {
                    Guarantee::create([
                      "verbal_trial_id" => $verbalTrial->id,
                      "type_of_guarantee_id" => $guarantee["type_of_guarantee_id"],
                      "comment" => $guarantee["comment"]
                    ]);
                  }
                }
                $requestData["status"] = "waiting";
                $verbalTrial->update($requestData);
              } catch (\Exception $e) {
                DB::rollback();
                throw $e;
              }
              DB::commit(); // Valider les opérations
              $verbalTrial->load(["type_of_credit.type_of_applicant", "guarantees", "contract", "caf"]);
              return $this->responseOk([
                "verbalTrial" => $verbalTrial
              ]);
            } else {
              return $this->responseError(["caf_id" => ["Le CAF n'existe pas"]], 404);
            }
          } else {
            return $this->responseError(["credit_admin_id" => ["L'admin crédit n'existe pas"]], 404);
          }
        }
      } else {
        return $this->responseError(["auth" => [$authorisation->message()]], 403);
      }
    } else {
      return $this->responseError(["id" => "Le procès verbal n'existe pas"], 404);
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
    $verbalTrial = VerbalTrial::find($id);
    if ($verbalTrial) {
      if (($authorisation = Gate::inspect("change_status", $verbalTrial))->allowed()) {
        $requestData = $request->all();
        $validator = Validator::make($requestData, [
          'status' => 'required|in:rejected,validated',
          'comment' => "min:0",
        ]);
        if ($validator->fails()) {
          return $this->responseError($validator->errors(), 400);
        } else {
          $requestData = $validator->validated();
          $connectedUser = User::find($request->user()->id);
          if ($requestData["status"] == "validated") {
            $requestData["validation_level"] = [
              "credit_admin" => "head_credit",
              "head_credit" => "md",
              "md" => "md",
            ][$connectedUser->profile];
            $requestData["status"] = ($connectedUser->profile == "md") ? "validated" : "waiting";
          }
          $verbalTrial->update($requestData);
          return $verbalTrial;
        }
      } else {
        return $this->responseError(["auth" => [$authorisation->message()]], 403);
      }
    } else {
      return $this->responseError(["id" => ["Le CAT n'existe pas"]], 404);
    }
  }

  /**
   * Supprime un procès verbal
   *
   * @urlParam id int required L'ID du procès verbal
   *
   * @response 204
   */
  public function destroy(int $id)
  {
    $verbalTrial = VerbalTrial::find($id);
    if ($verbalTrial) {
      if (($authorisation = Gate::inspect('delete', $verbalTrial))->allowed()) {
        if ($verbalTrial->delete()) {
          return $this->responseOk(messages: ["verbalTrial" => "Procès verbal supprimé"], status: 204);
        } else {
          return $this->responseError(["server" => "Erreur du serveur"], 500);
        }
      } else {
        return $this->responseError(["auth" => [$authorisation->message()]], 403);
      }
    } else {
      return $this->responseError(["id" => ["Le procès verbal n'existe pas"]], 404);
    }

  }
}
