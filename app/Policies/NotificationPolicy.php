<?php

namespace App\Policies;

use App\Http\Traits\PermissionCheckerTrait;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class NotificationPolicy
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
		return $this->check(["read", "historical", "without-signed-contract"], "notification", $connectedUser) ? Response::allow() : Response::deny("Vous n'êtes pas autorisé à effectuer cette action");
	}

	public function view(User $connectedUser, Notification $notification)
	{
		return $this->check(["read", "historical"], "notification", $connectedUser) ? Response::allow() : Response::deny("Vous n'êtes pas autorisé à effectuer cette action");
	}

	public function create(User $connectedUser)
	{
		return $this->check(["create"], "notification", $connectedUser) ? Response::allow() : Response::deny("Vous n'êtes pas autorisé à effectuer cette action");
	}

	public function update(User $connectedUser, Notification $notification)
	{
		return $this->check(["update"], "notification", $connectedUser) ? ($notification->head_credit_validation == "validated") ? Response::deny("vous n'etes plus autorisé à modifier ce contrat") : Response::allow() : Response::deny("Vous n'êtes pas autorisé à effectuer cette action");
	}
	public function download(User $connectedUser, Notification $notification)
	{
		return $this->check(["download"], "notification", $connectedUser) ? Response::allow() : Response::deny("Vous n'êtes pas autorisé à effectuer cette action");
	}
	public function upload(User $connectedUser, Notification $notification)
	{
		return $this->check(["upload"], "notification", $connectedUser) ? Response::allow() : Response::deny("Vous n'êtes pas autorisé à effectuer cette action");
	}
	public function change_head_credit_status(User $connectedUser, Notification $notification)
	{
		return $this->check(["change_head_credit_status"], "notification", $connectedUser) ? Response::allow() : Response::deny("Vous n'êtes pas autorisé à effectuer cette action");
	}
	public function send(User $connectedUser, Notification $notification)
	{
		return $this->check(["send"], "notification", $connectedUser) ? Response::allow() : Response::deny("Vous n'êtes pas autorisé à effectuer cette action");
	}
	public function change_status(User $connectedUser, Notification $notification)
	{
		return $this->check(["change_status"], "notification", $connectedUser) ? Response::allow() : Response::deny("Vous n'êtes pas autorisé à effectuer cette action");
	}
	public function delete(User $connectedUser, Notification $notification)
	{
		return $this->check(["delete"], "notification", $connectedUser) ? Response::allow() : Response::deny("Vous n'êtes pas autorisé à effectuer cette action");
	}
}
