<?php

namespace App\Http\Controllers\Admin\RolePermission;

use App\Http\Controllers\Base\BaseController;
use App\Http\Resources\Admin\RolePermission\EditResource;
use App\Http\Resources\Admin\RolePermission\IndexCollection;
use App\Http\Service\RolePermission\RolePermissionService;
use App\Models\Admin\Role\Role;
use Illuminate\Http\Request;

class RolePermissionController extends BaseController
{
    protected function setModel(): string
    {
        return Role::class;
    }

    protected function setService(): string
    {
        return RolePermissionService::class;
    }

    protected function setIndexResource(): string
    {
        return IndexCollection::class;
    }

    protected function setEditResource(): ?string
    {
        return EditResource::class;
    }

    protected function getViewPath(): string
    {
        return 'admin/roles-permissions';
    }

    protected function getWith(): array
    {
        return ['permissions', 'users'];
    }

    protected function getRoutePrefix(): string
    {
        return 'admin.roles-permissions';
    }

    public function index(Request $request)
    {
        return parent::baseIndex($request);
    }

    public function edit(Request $request, Role $role)
    {
        return parent::baseEdit($request, $role);
    }
}
