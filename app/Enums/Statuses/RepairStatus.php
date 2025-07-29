<?php

namespace App\Enums\Statuses;

use App\Http\Traits\HandleEnum;

enum RepairStatus: int
{
    use HandleEnum;

    case PENDING = 1;
    case ONGOING = 2;
    case DONE = 3;
}
