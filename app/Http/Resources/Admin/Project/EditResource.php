<?php

namespace App\Http\Resources\Admin\Project;

use App\Http\Resources\Admin\PaymentDetail\PaymentDetailResource;
use App\Http\Resources\Admin\Repair\RepairResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EditResource extends JsonResource
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
            'project_code' => $this->project_code,
            'client_name' => $this->client_name,
            'amount' => $this->amount,
            'status' => $this->status,
            'contact_person' => $this->contact_person,
            'province' => $this->province,
            'town' => $this->town,
            'barangay' => $this->barangay,
            'business_type' => $this->business_type,
            'contact_number' => $this->contact_number,
            'fb_name' => $this->fb_name,
            'project_acquired' => $this->project_acquired,
            'project_detail' => $this->project_detail,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'tech_warranty' => $this->tech_warranty,
            'hardware_warranty' => $this->hardware_warranty,
            'expenses' => $this->expenses,
            'user_id' => $this->user_id,
            'payment_details' => new PaymentDetailResource($this->whenLoaded('paymentDetail')),
            'repairs' => RepairResource::collection($this->whenLoaded('repairs')),
        ];
    }
}
