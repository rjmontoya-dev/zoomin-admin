<?php

namespace App\Http\Resources\Admin\Project;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class IndexCollection extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'project_code' => $this->project_code,
            'client_name' => $this->client_name,
            'amount' => $this->amount,
            'status' => $this->status->pill(),
            'contact_person' => $this->contact_person ?? null,
            'contact_number' => $this->contact_number ?? null,
            'start_date' => $this->start_date,
            'payment_status' => $this->paymentDetail?->status->pill(),
            'payment_type' => $this->paymentDetail?->payment_type?->pill() ?? null,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
