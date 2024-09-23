<?php

namespace App\Policies;

use App\Http\Traits\PermissionCheckerTrait;
use App\Models\CAT;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CATPolicy
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
		return $this->check(["read"], "cat", $connectedUser) ? Response::allow() : Response::deny("Vous n'êtes pas autorisé à effectuer cette action");
	}

	public function view(User $connectedUser, Cat $cat)
	{
		return $this->check(["read"], "cat", $connectedUser) ? Response::allow() : Response::deny("Vous n'êtes pas autorisé à effectuer cette action");
	}

	public function create(User $connectedUser)
	{
		return $this->check(["create"], "cat", $connectedUser) ? Response::allow() : Response::deny("Vous n'êtes pas autorisé à effectuer cette action");
	}

	public function update(User $connectedUser, Cat $cat)
	{
		return $this->check(["update"], "cat", $connectedUser) ? ($cat->validation_status == "validated") ? Response::deny("vous n'etes plus autorisé à modifier ce cat") : Response::allow() : Response::deny("Vous n'êtes pas autorisé à effectuer cette action");
	}
	public function download(User $connectedUser, Cat $cat)
	{
		return $this->check(["download"], "cat", $connectedUser) ? Response::allow() : Response::deny("Vous n'êtes pas autorisé à effectuer cette action");
	}
	public function validate(User $connectedUser, Cat $cat)
	{
		return $this->check(["validate"], "cat", $connectedUser) ? Response::allow() : Response::deny("Vous n'êtes pas autorisé à effectuer cette action");
	}
	public function unblock(User $connectedUser, Cat $cat)
	{
		return $this->check(["unblock"], "cat", $connectedUser) ? Response::allow() : Response::deny("Vous n'êtes pas autorisé à effectuer cette action");
	}
	public function reject_validation(User $connectedUser, Cat $cat)
	{
		return $this->check(["reject_validation"], "cat", $connectedUser) ? Response::allow() : Response::deny("Vous n'êtes pas autorisé à effectuer cette action");
	}
	public function reject_unblock(User $connectedUser, Cat $cat)
	{
		return $this->check(["reject_unblock"], "cat", $connectedUser) ? Response::allow() : Response::deny("Vous n'êtes pas autorisé à effectuer cette action");
	}
	public function delete(User $connectedUser, Cat $cat)
	{
		return $this->check(["delete"], "cat", $connectedUser) ? Response::allow() : Response::deny("Vous n'êtes pas autorisé à effectuer cette action");
	}
}
