<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CAT extends Model
{
    use HasFactory;

    protected $table = "c_a_t_s";
    protected $fillable = [
        "contract_id",
        "notification_id",
        "credit_number",
        "sector",
        "code_sector",
        "first_deadline",
        "last_deadline",
        "source_of_reimbursement",
        "instructions_from_the_risk_and_credit_department",
        "outstanding_number_ready_to_settle",
        "other_expenses",
        "teg",
        "validator_user_id",
        "validation_status",
        "validation_comment",
        "unblocker_user_id",
        "unblock_status",
        "unblock_comment",
    ];

    protected $appends = ["status", "comment"];

    public function toArray()
    {
        $data = parent::toArray();
        $data["created_at_fr"] = Carbon::parse($data["created_at"])->format("d/m/Y H:i:s");
        $data["updated_at_fr"] = Carbon::parse($data["updated_at"])->format("d/m/Y H:i:s");
        $data["contract_id"] = isset($data["contract_id"]) ? (int) $data["contract_id"] : null;
        $data["notification_id"] = isset($data["notification_id"]) ? (int) $data["notification_id"] : null;
        $data["other_expenses"] = (int) $data["other_expenses"];
        $data["teg"] = (int) $data["teg"];
        return $data;
    }

    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class, "contract_id", "id");
    }

    public function validator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'validator_user_id', 'id');
    }
    public function unblocker(): BelongsTo
    {
        return $this->belongsTo(User::class, 'unblocker_user_id', 'id');
    }

    public function notification(): BelongsTo
    {
        return $this->belongsTo(Notification::class, 'notification_id', 'id');
    }

    public function getStatusAttribute()
    {
        if ($this->validation_status == "waiting") {
            return [
                "level" => 1,
                "color" => "warning",
                "message" => "En attende de validation"
            ];
        } elseif ($this->validation_status == "rejected") {
            return [
                "level" => -1,
                "color" => "error",
                "message" => "Rejeté par le head crédit"
            ];
        } else {
            if ($this->unblock_status == "waiting") {
                return [
                    "level" => 2,
                    "color" => "primary",
                    "message" => "En attente de déblocage"
                ];
            } elseif ($this->unblock_status == "rejected") {
                return [
                    "level" => -2,
                    "color" => "error",
                    "message" => "Rejeté par les opérations"
                ];
            } else {
                return [
                    "level" => 3,
                    "color" => "success",
                    "message" => "Débloqué"
                ];
            }
        }
    }
    public function getCommentAttribute()
    {
        if ($this->unblock_comment && in_array($this->unblock_status, ["rejected", "validated"])) {
            return $this->unblock_comment;
        } elseif ($this->validation_comment && in_array($this->validation_status, ["rejected", "validated"])) {
            return $this->validation_comment;
        }
        return $this->validation_comment;
    }
}
