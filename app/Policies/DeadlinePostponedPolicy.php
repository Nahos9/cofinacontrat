<?php

namespace App\Policies;

use App\Http\Traits\PermissionCheckerTrait;
use App\Models\DeadlinePostponed;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class DeadlinePostponedPolicy
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
		return $this->check(["read", "historical"], "deadline-postponed", $connectedUser) ? Response::allow() : Response::deny("Vous n'êtes pas autorisé à effectuer cette action");
	}

	public function view(User $connectedUser, DeadlinePostponed $deadlinePostponed)
	{
		return $this->check(["read", "historical"], "deadline-postponed", $connectedUser) ? Response::allow() : Response::deny("Vous n'êtes pas autorisé à effectuer cette action");
	}

	public function create(User $connectedUser)
	{
		return $this->check(["create"], "deadline-postponed", $connectedUser) ? Response::allow() : Response::deny("Vous n'êtes pas autorisé à effectuer cette action");
	}
	public function update(User $connectedUser, DeadlinePostponed $deadlinePostponed)
	{
		if ($connectedUser->id == $deadlinePostponed->caf_id && $this->check(["update"], "deadline-postponed", $connectedUser) && in_array($deadlinePostponed->status, ["waiting_ca", "rejected_by_ca", "rejected_by_dex", "rejected_by_head", "rejected_by_md", "rejected_by_credit_admin"])) {
			return Response::allow();
		}
		return Response::deny("vous n'êtes pas autorisé à modifier ce report d'échéance");
	}
	public function change_status(User $connectedUser, DeadlinePostponed $deadlinePostponed)
	{
		return $this->check(["change_status"], "deadline-postponed", $connectedUser) ? Response::allow() : Response::deny("Vous n'êtes pas autorisé à effectuer cette action");
	}
	public function download(User $connectedUser, DeadlinePostponed $deadlinePostponed)
	{
		return $this->check(["download"], "deadline-postponed", $connectedUser) ? Response::allow() : Response::deny("Vous n'êtes pas autorisé à effectuer cette action");
	}
	public function delete(User $connectedUser, DeadlinePostponed $deadlinePostponed)
	{
		if ($connectedUser->id == $deadlinePostponed->caf_id && $this->check(["delete"], "deadline-postponed", $connectedUser) && in_array($deadlinePostponed->status, ["waiting_ca", "rejected_by_ca", "rejected_by_dex", "rejected_by_head", "rejected_by_md", "rejected_by_credit_admin"])) {
			return Response::allow();
		}
		return Response::deny("vous n'êtes pas autorisé à modifier ce report d'échéance");
	}
}
