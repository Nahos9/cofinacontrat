<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\attestation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use PhpOffice\PhpWord\TemplateProcessor;
use Illuminate\Support\Facades\Response;
use Rmunate\Utilities\SpellNumber;

class AttestationController extends Controller
{
    public function index()
    {
        $attestations = attestation::where('is_deleted', false)->orderBy('created_at', 'desc')->get();
        return response()->json($attestations);
    }
    public function show($id)
    {
        $attestation = attestation::find($id);
        return response()->json($attestation);
    }
    public function store(Request $request)
    {
        $attestation = attestation::create($request->all());
        if($request->has('gages')){
            foreach($request->gages as $gage){
                $attestation->gages()->create([
                    'immatriculation' => $gage['immatriculation'],
                    'marque' => $gage['marque'],
                ]);
            }
        }
        return response()->json($attestation);
    }
    public function update(Request $request, $id)
    {
        $attestation = attestation::find($id);
        $attestation->update($request->all());
        return response()->json($attestation);
    }
    public function destroy($id)
    {
        $attestation = attestation::find($id);
        $attestation->is_deleted = true;
        $attestation->save();
        return response()->json($attestation);
    }

    public function download(Request $request, $id)
    {
        $zip = new \ZipArchive();
		$zipFileName = 'attestations.zip';
		$zipFilePath = public_path($zipFileName);
		if ($zip->open($zipFilePath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== TRUE) {
			return $this->responseError(["error" => "Impossible de créer le fichier ZIP"], 500);
		}
        $attestation = attestation::find($id);
        // dd($attestation);
        if ($attestation) {
            $templatePath = base_path("document_templates/Attestations/Personne_physique/attestation_de_cloture.docx");
            $templatePath2 = base_path("document_templates/Attestations/Personne_physique/attestation_endettement.docx");
            $templatePath3 = base_path("document_templates/Attestations/Personne_physique/attestation_non_endettement.docx");
            $templatePath4 = base_path("document_templates/Attestations/Personne_physique/attestation_main_levee_de_gage.docx");
            if (!file_exists($templatePath)) {
                return response()->json(['error' => 'Le template est introuvable.'], 404);
            }
            $today = Carbon::now()->format('d/m/Y');

            if($attestation->type == "personne physique"){
                if($attestation->type_attestation == "cloture"){
                    $templateProcessor = new TemplateProcessor($templatePath);
                    $templateProcessor->setValue('last_name', $attestation->last_name);
                    $templateProcessor->setValue('first_name', $attestation->first_name);
                    $templateProcessor->setValue('civilite', $attestation->civilite);
                    if($attestation->civilite == "Monsieur"){
                        $templateProcessor->setValue('genre', "client");
                    }else{
                        $templateProcessor->setValue('genre', "cliente");
                    }
                    $templateProcessor->setValue('account_number', $attestation->account_number);
                    $templateProcessor->setValue('date_de_creation_compte', $formattedDate = Carbon::parse($attestation->date_de_creation_compte)->format('d/m/Y'));
                    $templateProcessor->setValue('type', $attestation->type);
                    $templateProcessor->setValue('type_attestation', $attestation->type_attestation);
                    $templateProcessor->setValue('raison_sociale', $attestation->raison_sociale);
                    $templateProcessor->setValue('date_du_jour', $today);
                    $outputFilePath = public_path("Attestation-cloture-" . $attestation->last_name . "-" . $attestation->first_name . ".docx");
                    $templateProcessor->saveAs($outputFilePath);
                   return response()->download($outputFilePath)->deleteFileAfterSend(true);
                }
                if($attestation->type_attestation == "endettement"){
                    $montant_endettement = 0;
                    $montant_endettement = SpellNumber::value((int) $attestation->montant_endettement)->locale('fr')->toLetters();
                  
                    $templateProcessor = new TemplateProcessor($templatePath2);
                    $templateProcessor->setValue('last_name', $attestation->last_name);
                    $templateProcessor->setValue('first_name', $attestation->first_name);
                    $templateProcessor->setValue('civilite', $attestation->civilite);
                    if($attestation->civilite == "Monsieur"){
                        $templateProcessor->setValue('genre', "client");
                    }else{
                        $templateProcessor->setValue('genre', "cliente");
                    }
                    $templateProcessor->setValue('account_number', $attestation->account_number);
                    $templateProcessor->setValue('date_de_creation_compte', $formattedDate = Carbon::parse($attestation->date_de_creation_compte)->format('d/m/Y'));
                    $templateProcessor->setValue('type', $attestation->type);
                    $templateProcessor->setValue('type_attestation', $attestation->type_attestation);
                    $templateProcessor->setValue('raison_sociale', $attestation->raison_sociale);
                    $templateProcessor->setValue('montant_endettement_fr', $montant_endettement);
                    $templateProcessor->setValue('montant_endettement', $attestation->montant_endettement);
                    $templateProcessor->setValue('date_du_jour', $today);
                    $outputFilePath = public_path("Attestation-endettement-" . $attestation->last_name . "-" . $attestation->first_name . ".docx");
                    $templateProcessor->saveAs($outputFilePath);
                    return response()->download($outputFilePath)->deleteFileAfterSend(true);
                }
                if($attestation->type_attestation == "non endettement"){
                    $templateProcessor = new TemplateProcessor($templatePath3);
                    $templateProcessor->setValue('last_name', $attestation->last_name);
                    $templateProcessor->setValue('first_name', $attestation->first_name);
                    $templateProcessor->setValue('civilite', $attestation->civilite);
                    if($attestation->civilite == "Monsieur"){
                        $templateProcessor->setValue('genre', "client");
                    }else{
                        $templateProcessor->setValue('genre', "cliente");
                    }
                    $templateProcessor->setValue('account_number', $attestation->account_number);
                    $templateProcessor->setValue('date_de_creation_compte', $formattedDate = Carbon::parse($attestation->date_de_creation_compte)->format('d/m/Y'));
                    $templateProcessor->setValue('type', $attestation->type);
                    $templateProcessor->setValue('type_attestation', $attestation->type_attestation);
                    $templateProcessor->setValue('raison_sociale', $attestation->raison_sociale);
                    $templateProcessor->setValue('date_du_jour', $today);
                    $outputFilePath = public_path("Attestation-non-endettement-" . $attestation->last_name . "-" . $attestation->first_name . ".docx");
                    $templateProcessor->saveAs($outputFilePath);
                    return response()->download($outputFilePath)->deleteFileAfterSend(true);

                }
                if($attestation->type_attestation == "main levée de gage"){
                    $templateProcessor = new TemplateProcessor($templatePath4);
                }
            }
            $templatePath = base_path("document_templates/Attestations/Personne_morale/attestation_de_cloture.docx");
            $templatePath2 = base_path("document_templates/Attestations/Personne_morale/attestation_endettement.docx");
            $templatePath3 = base_path("document_templates/Attestations/Personne_morale/attestation_non_endettement.docx");
            $templatePath4 = base_path("document_templates/Attestations/Personne_morale/attestation_main_levee_de_gage.docx");

            if($attestation->type == "personne morale"){
                // dd("ok");
                if($attestation->type_attestation == "cloture"){
                    $templateProcessor = new TemplateProcessor($templatePath);
                    $templateProcessor->setValue('raison_sociale', $attestation->raison_sociale);
                    $templateProcessor->setValue('adresse', $attestation->address);
                    $templateProcessor->setValue('date_de_creation_compte', $formattedDate = Carbon::parse($attestation->date_de_creation_compte)->format('d/m/Y'));
                    $templateProcessor->setValue('type', $attestation->type);
                    $templateProcessor->setValue('account_number', $attestation->account_number);
                    $templateProcessor->setValue('type_attestation', $attestation->type_attestation);
                    $templateProcessor->setValue('date_du_jour', $today);
                    $outputFilePath = public_path("Attestation-cloture-" . $attestation->last_name . "-" . $attestation->first_name . ".docx");
                    $templateProcessor->saveAs($outputFilePath);
                    return response()->download($outputFilePath)->deleteFileAfterSend(true);

                }
                if($attestation->type_attestation == "endettement"){
                    $templateProcessor = new TemplateProcessor($templatePath2);
                    $montant_endettement = 0;
                    $montant_endettement = SpellNumber::value((int) $attestation->montant_endettement)->locale('fr')->toLetters();
                    $templateProcessor->setValue('raison_sociale', $attestation->raison_sociale);
                    $templateProcessor->setValue('adresse', $attestation->address);
                    $templateProcessor->setValue('date_de_creation_compte', $formattedDate = Carbon::parse($attestation->date_de_creation_compte)->format('d/m/Y'));
                    $templateProcessor->setValue('type', $attestation->type);
                    $templateProcessor->setValue('account_number', $attestation->account_number);
                    $templateProcessor->setValue('type_attestation', $attestation->type_attestation);
                    $templateProcessor->setValue('montant_endettement_fr', $montant_endettement);
                    $templateProcessor->setValue('montant_endettement', $attestation->montant_endettement);
                    $templateProcessor->setValue('date_du_jour', $today);
                    $outputFilePath = public_path("Attestation-endettement-" . $attestation->last_name . "-" . $attestation->first_name . ".docx");
                    $templateProcessor->saveAs($outputFilePath);
                    return response()->download($outputFilePath)->deleteFileAfterSend(true);
                }
                if($attestation->type_attestation == "non endettement"){
                    $templateProcessor = new TemplateProcessor($templatePath3);
                    $templateProcessor->setValue('raison_sociale', $attestation->raison_sociale);
                    
                    $templateProcessor->setValue('adresse', $attestation->address);
                    $templateProcessor->setValue('date_de_creation_compte', $formattedDate = Carbon::parse($attestation->date_de_creation_compte)->format('d/m/Y'));
                    $templateProcessor->setValue('type', $attestation->type);
                    $templateProcessor->setValue('account_number', $attestation->account_number);
                    $templateProcessor->setValue('type_attestation', $attestation->type_attestation);
                    $templateProcessor->setValue('date_du_jour', $today);
                    $outputFilePath = public_path("Attestation-non-endettement-" . $attestation->last_name . "-" . $attestation->first_name . ".docx");
                    $templateProcessor->saveAs($outputFilePath);
                    return response()->download($outputFilePath)->deleteFileAfterSend(true);
                }
                if($attestation->type_attestation == "main levée de gage"){
                    $vehicules = [
                        [
                            "immatriculation" => "LQ-123-AA",
                            "marque" => "Toyota",
                        ],
                        [
                            "immatriculation" => "DA-454-AA",
                            "marque" => "Hyundai",
                        ]
                    ];
                    
                
                    $templateProcessor = new TemplateProcessor($templatePath4);
                    $templateProcessor->cloneRow('marque', count($vehicules));
                    foreach ($vehicules as $index => $vehicule) {
                        $rowIndex = $index + 1; // cloneRow commence à 1
                        $templateProcessor->setValue("marque#{$rowIndex}", $vehicule['marque']);
                        $templateProcessor->setValue("immatriculation#{$rowIndex}", $vehicule['immatriculation']);
                    }
                   
                    $templateProcessor->setValue('raison_sociale', $attestation->raison_sociale);
                    $templateProcessor->setValue('adresse', $attestation->address);
                    $templateProcessor->setValue('date_de_creation_compte', $formattedDate = Carbon::parse($attestation->date_de_creation_compte)->format('d/m/Y'));
                    $templateProcessor->setValue('type', $attestation->type);
                    $templateProcessor->setValue('account_number', $attestation->account_number);
                    $templateProcessor->setValue('type_attestation', $attestation->type_attestation);
                    $templateProcessor->setValue('date_du_jour', $today);
                    $outputFilePath = public_path("Attestation-main-levee-de-gage-" . $attestation->last_name . "-" . $attestation->first_name . ".docx");
                    $templateProcessor->saveAs($outputFilePath);
                    return response()->download($outputFilePath)->deleteFileAfterSend(true);
                }
            }
        }
       
    }
}
