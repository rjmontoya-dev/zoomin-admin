<?php

namespace App\Models\Admin;

use App\Enums\Statuses\PaymentStatus;
use App\Enums\Types\PaymentType;
use App\Models\System\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Admin\PaymentSchedule;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentDetail extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'project_id',
        'total_amount',
        'payment_type',
        'status',
        'number_installments',
        'amount_paid',
        'balance',
        'remarks',
        'date_payment',
    ];

    protected $casts = [
        'payment_type' => PaymentType::class,
        'status' => PaymentStatus::class,
        'date_payment' => 'date',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function paymentSchedules(): HasMany
    {
        return $this->hasMany(PaymentSchedule::class);
    }
}
