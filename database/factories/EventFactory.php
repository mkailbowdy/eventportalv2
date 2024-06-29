<?php

namespace Database\Factories;

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
            'start_date' => $this->faker->dateTime(),
            'end_date' => $this->faker->dateTime(),
            'capacity' => $this->faker->randomNumber(),
            'prefecture' => $this->faker->word(),
            'meeting_spot' => $this->faker->word(),
            'photo_path' => $this->faker->word(),
            'group_id' => $this->faker->randomNumber(),
            'user_id' => User::factory(),
        ];
    }
}
