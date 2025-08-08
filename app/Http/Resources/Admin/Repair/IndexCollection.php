<?php

namespace App\Http\Resources\Admin\Repair;

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
            'reported_issue' => $this->reported_issue,
            'date_reported' => $this->date_reported,
            'status' => $this->status->pill(),
            'remarks' => $this->remarks,
            'repair_date' => $this->repair_date,
            'amount' => $this->amount,
            'date_finished' => $this->date_finished,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'agent' => $this->user->name ?? null,
        ];
    }
}
