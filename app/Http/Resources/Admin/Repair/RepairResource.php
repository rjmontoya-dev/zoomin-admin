<?php

namespace App\Http\Resources\Admin\Repair;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RepairResource extends JsonResource
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
            'reported_issue' => $this->reported_issue,
            'date_reported' => $this->date_reported,
            'status' => $this->status,
            'remarks' => $this->remarks,
            'repair_date' => $this->repair_date,
            'amount' => $this->amount,
            'date_finished' => $this->date_finished,
            'user_id' => $this->user_id,
            'agent' => $this->user->name ?? null,
        ];
    }
}
