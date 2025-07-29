<?php

namespace App\Enums\Statuses;

use App\Http\Traits\HandleEnum;

enum RepairStatus: int
{
    use HandleEnum;

    case PENDING = 1;
    case ONGOING = 2;
    case DONE = 3;

    /**
     * Meta properties.
     *
     * @return array
     */
    public static function metaProperties(): array
    {
        return [
            [
                'value' => self::PENDING,
                'title' => 'Pending',
                'color' => 'gray',
            ],
            [
                'value' => self::ONGOING,
                'title' => 'On-going',
                'color' => 'yellow',
            ],
            [
                'value' => self::DONE,
                'title' => 'Done',
                'color' => 'green',
            ], 
        ];
    }
}
