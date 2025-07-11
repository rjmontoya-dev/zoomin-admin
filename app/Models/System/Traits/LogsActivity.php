<?php

namespace App\Models\System\Traits;

use App\Models\System\Traits\PrettyLog;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity as TraitsLogsActivity;

trait LogsActivity
{
    use PrettyLog;
    use TraitsLogsActivity;
}
