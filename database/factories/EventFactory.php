<?php

namespace Database\Factories;

use App\Enums\Category;
use App\Enums\Prefecture;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Event;
use App\Models\User;

class EventFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Event::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'description' => $this->faker->text(),
            'category' => $this->faker->randomElement(Category::class),
            'start_date' => $this->faker->dateTime(),
            'end_date' => $this->faker->dateTime(),
            'capacity' => $this->faker->randomNumber(),
            'prefecture' => $this->faker->randomElement(Prefecture::class),
            'meeting_spot' => $this->faker->word(),
            'photo_path' => "",
            'group_id' => $this->faker->randomNumber(),
            'user_id' => null,
        ];
    }
}
