<?php

namespace App\Policies;

use App\Http\Traits\PermissionCheckerTrait;
use App\Models\TypeOfApplicant;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TypeOfApplicantPolicy
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
        return $this->check(["read"], "type-of-applicant", $connectedUser) ? Response::allow() : Response::deny("Vous n'êtes pas autorisé à effectuer cette action");
    }

    public function view(User $connectedUser, TypeOfApplicant $typeOfApplicant)
    {
        return $this->check(["read"], "type-of-applicant", $connectedUser) ? Response::allow() : Response::deny("Vous n'êtes pas autorisé à effectuer cette action");
    }

    public function create(User $connectedUser)
    {
        return $this->check(["create"], "type-of-applicant", $connectedUser) ? Response::allow() : Response::deny("Vous n'êtes pas autorisé à effectuer cette action");
    }

    public function update(User $connectedUser, TypeOfApplicant $typeOfApplicant)
    {
        return $this->check(["update"], "type-of-applicant", $connectedUser) ? Response::allow() : Response::deny("Vous n'êtes pas autorisé à effectuer cette action");
    }

    public function delete(User $connectedUser, TypeOfApplicant $typeOfApplicant)
    {
        return $this->check(["delete"], "type-of-applicant", $connectedUser) ? Response::allow() : Response::deny("Vous n'êtes pas autorisé à effectuer cette action");
    }
}
