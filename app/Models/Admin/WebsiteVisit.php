<?php

namespace App\Models\Admin;

use App\Models\System\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class WebsiteVisit extends BaseModel
{
    use SoftDeletes;

    protected $fillable = [
        'ip_address',
        'user_agent',
        'visited_at',
    ];
}
