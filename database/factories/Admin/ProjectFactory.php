<?php

namespace Database\Factories\Admin;

use App\Enums\Statuses\ProjectStatus;
use App\Enums\Types\BusinessType;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Admin\Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'project_code' => $this->faker->unique()->ean8(),
            'client_name' => $this->faker->company(),
            'amount' => $this->faker->randomFloat(2, 10000, 100000),
            'status' => $this->faker->randomElement(ProjectStatus::cases()),
            'contact_person' => $this->faker->name(),
            'province' => $this->faker->state(),
            'town' => $this->faker->city(),
            'barangay' => $this->faker->streetName(),
            'business_type' => $this->faker->randomElement(BusinessType::cases()),
            'contact_number' => $this->faker->phoneNumber(),
            'fb_name' => $this->faker->userName(),
            'project_acquired' => $this->faker->date(),
            'project_detail' => $this->faker->paragraph(),
            'start_date' => $this->faker->date(),
            'end_date' => $this->faker->date(),
            'tech_warranty' => $this->faker->date(),
            'hardware_warranty' => $this->faker->date(),
            'expenses' => $this->faker->randomFloat(2, 5000, 50000),
            'user_id' => 1,
        ];
    }
}
