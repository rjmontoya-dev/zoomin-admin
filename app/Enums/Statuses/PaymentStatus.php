<?php

namespace App\Enums\Statuses;

use App\Http\Traits\HandleEnum;

enum PaymentStatus: int
{
    use HandleEnum;
    case PAID = 1;
    case PARTIAL = 2;
    case UNPAID = 3;

    /**
     * Meta properties.
     *
     * @return array
     */
    public static function metaProperties(): array
    {
        return [
            [
                'value' => self::PAID,
                'title' => 'Paid',
                'color' => 'green',
            ],
            [
                'value' => self::PARTIAL,
                'title' => 'Partial',
                'color' => 'yellow',
            ],
            [
                'value' => self::UNPAID,
                'title' => 'Unpaid',
                'color' => 'red',
            ],
        ];
    }

}
