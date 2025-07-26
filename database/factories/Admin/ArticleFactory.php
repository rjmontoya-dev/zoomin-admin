<?php

namespace Database\Factories\Admin;

use App\Enums\Statuses\ArticleStatus;
use App\Models\Admin\Article;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Admin\Article>
 */
class ArticleFactory extends Factory
{

    protected $model = Article::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
         return [
        'title' => $this->faker->sentence(),
        'author' => $this->faker->name(),
        'status' => $this->faker->randomElement(ArticleStatus::cases()),
        'body' => $this->faker->paragraphs(3),
    ];
    }
}
