<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Comment;
use App\Models\Event;
use App\Models\User;

class CommentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Comment::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'body' => $this->faker->text(),
            'event_id' => Event::factory(),
            'user_id' => User::factory(),
        ];
    }
}
