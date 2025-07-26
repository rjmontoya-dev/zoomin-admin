<?php

namespace App\Http\Resources\Admin\Article;

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
            'title' => $this->title ?? null,
            'author' => $this->author ?? null,
            'status' => $this->status->pill() ?? null,
            'created_at' => $this->created_at ?? null,
        ];
    }
}
