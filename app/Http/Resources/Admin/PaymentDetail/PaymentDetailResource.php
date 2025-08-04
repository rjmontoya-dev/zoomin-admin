<?php

namespace App\Http\Resources\Admin\PaymentDetail;

use App\Http\Resources\Admin\PaymentSchedule\PaymentScheduleResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'project_id' => $this->project_id,
            'total_amount' => $this->total_amount ?? null,
            'payment_type' => $this->payment_type,
            'payment_type_information' => $this->payment_type->pill(),
            'status' => $this->status,
            'status_information' => $this->status->pill(),
            'number_installments' => $this->number_installments,
            'amount_paid' => $this->amount_paid,
            'balance' => $this->balance,
            'remarks' => $this->remarks,
            'date_payment' => $this->date_payment,
            'payment_schedules' => PaymentScheduleResource::collection($this->whenLoaded('paymentSchedules')),
        ];
    }
}
