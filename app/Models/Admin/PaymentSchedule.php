<?php

namespace App\Models\Admin;

use App\Enums\Statuses\PaymentScheduleStatus;
use App\Models\System\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentSchedule extends BaseModel
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'payment_detail_id',
        'due_date',
        'due_amount',
        'status',
        'amount_paid',
        'remarks',
        'date_payment',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'status' => PaymentScheduleStatus::class,
        'due_date' => 'date',
        'date_payment' => 'date'
    ];

    //// Relationships
    public function paymentDetail(): BelongsTo
    {
        return $this->belongsTo(PaymentDetail::class);
    }
}
