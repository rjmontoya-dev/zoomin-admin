<?php

namespace Database\Seeders\Admin\RolePermission;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Permission::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $permissions = config('permissions-admin');

        foreach (config('permissions-admin') as $module => $submodules) {
            foreach ($submodules as $submodule) {
                $roles = $submodule['roles'];

                $permission = Permission::create([
                    'module' => $module,
                    'label' => $submodule['label'],
                    'name' => $submodule['name'],
                    'description' => $submodule['description'],
                    'guard_name' => 'web',
                    'category_module' => $submodule['category_module'],
                ]);

                $roles = Role::whereIn('name', $roles)->get();
                $permission->assignRole($roles);
            }
        }
    }
}
