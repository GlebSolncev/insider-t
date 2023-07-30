<?php

namespace App\Services;

use App\Models\Club;
use ErrorException;
use Illuminate\Container\Container;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * Class ClubService
 * @package App\Services
 */
class ClubService extends AbstractService
{
    /**
     * @param Club $model
     */
    public function __construct(
        protected Club $model
    )
    {
    }

    /**
     * @return Collection
     */
    public function getAll(): Collection
    {
        return $this->model->all();
    }

    /**
     * @param array $ids
     * @return Collection
     * @throws ErrorException
     */
    public function getByIds(array $ids): Collection
    {
        $models = $this->model->whereIn('id', $ids)->get();

        $diff = array_diff($ids, $models->pluck('id')->toArray());
        if ($diff) {
            throw new ErrorException('Club not found. ' . json_encode(array_values($diff)));
        }

        return $models;
    }

    /**
     * @param int $id
     * @return Model|null
     */
    public function getById(int $id): Club|null
    {
        return $this->model->find($id);
    }

    /**
     * @param int $id
     * @return Model
     * @throws BindingResolutionException
     * @throws ErrorException
     */
    public function getInfoByClubId(int $id): Model
    {
        $model = $this->getById($id);
        if (!$model) throw new ErrorException('Club not found');

        /** @var PlayerService $playerService */
        $playerService = Container::getInstance()->make(PlayerService::class);
        $randomPlayers = $this->getRandomPlayers($model);
        $model->setAttribute('power', $playerService->getSquadInfo($randomPlayers));

        return $model;
    }

    /**
     * @param Club $model
     * @return Collection
     */
    protected function getRandomPlayers(Club $model): Collection
    {
        $players = $model->getAttribute('players');
        if ($players->count() <= Club::MAX_PLAYERS_IN_GAME)
            return $players;

        return $players->random(Club::MAX_PLAYERS_IN_GAME);
    }
}
