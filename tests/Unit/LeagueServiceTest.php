<?php


use App\Models\Game;
use App\Models\League;
use App\Services\ClubService;
use App\Services\LeagueService;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Tests\CreatesApplication;
use Tests\TestCase;

/**
 * This is simple tests fot ClubService. (not full)
 * Class ClubServiceTest
 * @package Tests\Unit
 */
class LeagueServiceTest extends TestCase
{
    use CreatesApplication;

    /**
     * @var LeagueService
     */
    protected LeagueService $object;

    /**
     * @var League|Mockery|(League&\Mockery\LegacyMockInterface)|(League&\Mockery\MockInterface)|\Mockery\LegacyMockInterface|\Mockery\MockInterface
     */
    protected League|Mockery $league;
    /**
     * @var ClubService|Mockery|(ClubService&\Mockery\LegacyMockInterface)|(ClubService&\Mockery\MockInterface)|\Mockery\LegacyMockInterface|\Mockery\MockInterface
     */
    protected ClubService|Mockery $clubService;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();


        $this->league = Mockery::mock(League::class);
        $this->clubService = Mockery::mock(ClubService::class);

        $this->object = new LeagueService($this->league, $this->clubService);
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testCreateLeagueException()
    {
        $this->expectException(ErrorException::class);
        $res = $this->object->createLeague([1,2,3]);

        self::assertIsArray($res);
    }

    /**
     * A basic unit test example.
     * work with:
     * @link LeagueService::addGoals()
     * @link LeagueService::addMatch()
     *
     * @return void
     */
    public function testCreateLeague_is_work()
    {
        $hasMany = Mockery::mock(HasMany::class);
        $game = Mockery::mock(Game::class);
        $game->shouldReceive('offsetExists')->times(33)->andReturn(true);
        $game->shouldReceive('offsetGet')->times(33)->andReturn(true);
        $game->shouldReceive('games')->times(12)->andReturn($hasMany);
        $game->shouldReceive('setAttribute')->times(3)->andReturn(true);
        $hasMany->shouldReceive('create')->times(18)->andReturn($game);



        $this->clubService->shouldReceive('getByIds')->times(1)->andReturn(Collection::make());

        $this->league->shouldReceive('getAttribute')->with('power')->times(48)->andReturn((object) [
            'attack' => 1,
            'strength' => 1,
            'protection' => 1
        ]);

        $this->league->shouldReceive('setAttribute')->times(12)->andReturn(true);
        $this->league->shouldReceive('offsetExists')->times(24)->andReturn(true);
        $this->league->shouldReceive('getAttribute')->with('id')->times(1)->andReturn(true);
        $this->league->shouldReceive('getAttribute')->with('matchGames')->times(1)->andReturn(Collection::make());
        $this->league->shouldReceive('offsetGet')->times(24)->andReturn(true);
        $this->league->shouldReceive('matchGames')->times(6)->andReturn($hasMany);

        $this->clubService->shouldReceive('getInfoByClubId')->times(24)->andReturn($this->league);
        $this->league->shouldReceive('save')->times(1)->andReturn(true);

        $res = $this->object->createLeague([[1,2], [3,4]]);

        self::assertIsArray($res);
        self::assertArrayHasKey('league_id', $res);
        self::assertArrayHasKey('clubs', $res);
        self::assertArrayHasKey('max_weeks', $res);
        self::assertInstanceOf(Collection::class, $res['clubs']);
    }


    /**
     * @return void
     * @throws ReflectionException
     */
    public function testCheckByModule()
    {
        $reflection = new ReflectionClass(LeagueService::class);
        /** @link LeagueService::checkByModule() */
        $method = $reflection->getMethod('checkByModule');
        $method->setAccessible(true);

        $res = $method->invokeArgs($this->object, [2]);
        self::assertTrue($res);
        $res = $method->invokeArgs($this->object, [3]);
        self::assertFalse($res);
        $res = $method->invokeArgs($this->object, [4]);
        self::assertTrue($res);
        $res = $method->invokeArgs($this->object, [5]);
        self::assertFalse($res);
        $res = $method->invokeArgs($this->object, [6]);
        self::assertFalse($res);// This situation where we have 3 losers and 3 winner. And how play ? (Info about this method)
        $res = $method->invokeArgs($this->object, [7]);
        self::assertFalse($res);
        $res = $method->invokeArgs($this->object, [8]);
        self::assertTrue($res);
        $res = $method->invokeArgs($this->object, [9]);
        self::assertFalse($res);
        $res = $method->invokeArgs($this->object, [10]);
        self::assertFalse($res);
    }
}
