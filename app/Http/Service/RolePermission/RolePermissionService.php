<?php

namespace App\Http\Service\RolePermission;

use App\Http\Service\Base\BaseService;
use Spatie\Permission\Models\Permission;

class RolePermissionService extends BaseService
{
    protected function initialize(): void
    {
        parent::initialize();
        $this->setSearchableFields(['name']);
        $this->setSorters(['name', 'created_at']);
    }

    protected function getFormData(?object $item = null): array
    {
        return [
            'permissions' => Permission::all()->pluck('name'),
        ];
    }
}
