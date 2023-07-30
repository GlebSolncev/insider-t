<?php

namespace Database\Factories;

use App\Models\Club;
use App\Models\Player;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Player>
 */
class PlayerFactory extends Factory
{
    protected $positions = [
        'Goalkeeper',
        'Right Back',
        'Center Back',
        'Left Back',
        'Defensive Midfielder',
        'Central Midfielder',
        'Right Winger',
        'Attacking Midfielder',
        'Striker',
        'Left Winger'
    ];

    protected $model = Player::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'position' => $this->positions[$this->faker->numberBetween(0, count($this->positions) -1)],
            'rating' => $this->faker->numberBetween(0, 5),
        ];
    }
}
