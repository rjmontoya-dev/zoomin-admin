<?php

namespace App\Enums\Statuses;

use App\Http\Traits\HandleEnum;

enum PaymentScheduleStatus: int
{
    use HandleEnum;

    case PENDING = 1;
    case PAID = 2;
    case OVERDUE = 3;

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
                'color' => 'green',
            ],
            [
                'value' => self::PAID,
                'title' => 'Paid',
                'color' => 'yellow',
            ],
            [
                'value' => self::OVERDUE,
                'title' => 'OverDue',
                'color' => 'red',
            ],
        ];
    }
}
