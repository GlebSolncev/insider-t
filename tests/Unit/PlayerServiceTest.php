<?php


use App\Models\Player;
use App\Services\PlayerService;
use Illuminate\Support\Collection;
use Tests\TestCase;
use Tests\CreatesApplication;

/**
 * This is simple tests fot ClubService. (not full)
 * Class ClubServiceTest
 * @package Tests\Unit
 */
class PlayerServiceTest extends TestCase
{
    use CreatesApplication;

    /**
     * @var PlayerService
     */
    protected PlayerService $object;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->object = new PlayerService();
    }


    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testGetSquadInfo()
    {
        $res = $this->object->getSquadInfo(Collection::make(Player::query()->limit(5)->get()));

        self::assertIsArray($res);
        self::assertArrayHasKey('strength', $res);
        self::assertArrayHasKey('attack', $res);
        self::assertArrayHasKey('protection', $res);
    }


}
