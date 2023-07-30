<?php

namespace App\Services;

use App\Models\Player;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;

/**
 * Class PlayerService
 * @package App\Services
 */
class PlayerService extends AbstractService
{
    /**
     * @param Collection $players
     * @return array
     */
    public function getSquadInfo(Collection $players): array
    {
        $skills = Config::get('skills');
        $info = Config::get('power-skills');
        $result = [];

        $collection = $players->map(function (Player $player) use ($skills) {
            foreach ($skills as $name => $group) {
                $value = $player->skills->whereIn('name', $group)->sum('value') / 100;

                $player->$name = $value;
            }
            return $player;
        })->map(function (Player $player) use ($info) {
            foreach ($info as $name => $skills) {
                $value = array_sum($player->only($skills)) / $player->skills->count();
                $player->$name = round($value, 1);
            }

            return $player->only(array_keys($info));
        });

        foreach (array_keys($info) as $key) {
            $result[$key] = $collection->sum($key);
        }

        return $result;
    }
}
