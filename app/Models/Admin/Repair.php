<?php

namespace App\Models\Admin;

use App\Enums\Statuses\RepairStatus;
use App\Models\System\BaseModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Repair extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'project_id',
        'user_id',
        'reported_issue',
        'date_reported',
        'status',
        'remarks',
        'repair_date',
        'amount',
        'date_finished',
    ];

    protected $casts = [
        'status' => RepairStatus::class,
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
