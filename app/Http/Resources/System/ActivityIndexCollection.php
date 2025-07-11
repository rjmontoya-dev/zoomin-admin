<?php

namespace App\Http\Resources\System;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Spatie\Activitylog\Models\Activity;

class ActivityIndexCollection extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'subject_name' => $this->subject_name,
            'event' => $this->event,
            'description' => $this->description,
            'causer_name' => $this->causer_name,
            'created_at' => $this->created_at,
        ];
    }
}
