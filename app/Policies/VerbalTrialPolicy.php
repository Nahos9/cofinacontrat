<?php

namespace App\Policies;

use App\Http\Traits\PermissionCheckerTrait;
use App\Models\User;
use App\Models\VerbalTrial;
use Illuminate\Auth\Access\Response;

class VerbalTrialPolicy
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
		return $this->check(["read", "historical"], "pv", $connectedUser) ? Response::allow() : Response::deny("Vous n'êtes pas autorisé à effectuer cette action");
	}

	public function view(User $connectedUser, VerbalTrial $verbalTrial)
	{
		return $this->check(["read", "historical"], "pv", $connectedUser) ? Response::allow() : Response::deny("Vous n'êtes pas autorisé à effectuer cette action");
	}

	public function create(User $connectedUser)
	{
		return $this->check(["create"], "pv", $connectedUser) ? Response::allow() : Response::deny("Vous n'êtes pas autorisé à effectuer cette action");
	}

	public function update(User $connectedUser, VerbalTrial $verbalTrial)
	{
		return $this->check(["update"], "pv", $connectedUser) ? (($verbalTrial->status == "validated") ? Response::deny("vous n'etes plus autorisé à modifier ce pv") : Response::allow()) : Response::deny("Vous n'êtes pas autorisé à effectuer cette action");
	}
	public function change_status(User $connectedUser, VerbalTrial $verbalTrial)
	{
		return $this->check(["change_status"], "pv", $connectedUser) ? Response::allow() : Response::deny("Vous n'êtes pas autorisé à effectuer cette action");
	}
	public function download(User $connectedUser, VerbalTrial $verbalTrial)
	{
		return $this->check(["download"], "pv", $connectedUser) ? Response::allow() : Response::deny("Vous n'êtes pas autorisé à effectuer cette action");
	}

	public function delete(User $connectedUser, VerbalTrial $verbalTrial)
	{
		return $this->check(["delete"], "pv", $connectedUser) ? (($verbalTrial->status == "validated") ? Response::deny("vous n'etes plus autorisé à supprimer ce pv") : Response::allow()) : Response::deny("Vous n'êtes pas autorisé à effectuer cette action");
	}
}
