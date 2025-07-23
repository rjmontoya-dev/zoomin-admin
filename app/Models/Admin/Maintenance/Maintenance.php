<?php

namespace App\Models\Admin\Maintenance;

use Illuminate\Database\Eloquent\Model;

class Maintenance extends Model
{
    protected $fillable = [
        'message',
        'image',
        'status',
        'start_at',
        'end_at',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'start_at' => 'date',
        'end_at' => 'date',
    ];
}
