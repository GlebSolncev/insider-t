<?php

namespace App\Services;

use App\Models\League;
use ErrorException;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

/**
 * Class LeagueService
 * @package App\Services
 */
class LeagueService extends AbstractService
{

    /**
     * @param League $model
     * @param ClubService $clubService
     */
    public function __construct(
        protected League      $model,
        protected ClubService $clubService
    )
    {
    }

    /**
     * @param array $groups
     * @return array
     * @throws BindingResolutionException
     * @throws ErrorException
     */
    public function createLeague(array $groups): array
    {
        if (!$this->checkByModule(Collection::make($groups)->flatten(1)->count()))
            throw new ErrorException(__('The number of teams does not fit. You need to choose 4,8,16 etc'), 400);

        $clubs = $this->clubService->getByIds(Arr::flatten($groups, 1))->pluck('name', 'id');
        $this->model->save();

        do {
            $winner = Collection::make();

            foreach ($groups as $group) {
                $matchInfoOne = $this->addMatch($this->model, $group);
                $matchInfoTwo = $this->addMatch($this->model, array_reverse($group));

                $mergeTwoMatches = Collection::make()
                    ->add($matchInfoOne)
                    ->add($matchInfoTwo)
                    ->flatten(1)
                    ->groupBy('club_id')
                    ->map(function ($group) {
                        $item = $group->first();
                        $item->goals = $group->sum('goals');
                        return $item;
                    });

                $winner->add(
                    $mergeTwoMatches->where('goals', $mergeTwoMatches->max('goals'))->first()
                );
            }

            $groups = $winner->pluck('club_id')->chunk(2)->toArray();
        } while (count(reset($groups)) > 1);

        return [
            'league_id' => $this->model->getAttribute('id'),
            'clubs' => $clubs,
            'max_weeks' => $this->model->getAttribute('matchGames')->count(),
        ];
    }

    /**
     * @param int $leagueId
     * @param int|null $week
     * @return League
     */
    public function getGamesById(int $leagueId, ?int $week = null): League
    {
        $model = $this->model->with([
            'matchGames',
            'matchGames.games',
            'matchGames.games.club',
        ])->find($leagueId);

        if ($week) {
            $match = $model->matchGames()->limit(1)->offset($week)->get();
            $model->setAttribute('matchGames', Collection::make($match));
        }

        return $model;
    }

    /**
     * @param int $count
     * @return bool
     */
    protected function checkByModule(int $count): bool
    {
        $status = false;

        do {
            if ($count % 2 !== 0) {
                break;
            }

            $newCount = $count / 2;
            if ($newCount == 1) {
                $count = 1;
                $status = true;
            } else {
                $count = $count / 2;
            }
        } while ($count != 1);

        return $status;
    }

    /**
     * @param League $league
     * @param array $group
     * @return Collection
     * @throws BindingResolutionException
     * @throws ErrorException
     */
    protected function addMatch(League $league, array $group): Collection
    {
        $games = Collection::make([]);
        $infoMatches = Collection::make()->add([
            $this->addGoals(reset($group), end($group)),
            $this->addGoals(end($group), reset($group)),
        ]);

        foreach ($infoMatches as $match) {
            $matchModel = $league->matchGames()->create();
            foreach ($match as $club) {
                $games->add($matchModel->games()->create([
                    'club_id' => Arr::get($club, 'id'),
                    'goals' => Arr::get($club, 'goals'),
                ]));
            }
        }

        return $games;
    }

    /**
     * @param $fId
     * @param $lId
     * @return Model
     * @throws ErrorException
     * @throws BindingResolutionException
     */
    protected function addGoals($fId, $lId): Model
    {
        $gClub = $this->clubService->getInfoByClubId($fId);
        $sClub = $this->clubService->getInfoByClubId($lId);
        $onePower = Arr::get($gClub->power, 'strength') +  Arr::get($gClub->power, 'attack');
        //Arr::get(, 'power.strength') + Arr::get($gClub, 'power.attack');
        $twoPower = Arr::get($sClub->power, 'strength') + Arr::get($sClub->power, 'protection');
        //Arr::get($sClub, 'power.strength') + Arr::get($sClub, 'power.protection');
        $steps = ($onePower - $twoPower);

        if ($steps < 0) {
            $steps = $steps * (-1);
        }

        $goals = 0;
        for ($i = 0; $i < $steps; $i++) {
            $goals += rand(0, 1);
        }

        $gClub->goals = $goals;

        return $gClub;
    }

}
