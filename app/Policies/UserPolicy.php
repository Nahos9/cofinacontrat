<?php

namespace App\Policies;

use App\Http\Traits\PermissionCheckerTrait;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
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
        return $this->check(["read", "read_caf"], "user", $connectedUser) ? Response::allow() : Response::deny("Vous n'êtes pas autorisé à effectuer cette action");
    }

    public function view(User $connectedUser, User $user)
    {
        if ($connectedUser->id == $user->id)
            return Response::allow();
        return $this->check(["read"], "user", $connectedUser) ? Response::allow() : Response::deny("Vous n'êtes pas autorisé à effectuer cette action");
    }

    public function create(User $connectedUser)
    {
        return $this->check(["create"], "user", $connectedUser) ? Response::allow() : Response::deny("Vous n'êtes pas autorisé à effectuer cette action");
    }

    public function update(User $connectedUser, User $user)
    {
        return $this->check(["update"], "user", $connectedUser) ? Response::allow() : Response::deny("Vous n'êtes pas autorisé à effectuer cette action");
    }
    public function update_password(User $connectedUser, User $user)
    {
        if ($connectedUser->id == $user->id)
            return Response::allow();
        return Response::deny("Vous n'êtes pas autorisé à effectuer cette action");
    }
    public function update_signatory(User $connectedUser, User $user)
    {
        return $this->update_password($connectedUser, $user);
    }
    public function delete(User $connectedUser, User $user)
    {
        return $this->check(["delete"], "user", $connectedUser) ? Response::allow() : Response::deny("Vous n'êtes pas autorisé à effectuer cette action");
    }
}
