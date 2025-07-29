<?php

namespace Database\Seeders;

use Database\Seeders\Admin\Maintenance\MaintenanceSeeder;
use Database\Seeders\Admin\Project\ProjectSeeder;
use Database\Seeders\Admin\RolePermission\PermissionSeeder;
use Database\Seeders\Admin\RolePermission\RoleSeeder;
use Database\Seeders\Admin\User\UserSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            RoleSeeder::class,
            PermissionSeeder::class,
            MaintenanceSeeder::class,
            ProjectSeeder::class,
        ]);
    }
}
