<?php

namespace App\Enums\Types;

use App\Http\Traits\HandleEnum;

enum BusinessType: string
{
    use HandleEnum;
    case PUBLIC = 'Public';
    case PRIVATE_COMPANY = 'Private Company';
    case STORE = 'Store';
    case RESIDENCE = 'Residence';
    case COMMERCIAL_BUILDING = 'Commercial Building';

    /**
     * Meta properties.
     *
     * @return array
     */
    public static function metaProperties(): array
    {
        return [
            [
                'value' => self::PUBLIC,
                'title' => 'Public',
            ],
            [
                'value' => self::PRIVATE_COMPANY,
                'title' => 'Private Company',
            ],
            [
                'value' => self::STORE,
                'title' => 'Store',
            ],
            [
                'value' => self::RESIDENCE,
                'title' => 'Residence',
            ],
            [
                'value' => self::COMMERCIAL_BUILDING,
                'title' => 'Commercial Building',
            ],
        ];
    }
}
