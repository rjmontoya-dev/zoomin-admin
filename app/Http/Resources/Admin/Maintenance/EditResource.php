<?php

namespace App\Http\Resources\Admin\Maintenance;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

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
            'message' => $this->message ?? null,
            'image' => $this->image ?? null,
            'status' => $this->status == 'enabled' ? true : false,
            'start_at' => $this->start_at,
            'end_at' => $this->end_at,
        ];
    }
}
