<?php

namespace App\Models\Admin;

use App\Enums\Statuses\ProjectStatus;
use App\Enums\Types\BusinessType;
use App\Models\System\BaseModel;
use App\Enums\Statuses\RepairStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'project_code',
        'client_name',
        'amount',
        'status',
        'contact_person',
        'province',
        'town',
        'barangay',
        'business_type',
        'contact_number',
        'fb_name',
        'project_acquired',
        'project_detail',
        'start_date',
        'end_date',
        'tech_warranty',
        'hardware_warranty',
        'expenses',
        'user_id',
    ];

    protected $casts = [
        'status' => ProjectStatus::class,
        'business_type' => BusinessType::class,
        'project_acquired' => 'date',
        'start_date' => 'date',
        'end_date' => 'date',
        'tech_warranty' => 'date',
        'hardware_warranty' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }


public function paymentDetail(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(PaymentDetail::class);
    }
    public function repairs(): HasMany
    {
        return $this->hasMany(Repair::class);
    }

    protected function net(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->amount - $this->expenses,
        );
    }

    public function completeAddress(): Attribute
    {
        return Attribute::get(
            fn () => "{$this->province}, {$this->town}, {$this->barangay}"
        );
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($project) {
            if ($project->relationLoaded('repairs')) {
                $hasPendingOrOngoingRepairs = $project->repairs->contains(function ($repair) {
                    return in_array($repair->status, [RepairStatus::PENDING->value, RepairStatus::ONGOING->value]);
                });

                if ($hasPendingOrOngoingRepairs) {
                    $project->status = ProjectStatus::WITH_REPAIR->value;
                }
            }
        });
    }
}
