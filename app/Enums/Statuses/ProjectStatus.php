<?php

namespace App\Enums\Statuses;

use App\Http\Traits\HandleEnum;

enum ProjectStatus: int
{
    use HandleEnum;

    case WAITING_SCHEDULE = 1;
    case IN_PROGRESS = 2;
    case CANCELED = 3;
    case WITH_REPAIR = 4;
    case COMPLETED = 5;

    /**
     * Meta properties.
     *
     * @return array
     */
    public static function metaProperties(): array
    {
        return [
            [
                'value' => self::WAITING_SCHEDULE,
                'title' => 'Waiting Schedule',
                'color' => 'yellow',
            ],
            [
                'value' => self::IN_PROGRESS,
                'title' => 'In Progress',
                'color' => 'blue',
            ],
            [
                'value' => self::CANCELED,
                'title' => 'Canceled',
                'color' => 'red',
            ],
            [
                'value' => self::WITH_REPAIR,
                'title' => 'With Repair',
                'color' => 'orange',
            ],
            [
                'value' => self::COMPLETED,
                'title' => 'Completed',
                'color' => 'green',
            ],
        ];
    }
}
