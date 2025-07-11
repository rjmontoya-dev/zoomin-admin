<?php

namespace App\Models\Admin;

use App\Models\System\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Article extends BaseModel
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'author',
        'status',
        'body'
    ];

    protected $casts = [
        'body' => 'array',
    ];
}
