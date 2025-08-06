<?php

namespace App\Http\Resources\Admin\Inquiry;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

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
            'id' => $this->getKey(),
            'name' => $this->name,
            'email' => $this->email,
            'company' =>  $this->company ?? null,
            'service' => $this->service ?? null,
            'budget' => $this->budget ?? null,
            'message' => $this->message,
            'created_at' => $this->created_at,
        ];
    }
}
