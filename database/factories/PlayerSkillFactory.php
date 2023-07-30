<?php

namespace Database\Factories;

use App\Models\Player;
use App\Models\PlayerSkill;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;

/**
 * @extends Factory<\App\Models\Player>
 */
class PlayerSkillFactory extends Factory
{
    /**
     * @var array
     */
    protected array $skills;

    protected $model = PlayerSkill::class;

    public function __construct($count = null, ?Collection $states = null, ?Collection $has = null, ?Collection $for = null, ?Collection $afterMaking = null, ?Collection $afterCreating = null, $connection = null)
    {
        parent::__construct($count, $states, $has, $for, $afterMaking, $afterCreating, $connection);
        $this->skills = Config::get('skills');
    }

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $group = array_keys($this->skills);
        $group = $group[$this->faker->numberBetween(0, count($group) - 1)];

        return [
            'name' => $this->skills[$group][$this->faker->numberBetween(0, count($this->skills[$group]) - 1)],
            'group' => $group,
            'value' => $this->faker->numberBetween(0, 100),
        ];
    }
}
