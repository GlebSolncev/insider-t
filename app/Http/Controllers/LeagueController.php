<?php

namespace App\Http\Controllers;

use App\Http\Requests\LeagueRequest;
use App\Http\Resources\LeagueResource;
use App\Services\LeagueService;
use ErrorException;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;

/**
 * Class LeagueController
 * @package App\Http\Controllers
 */
class LeagueController extends Controller
{

    /**
     * @param LeagueService $service
     */
    public function __construct(
        protected LeagueService $service
    ) {
    }

    /**
     * @param LeagueRequest $request
     * @return JsonResponse
     * @throws ErrorException
     * @throws BindingResolutionException
     */
    public function store(LeagueRequest $request)
    {
        return response()->json(
            $this->service->createLeague($request->groups),
            JsonResponse::HTTP_CREATED
        );
    }

    /**
     * @param LeagueRequest $request
     * @return Model
     */
    public function play(LeagueRequest $request)
    {
        return new LeagueResource($this->service->getGamesById($request->league_id, $request->week));
    }
}
