<?php

namespace App\Http\Resources\Admin\Project;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Admin\PaymentDetail\PaymentDetailResource;

class ProjectShowResource extends JsonResource
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
            'agent' => $this->user->name ?? null,
            'project_code' => $this->project_code,
            'client_name' => $this->client_name,
            'amount' => $this->amount ?? null,
            'status' => $this->status->pill(),
            'contact_person' => $this->contact_person ?? null,
            'complete_address' => $this->completeAddress ?? null, 
            'contact_number' => $this->contact_number ?? null,  
            'business_type' => $this->business_type->pill(),
            'project_acquired' => $this->project_acquired,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date, 
            'created_at' => $this->created_at,
            'payment_detail' => new PaymentDetailResource($this->whenLoaded('paymentDetail')),
        ];
    }
}