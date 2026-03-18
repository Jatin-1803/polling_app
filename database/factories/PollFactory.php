<?php

namespace Database\Factories;

use App\Models\Poll;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Poll>
 */
class PollFactory extends Factory
{
    protected $model = Poll::class;

    public function definition(): array
    {
        return [
            'question' => fake()->sentence(8) . '?',
            'admin_id' => User::factory()->state(['is_admin' => true]),
            'is_active' => true,
        ];
    }
}

