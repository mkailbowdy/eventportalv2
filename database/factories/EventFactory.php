<?php

namespace Database\Factories;

use App\Enums\Category;
use App\Enums\Prefecture;
use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;

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
        $date = now()->addMonths(2);
        $startTime = now()->addHours(1);
        $endTime = now()->addHours(4);

        return [
            'name' => $this->faker->name(),
            'description' => $this->faker->text(),
            'category' => $this->faker->randomElement(Category::class),
            'date' => $date,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'capacity' => $this->faker->numberBetween(1, 100),
            'prefecture' => $this->faker->randomElement(Prefecture::class),
            'meeting_spot' => $this->faker->word(),
            'group_id' => $this->faker->randomNumber(),
            'user_id' => null,
            'images' => null,
        ];
    }
}
