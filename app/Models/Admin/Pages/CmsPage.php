<?php

namespace App\Models\Admin\Pages;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class CmsPage extends Model
{
     use HasFactory ;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array<string>|bool
     */
    public $guarded = [];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'content' => 'array',
    ];

    /*
    |--------------------------------------------------------------------------
    | METHODS
    |--------------------------------------------------------------------------
     */

    // public function getActivitylogOptions(): LogOptions
    // {
    //     return LogOptions::defaults()
    //         ->logOnlyDirty()
    //         ->logUnguarded()
    //         ->setDescriptionForEvent(fn(string $eventName) => "CMS {$this->label} has been {$eventName} by " . (Auth::user()?->name ?? 'System'))
    //         ->useLogName('cms');
    // }
    /**
     * Render a URL for the given path.
     *
     * @param string $path
     * @param string|null $default
     *
     * @return string
     */
    public function renderUrl(string $path, ?string $default = null): string
    {
        return Storage::url($path ?: $default);
    }

}
