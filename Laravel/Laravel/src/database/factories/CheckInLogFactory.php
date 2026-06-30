<?php

namespace Database\Factories;

use App\Models\CheckInLog;
use App\Models\Room;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CheckInLog>
 */
class CheckInLogFactory extends Factory
{
    protected $model = CheckInLog::class;

    public function definition(): array
    {
        return [
            'user_id'    => User::factory(),
            'room_id'    => Room::factory(),
            'scanned_by' => User::factory(),
            'type'       => fake()->randomElement(['masuk', 'keluar']),
            'scanned_at' => fake()->dateTimeBetween('-1 month', 'now'),
            'notes'      => fake()->optional()->sentence(),
        ];
    }

    public function masuk(): static
    {
        return $this->state(fn () => ['type' => 'masuk']);
    }

    public function keluar(): static
    {
        return $this->state(fn () => ['type' => 'keluar']);
    }
}