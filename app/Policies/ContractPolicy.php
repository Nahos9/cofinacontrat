<?php

namespace App\Policies;

use App\Http\Traits\PermissionCheckerTrait;
use App\Models\Contract;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ContractPolicy
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
        return $this->check(["read", "historical"], "contract", $connectedUser) ? Response::allow() : Response::deny("Vous n'êtes pas autorisé à effectuer cette action");
    }

    public function view(User $connectedUser, Contract $contract)
    {
        return $this->check(["read", "historical"], "contract", $connectedUser) ? Response::allow() : Response::deny("Vous n'êtes pas autorisé à effectuer cette action");
    }

    public function create(User $connectedUser)
    {
        return $this->check(["create"], "contract", $connectedUser) ? Response::allow() : Response::deny("Vous n'êtes pas autorisé à effectuer cette action");
    }

    public function update(User $connectedUser, Contract $contract)
    {
        return $this->check(["update"], "contract", $connectedUser) ? ($contract->status == "validated") ? Response::deny("vous n'etes plus autorisé à modifier ce contrat") : Response::allow() : Response::deny("Vous n'êtes pas autorisé à effectuer cette action");
    }
    public function download(User $connectedUser, Contract $contract)
    {
        return $this->check(["download"], "contract", $connectedUser) ? Response::allow() : Response::deny("Vous n'êtes pas autorisé à effectuer cette action");
    }
    public function upload(User $connectedUser, Contract $contract)
    {
        return $this->check(["upload"], "contract", $connectedUser) ? Response::allow() : Response::deny("Vous n'êtes pas autorisé à effectuer cette action");
    }

    public function change_status(User $connectedUser, Contract $contract)
    {
        return $this->check(["change_status"], "contract", $connectedUser) ? Response::allow() : Response::deny("Vous n'êtes pas autorisé à effectuer cette action");
    }

    public function delete(User $connectedUser, Contract $contract)
    {
        return $this->check(["delete"], "contract", $connectedUser) ? ($contract->status == "validated") ? Response::deny("vous n'etes plus autorisé à supprimer ce contrat") : Response::allow() : Response::deny("Vous n'êtes pas autorisé à effectuer cette action");
    }
}
