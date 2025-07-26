<?php

namespace App\Enums\Statuses;

use App\Http\Traits\HandleEnum;

enum ArticleStatus: int
{
    use HandleEnum;

    case DRAFT = 1;
    case PUBLISH = 2;

    /**
     * Meta properties.
     *
     * @return array
     */
    public static function metaProperties(): array
    {
        return [
            [
                'value' => self::DRAFT,
                'title' => 'Draft',
            ],
            [
                'value' => self::PUBLISH,
                'title' => 'Publish',
            ],
        ];
    }
}
