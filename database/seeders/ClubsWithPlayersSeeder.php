<?php

namespace Database\Seeders;

use App\Models\Club;
use App\Models\Player;
use App\Models\PlayerSkill;
use Illuminate\Database\Seeder;

/**
 * Class ClubsWithPlayersSeeder
 * @package Database\Seeders
 */
class ClubsWithPlayersSeeder extends Seeder
{
    /**
     * @var array[]
     */
    protected $clubs = [
        [
            'name' => 'Arsenal',
            'is_active' => true
        ],
        [
            'name' => 'Liverpool',
            'is_active' => true
        ],
        [
            'name' => 'Shahter Donetsk',
            'is_active' => true
        ],
        [
            'name' => 'Dinamo kiev',
            'is_active' => true
        ],
        [
            'name' => 'Juventus',
            'is_active' => true
        ]
    ];


    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Club::query()->delete();
        Player::query()->delete();
        PlayerSkill::query()->delete();

        Club::insert($this->clubs);
        for ($i = Club::min('id'); $i <= Club::max('id'); $i++) {
            $players = Player::factory()->count(12)->make();

            foreach ($players as $player) {
                $player->club_id = $i;
                $player->save();

                $this->createPlayerSkills($player->id);
            }
        }
    }

    /**
     * @param $playerId
     * @return void
     * @throws \Exception
     */
    protected function createPlayerSkills($playerId)
    {
        $groupSkiils = config('skills');

        $data = [];
        foreach ($groupSkiils as $group => $skills) {
            foreach ($skills as $skill) {
                $data[] = [
                    'group' => $group,
                    'name' => $skill,
                    'player_id' => $playerId,
                    'value' => random_int(0, 60)
                ];
            }
        }


        PlayerSkill::insert($data);
    }
}
