<?php

namespace App\Http\Resources\Admin\RolePermission;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class IndexCollection extends JsonResource
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
            'name' => $this->name,
            // 'members' => $this->users,
            // 'extra_members' => $this->users?->count() > 4
            //     ? $this->users->count() - 4
            //     : null,
        ];
    }
}
