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
        $startDate = now()->addMonths(9);
        $endDate = now()->addMonths(9)->addDays(2);
        return [
            'name' => $this->faker->name(),
            'description' => $this->faker->text(),
            'category' => $this->faker->randomElement(Category::class),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'capacity' => $this->faker->randomNumber(),
            'prefecture' => $this->faker->randomElement(Prefecture::class),
            'meeting_spot' => $this->faker->word(),
            'photo_path' => "",
            'group_id' => $this->faker->randomNumber(),
            'user_id' => null,
        ];
    }
}
