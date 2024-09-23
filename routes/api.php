<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CATController;
use App\Http\Controllers\API\DeadlinePostponedController;
use App\Http\Controllers\API\GuarantorController;
use App\Http\Controllers\API\NotificationController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\ContractController;
use App\Http\Controllers\API\TypeOfApplicantController;
use App\Http\Controllers\API\TypeOfCreditController;
use App\Http\Controllers\API\TypeOfGuaranteeController;
use App\Http\Controllers\API\VerbalTrialController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('auth/login', [AuthController::class, "login"])->name("auth.login");
Route::get("/test/contract/download/{id}", [ContractController::class, "download"])->name("free.contract.word");
// Route::get("/test/contract/promissory-note/{id}", [ContractController::class, "promissory_note"])->name("free.contract.promissory-note");
// Route::get("/test/guarantor/word/{id}", [GuarantorController::class, "download"])->name("free.guarantor.word");
// Route::get("/test/guarantor/promissory-note/{id}", [GuarantorController::class, "promissory_note"])->name("free.guarantor.promissory-note");

Route::middleware('auth:sanctum')->group(function () {
	Route::prefix("/auth")->name("auth.")->group(function () {
		Route::get('show', [AuthController::class, "show"])->name("show");
		Route::delete('logout', [AuthController::class, "logout"])->name("logout");
	});
	Route::prefix("user")->name("user.")->group(function () {
		Route::get("/", [UserController::class, "index"])->name("index");
		Route::get("/{id}", [UserController::class, "show"])->name("show");
		Route::post("/", [UserController::class, "store"])->name("store");
		Route::put("/update-password", [UserController::class, "update_password"])->name("update-password");
		Route::put("/update-signatory/{id}", [UserController::class, "update_signatory"])->name("update-signatory");
		Route::put("/{id}", [UserController::class, "update"])->name("update");
		Route::delete("/{id}", [UserController::class, "destroy"])->name("destroy");
	});
	Route::prefix("type-of-applicant")->name("type-of-applicant.")->group(function () {
		Route::get("/", [TypeOfApplicantController::class, "index"])->name("index");
		Route::get("/{id}", [TypeOfApplicantController::class, "show"])->name("show");
		Route::post("/", [TypeOfApplicantController::class, "store"])->name("store");
		Route::put("/{id}", [TypeOfApplicantController::class, "update"])->name("update");
		Route::delete("/{id}", [TypeOfApplicantController::class, "destroy"])->name("destroy");
	});
	Route::prefix("type-of-credit")->name("type-of-credit.")->group(function () {
		Route::get("/", [TypeOfCreditController::class, "index"])->name("index");
		Route::get("/{id}", [TypeOfCreditController::class, "show"])->name("show");
		Route::post("/", [TypeOfCreditController::class, "store"])->name("store");
		Route::put("/{id}", [TypeOfCreditController::class, "update"])->name("update");
		Route::delete("/{id}", [TypeOfCreditController::class, "destroy"])->name("destroy");
	});
	Route::prefix("type-of-guarantee")->name("type-of-guarantee.")->group(function () {
		Route::get("/", [TypeOfGuaranteeController::class, "index"])->name("index");
		Route::get("/{id}", [TypeOfGuaranteeController::class, "show"])->name("show");
		Route::post("/", [TypeOfGuaranteeController::class, "store"])->name("store");
		Route::put("/{id}", [TypeOfGuaranteeController::class, "update"])->name("update");
		Route::delete("/{id}", [TypeOfGuaranteeController::class, "destroy"])->name("destroy");
	});
	Route::prefix("verbal-trial")->name("verbal-trial.")->group(function () {
		Route::get("/", [VerbalTrialController::class, "index"])->name("index");
		Route::get("/download/{id}", [VerbalTrialController::class, "download"])->name("download");
		Route::get("/{id}", [VerbalTrialController::class, "show"])->name("show");
		Route::post("/", [VerbalTrialController::class, "store"])->name("store");
		Route::put("/{id}", [VerbalTrialController::class, "update"])->name("update");
		Route::put("/change-status/{id}", [VerbalTrialController::class, "change_status"])->name("change_status");
		Route::delete("/{id}", [VerbalTrialController::class, "destroy"])->name("destroy");
	});
	Route::prefix("contract")->name("contract.")->group(function () {
		Route::get("/", [ContractController::class, "index"])->name("index");
		Route::get("/download/{id}", [ContractController::class, "download"])->name("download");
		// Route::get("/downloadAll/{id}", [ContractController::class, "downloadAll"])->name("downloadAll");
		Route::post("/upload/{id}", [ContractController::class, "upload"])->name("upload");
		Route::get("/promissory-note/download/{id}", [ContractController::class, "promissory_note"])->name("promissory-note.download");
		Route::get("/{id}", [ContractController::class, "show"])->name("show");
		Route::post("/", [ContractController::class, "store"])->name("store");
		Route::put("/{id}", [ContractController::class, "update"])->name("update");
		Route::put("/change-status/{id}", [ContractController::class, "change_status"])->name("change_status");
		Route::delete("/{id}", [ContractController::class, "destroy"])->name("destroy");
	});
	Route::prefix("guarantor")->name("guarantor.")->group(function () {
		Route::get("/", [GuarantorController::class, "index"])->name("index");
		Route::get("/download/{id}", [GuarantorController::class, "download"])->name("download");
		Route::post("/upload/{id}", [GuarantorController::class, "upload"])->name("upload");
		Route::get("/promissory-note/download/{id}", [GuarantorController::class, "promissory_note"])->name("promissory-note.download");
		Route::get("/{id}", [GuarantorController::class, "show"])->name("show");
		Route::post("/", [GuarantorController::class, "store"])->name("store");
		Route::put("/{id}", [GuarantorController::class, "update"])->name("update");
		Route::delete("/{id}", [GuarantorController::class, "destroy"])->name("destroy");
	});
	Route::prefix("notification")->name("notification.")->group(function () {
		Route::get("/", [NotificationController::class, "index"])->name("index");
		Route::get("/download/{id}", [NotificationController::class, "download"])->name("download");
		Route::get("/promissory-note/download/{id}", [NotificationController::class, "promissory_note"])->name("promissory-note.download");
		Route::get("/{id}", [NotificationController::class, "show"])->name("show");
		Route::post("/", [NotificationController::class, "store"])->name("store");
		Route::post("/upload/{id}", [NotificationController::class, "upload"])->name("upload");
		Route::put("/{id}", [NotificationController::class, "update"])->name("update");
		Route::put("/send/{id}", [NotificationController::class, "send"])->name("send");
		Route::put("/change-head-credit-status/{id}", [NotificationController::class, "change_head_credit_status"])->name("change_head_credit_status");
		Route::put("/change-status/{id}", [NotificationController::class, "change_status"])->name("change_status");
		Route::delete("/{id}", [NotificationController::class, "destroy"])->name("destroy");
	});
	Route::prefix("cat")->name("cat.")->group(function () {
		Route::get("/", [CATController::class, "index"])->name("index");
		Route::get("/download/{id}", [CATController::class, "download"])->name("download");
		Route::get("/{id}", [CATController::class, "show"])->name("show");
		Route::post("/", [CATController::class, "store"])->name("store");
		Route::put("/{id}", [CATController::class, "update"])->name("update");
		Route::put("validate/{id}", [CATController::class, "validate_cat"])->name("validate");
		Route::put("unblock/{id}", [CATController::class, "unblock"])->name("unblock");
		Route::put("reject-validation/{id}", [CATController::class, "reject_validation"])->name("reject_validation");
		Route::put("reject-unblock/{id}", [CATController::class, "reject_unblock"])->name("reject_unblock");
		Route::delete("/{id}", [CATController::class, "destroy"])->name("destroy");
	});
	Route::prefix("deadline-postponed")->name("deadline-postponed.")->group(function () {
		Route::get("/", [DeadlinePostponedController::class, "index"])->name("index");
		Route::get("/{id}", [DeadlinePostponedController::class, "show"])->name("show");
		Route::post("/", [DeadlinePostponedController::class, "store"])->name("store");
		Route::put("/{id}", [DeadlinePostponedController::class, "update"])->name("update");
		Route::delete("/{id}", [DeadlinePostponedController::class, "destroy"])->name("destroy");
	});
});

Route::get("/download/{id}", [NotificationController::class, "download"])->name("download");

Route::get('/clients',[UserController::class,'clients'])->name("clients");
Route::get('/clients/search', [UserController::class, 'searchClients'])->name("searchClients");
Route::get('/clients/comptes', [UserController::class, 'comptes'])->name("comptes");
Route::get('/clients/prets', [UserController::class, 'prets'])->name("prets");

