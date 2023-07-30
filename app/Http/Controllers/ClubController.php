<?php

namespace App\Http\Controllers;

use App\Http\Resources\ClubResource;
use App\Services\ClubService;
use JsonSerializable;

/**
 * Class ClubController
 * @package App\Http\Controllers
 */
class ClubController extends Controller
{
    /**
     * @param ClubService $service
     */
    public function __construct(
        protected ClubService $service
    ) {
    }

    /**
     * @return JsonSerializable
     */
    public function index(): JsonSerializable
    {
        return ClubResource::collection($this->service->getAll());
    }
}
