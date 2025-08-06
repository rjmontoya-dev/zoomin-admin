<?php

namespace App\Models\Admin\Inquiry;

use App\Models\System\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inquiry extends BaseModel
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'company',
        'service',
        'budget',
        'message'
    ];
}
