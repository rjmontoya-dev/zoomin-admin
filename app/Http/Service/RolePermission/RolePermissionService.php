<?php

namespace App\Http\Service\RolePermission;

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionService
{
    public function getRolesWithPermissions()
    {
        return Role::all();
    }

    public function getRoleDetails(Role $role)
    {
        return [
            'role' => $role->load('permissions', 'users'),
            'permissions' => Permission::all(),
        ];
    }
}
