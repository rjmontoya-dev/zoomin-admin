<?php

namespace Database\Seeders\Admin\Maintenance;

use App\Models\Admin\Maintenance\Maintenance;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class MaintenanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Maintenance::create([
            'message' => 'The site is currently down for maintenance.',
            'status' => 'disabled',
            'start_at' => Carbon::now(),
            'end_at' => null,
        ]);
    }
}
