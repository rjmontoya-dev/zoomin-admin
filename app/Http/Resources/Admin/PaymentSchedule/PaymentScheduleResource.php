<?php

namespace App\Http\Resources\Admin\PaymentSchedule;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentScheduleResource extends JsonResource
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
            'payment_detail_id' => $this->payment_detail_id,
            'due_date' => $this->due_date,
            'due_amount' => $this->due_amount,
            'status' => $this->status,
            'status_information' => $this->status->pill(),
            'remarks' => $this->remarks,
            'date_payment' => $this->date_payment,
            'amount_paid' => $this->amount_paid,
        ];
    }
}
