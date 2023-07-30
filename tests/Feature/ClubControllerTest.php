<?php


use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClubControllerTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testIndex()
    {
        $response = $this->get('/api/v1/clubs');
        $result = json_decode($response->getContent(), true);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('data', $result);
        $response->assertStatus(200);
    }
}
