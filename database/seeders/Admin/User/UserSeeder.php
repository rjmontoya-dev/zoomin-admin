<?php

namespace Database\Seeders\Admin\User;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'rjmontoya-dev@mail.com'],
            [
                'name' => 'Rj Dev',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
            ]
        );
    }
}
