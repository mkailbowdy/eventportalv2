<?php

namespace Database\Factories;

use App\Enums\Prefecture;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Group;

class GroupFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Group::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'description' => $this->faker->text(),
            'prefecture' => $this->faker->randomElement(Prefecture::class),
            'photo_path' => '',
        ];
    }
}
