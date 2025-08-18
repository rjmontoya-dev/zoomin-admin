<?php

namespace App\Enums\Types;

use App\Http\Traits\HandleEnum;

enum PaymentType: int
{
    use HandleEnum;

    case FULL = 1;
    case INSTALLMENT = 2;

     /**
     * Meta properties.
     *
     * @return array
     */
    public static function metaProperties(): array
    {
        return [
            [
                'value' => self::FULL,
                'title' => 'Full',
                'color' => 'green'
            ],
            [
                'value' => self::INSTALLMENT,
                'title' => 'Installment',
                'color' => 'yellow'
            ],
        ];
    }
}
