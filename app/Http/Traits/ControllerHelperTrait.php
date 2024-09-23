<?php

namespace App\Http\Traits;

use Exception;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\RelationNotFoundException;
use Illuminate\Support\Facades\Storage;

trait ControllerHelperTrait
{

    /**
     * Permet d'ajouter des filtres sur un objet Eloquent
     * @param 	mixed 	$query				L'objet Eloquent
     * @param 	mixed 	$requestData		Les données de la requete
     * @param 	mixed 	$modelName			Le nom du model
     * @return 	mixed
     */
    public function queryFilter($query, $requestData, $modelName)
    {
        $modelPath = "\App\Models\\$modelName";
        foreach ($requestData as $filter => $value) {
            if (in_array($filter, Schema::getColumnListing((new $modelPath)->getTable())) && $requestData[$filter]) {
                $query->where($filter, $requestData[$filter]);
            }
        }
        return $query;
    }

    /**
     * Permet d'ajouter des relation sur un objet Eloquent
     * @param 	mixed 	$query				L'objet Eloquent
     * @param 	mixed 	$requestData		Les données de la requete
     * @param 	mixed 	$modelName			Le nom du model
     * @return 	mixed
     */
    public function queryRelation($query, $requestData, $modelName)
    {
        $modelPath = "App\Models\\$modelName";
        $currentObjet = new $modelPath();
        $relations = collect($requestData)->filter(function ($value, $key) {
            return strpos($key, 'with_') === 0;
        });
        $validatedRelations = [];
        foreach ($relations as $relation => $value) {
            $relation = str_replace("<", ".", substr($relation, 5));
            try {
                $currentObjet->load($relation);
                ($value == "true") ? $validatedRelations[] = $relation : null;
            } catch (RelationNotFoundException $ex) {
                continue;
            }
        }
        $query->with($validatedRelations);
        return $query;
    }

    /**
     * Permet d'ajouter un filtre de recherche sur un objet Eloquent
     * @param 	mixed 	$query				L'objet Eloquent
     * @param 	mixed 	$columns			Les colones où rechercher
     * @param 	mixed 	$search				Le mot clé à rechercher
     * @return 	mixed
     */
    public function querySearch($query, $columns, $search)
    {
        $query
            ->where(function ($query) use ($columns, $search) {
                foreach ($columns as $column) {
                    $query->where($column, 'LIKE', "%$search%");
                }
            });
        return $query;
    }

    /**
     * Permet d'ajouter des relation sur un model laravel via chargement load
     * @param 	mixed 	$query				L'objet Eloquent
     * @param 	mixed 	$requestData		Les valeurs à utiliser pour appliquer les relations
     * @param 	mixed 	$modelName			Le nom du model
     * @return 	mixed
     */
    public function modelRelationLoad($model, $requestData, $modelName)
    {
        $modelPath = "App\Models\\$modelName";
        $currentObjet = new $modelPath();
        $relations = collect($requestData)->filter(function ($value, $key) {
            return strpos($key, 'with_') === 0;
        });
        $validatedRelations = [];
        foreach ($relations as $relation => $value) {
            $relation = str_replace("<", ".", substr($relation, 5));
            try {
                $currentObjet->load($relation);
                ($value == "true") ? $validatedRelations[] = $relation : null;
            } catch (RelationNotFoundException $ex) {
                continue;
            }
        }

        $model->load($validatedRelations);
        return $model;
    }

    /**
     * Vérifie si on a à faire à un base64 valide
     * @param 	mixed	$base64				Le base64
     * @param 	mixed	$validateType		Les types de base64 valides
     * @return	boolean
     */
    public function checkIsBase64Validated($base64, $validatedTypes = ["jpg", "png", "jpeg", "gif"])
    {
        $validators = [
            "jpg" => fn($base64) => strpos($base64, 'data:image/jpg;base64') === 0,
            "jpeg" => fn($base64) => strpos($base64, 'data:image/jpeg;base64') === 0,
            "png" => fn($base64) => strpos($base64, 'data:image/png;base64') === 0,
            "gif" => fn($base64) => strpos($base64, 'data:image/gif;base64') === 0,
        ];
        foreach ($validatedTypes as $validatedType) {
            if ($validators[$validatedType]($base64)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Enregistre une image
     * @param 	string	$base64				Le base64
     * @param 	string	$savePath			Le chemin d'enregistrement
     * @param 	array	$validatedTypes		Les types du fichier validés
     *
     */
    public function saveImageFromBase64($base64, $savePath, $validatedTypes = ["jpg", "png", "jpeg", "gif"])
    {
        try {
            foreach ($validatedTypes as $extention) {
                $imageData = str_replace("data:image/$extention;base64,", '', $base64);
            }
            $imageData = str_replace(' ', '+', $imageData);
            $imageData = base64_decode($imageData);
            Storage::disk("public")->put($savePath, $imageData);
            return $savePath;
        } catch (Exception $ex) {
            return false;
        }
    }



    /**
     * Permet d'ajouter des filtres avec des valeurs multiples sur un objet Eloquent
     * @param 	mixed 	$query				L'objet Eloquent
     * @param 	mixed 	$filters			Les filtres
     * @param 	mixed 	$requestData		Les données de la requete
     * @param 	mixed 	$correlationData	Les valeurs à utiliser pour appliquer les filtres
     * @return 	mixed
     */
    public function queryMultipeValvueFilter($query, $associationFilters, $requestData, $correlationData)
    {
        foreach ($associationFilters as $filter => $chain) {
            if (isset($requestData[$filter]) && $requestData[$filter]) {
                $query->where(function ($query) use ($filter, $requestData, $correlationData) {
                    foreach (explode("-", $requestData[$filter]) as $char) {
                        if (array_key_exists($char, $correlationData)) {
                            $query->where($filter, $correlationData[$char]);
                        }
                    }
                });
            }
        }
        return $query;
    }
}
