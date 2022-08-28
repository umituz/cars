<?php

namespace Tests\Feature\Service;

use App\Models\Service;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\Feature\BaseTestCase;

/**
 * Class ServiceV1ControllerTest
 * @package Tests\Feature\Service
 * @coversDefaultClass \App\Http\Controllers\Api\Service\ServicesV1Controller
 */
class ServiceV1ControllerTest extends BaseTestCase
{
    use RefreshDatabase;

    /**
     * @test
     * @covers ::index
     * @covers ::__construct
     */
    public function it_should_return_all_services()
    {
        $user = $this->createUser(123456);
        $this->createService(10);

        Sanctum::actingAs($user, ['*']);

        $response = $this->getJson('api/v1/services');
        $response->assertStatus(200);
        $response->assertJsonCount(10, 'data');
    }

    /**
     * @test
     */
    public function it_should_not_return_services_with_wrong_credentials()
    {
        $response = $this->getJson('api/v1/services');

        $response->assertStatus(401);
        $response->assertJsonPath('message', 'Unauthenticated.');
    }

    /**
     * @param int $count
     * @return Collection|HasFactory|Model|mixed
     */
    private function createService(int $count = 1): mixed
    {
        return Service::factory()->count($count)->create();
    }
}
