<?php

namespace App\Http\Controllers\Admin\RolePermission;

use App\Http\Controllers\Controller;
use App\Http\Service\RolePermission\RolePermissionService;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;

class RolePermissionController extends Controller
{
    public function __construct(
        private readonly RolePermissionService $rolePermissionService
    )
    {
    }

    public function index()
    {
        $roles = $this->rolePermissionService->getRolesWithPermissions();

        return Inertia::render('admin/roles-permissions/index', [
            'roles' => $roles,
        ]);
    }

    public function show(Role $role)
    {
        $data = $this->rolePermissionService->getRoleDetails($role);

        return Inertia::render('admin/roles-permissions/show', [
            'role' => $data['role'],
            'permissions' => $data['permissions'],
            'users' => $data['role']->users,
        ]);
    }

    public function getRoles()
    {
        return $this->rolePermissionService->getRolesWithPermissions();
    }
}
