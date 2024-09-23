<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('{any?}', function () {
    return view('application');
})->where('any', '.*');

// Route::get('/', function () {
//     try {
//         $results = DB::connection('oracle')->select("SELECT * FROM cofina.client ");
//         dd("Connexion réussie !", $results);
//     } catch (\Exception $e) {
//         dd("Erreur de connexion : " . $e->getMessage());
//     }
// });
// Route::get('/comptes', function () {
//     try {
//         $results = DB::connection('oracle')->select("SELECT * FROM cofina.compte ");
//         dd("Connexion réussie !", $results);
//     } catch (\Exception $e) {
//         dd("Erreur de connexion : " . $e->getMessage());
//     }
// });
