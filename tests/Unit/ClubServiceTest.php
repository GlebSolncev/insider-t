<?php

namespace Tests\Unit;

use App\Models\Club;
use App\Services\ClubService;
use ErrorException;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Mockery;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use Tests\CreatesApplication;

/**
 * This is simple tests fot ClubService. (not full)
 * Class ClubServiceTest
 * @package Tests\Unit
 */
class ClubServiceTest extends TestCase
{
    use CreatesApplication;

    /**
     * @var ClubService
     */
    protected ClubService $object;

    /**
     * @var Club
     */
    protected Club $club;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->club = Mockery::mock(Club::class);
        $this->object = new ClubService($this->club);

        parent::setUp();
    }


    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testGetAll()
    {
        $this->club->shouldReceive('all')->times(1)->andReturn(Collection::make());
        $res = $this->object->getAll();
        Mockery::close();

        self::assertInstanceOf(Collection::class, $res);
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testGetByIds()
    {
        $builder = Mockery::mock(Builder::class);
        $this->club->shouldReceive('whereIn')->times(1)->andReturn($builder);
        $builder->shouldReceive('get')->times(1)->andReturn(Collection::make());
        $res = $this->object->getByIds([]);
        Mockery::close();

        self::assertInstanceOf(Collection::class, $res);
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testGetById_is_null()
    {
        $this->club->shouldReceive('find')->times(1)->andReturn(null);
        $res = $this->object->getById(1);

        self::assertNull($res);
        Mockery::close();
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testGetInfoByClubIdException()
    {
        $this->expectException(ErrorException::class);
        $this->club->shouldReceive('find')->times(1)->andReturn(null);
        $this->object->getInfoByClubId(1);

        Mockery::close();
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testGetRandomPlayers()
    {
        $clubM = Mockery::mock(Club::class);
        $this->object = new ClubService($clubM);
        $reflection = new ReflectionClass(ClubService::class);
        /** @link ClubService::getRandomPlayers() */
        $method = $reflection->getMethod('getRandomPlayers');
        $method->setAccessible(true);
        $club2 = Mockery::mock(Club::class);
        $collection = Mockery::mock(Collection::class);
        $club2->shouldReceive('getAttribute')->with('players')->times(1)->andReturn($collection);
        $collection->shouldReceive('count')->times(1)->andReturn(0);
        $method->invokeArgs($this->object, [$club2]);
//        Mockery::close();

        self::assertEquals($method->getReturnType()->getName(), Collection::class);
    }
}
