<?php

namespace App\Policies;

use App\Http\Traits\PermissionCheckerTrait;
use App\Models\TypeOfCredit;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TypeOfCreditPolicy
{
    use PermissionCheckerTrait;
    public function before(User $connectedUser, string $ability)
    {
        if ($connectedUser->profile == "admin" || ($connectedUser->ability_rules[0]["subject"] == "all" && $connectedUser->ability_rules[0]["action"] == "manage")) {
            return Response::allow();
        }
        return null;
    }

    public function viewAny(User $connectedUser)
    {
        return $this->check(["read"], "type-of-credit", $connectedUser) ? Response::allow() : Response::deny("Vous n'êtes pas autorisé à effectuer cette action");
    }

    public function view(User $connectedUser, TypeOfCredit $typeOfCredit)
    {
        return $this->check(["read"], "type-of-credit", $connectedUser) ? Response::allow() : Response::deny("Vous n'êtes pas autorisé à effectuer cette action");
    }

    public function create(User $connectedUser)
    {
        return $this->check(["create"], "type-of-credit", $connectedUser) ? Response::allow() : Response::deny("Vous n'êtes pas autorisé à effectuer cette action");
    }

    public function update(User $connectedUser, TypeOfCredit $typeOfCredit)
    {
        return $this->check(["update"], "type-of-credit", $connectedUser) ? Response::allow() : Response::deny("Vous n'êtes pas autorisé à effectuer cette action");
    }

    public function delete(User $connectedUser, TypeOfCredit $typeOfCredit)
    {
        return $this->check(["delete"], "type-of-credit", $connectedUser) ? Response::allow() : Response::deny("Vous n'êtes pas autorisé à effectuer cette action");
    }
}
