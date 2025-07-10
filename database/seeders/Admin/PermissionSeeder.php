<?php

namespace Database\Seeders\Admin;

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

        foreach ($permissions as $group) {
            foreach ($group as $permission) {
                $newPermission = Permission::create([
                    'name' => $permission['name'],
                    'guard_name' => $permission['guard_name'],
                ]);

                $roles = Role::whereIn('name', $permission['roles'])->get();

                $newPermission->assignRole($roles);
            }
        }
    }
}
