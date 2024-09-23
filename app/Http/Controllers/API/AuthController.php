<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

/**
 * @group Authentification
 *
 * Endpoints pour gérer l_authentification
 */
class AuthController extends Controller
{



    /**
     * Connecte un utilisateur
     *
     * @bodyParam email     string  required L'email de l'utilsateur.                   Example: admin@cofinacorp.com
     * @bodyParam password  string  required Le mot de passe complet de l'utilisateur.  Example: Coftg2021
     *
     * @response 200
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users',
            "password" => 'required'
        ]);
        if ($validator->fails()) {
            return $this->responseError($validator->errors(), 400);
        } else {
            $user = User::on('mysql')->where('email', $request->email)->first();
            // dd($user);
            if (Hash::check($request->password, $user->password)) {
                if ($user->activated) {
                    return $this->responseOk([
                        "userToken" => $user->createToken($request->email)->plainTextToken,
                        "user" => $user
                    ]);
                } else {
                    return $this->responseError(["activated" => ["Votre compte est désactivé"]], 403);
                }
            } else {
                return $this->responseError(["password" => ["Mot de passe incorrect"]], 400);
            }
        }
    }

    /**
     * Affiche l'utilisateur connecté
     *
     * @response 200
     */
    public function show(Request $request)
    {
        return $this->responseOk($request->user());
    }

    /**
     * Déconnecte l'utilisateur connecté
     *
     * @response 204
     */
    public function logout(Request $request)
    {
        if ($request->user()->currentuserToken()->delete()) {
            return $this->responseOk(["messages" => ["Deconnexion complète"]]);
        } else {
            return $this->responseError(["errors" => ["Erreur durant la deconnexion"]]);
        }
    }

}
