<?php

namespace App\Http\Controllers\Admin\ActivityLog;

use App\Http\Controllers\Base\BaseController;
use App\Http\Resources\System\ActivityIndexCollection;
use App\Http\Service\ActivityLog\ActivityLogService;
use App\Models\System\Activity;
use Illuminate\Http\Request;
use Inertia\Response;

class ActivityLogController extends BaseController
{
    protected function setModel(): string
    {
        return Activity::class;
    }

    protected function setService(): string
    {
        return ActivityLogService::class;
    }

    protected function setIndexResource(): string
    {
        return ActivityIndexCollection::class;
    }

    protected function setEditResource(): ?string
    {
        return null;
    }

    protected function getViewPath(): string
    {
        return 'admin/activity-log';
    }

    public function index(Request $request): Response
    {
        return parent::baseIndex($request);
    }
}
