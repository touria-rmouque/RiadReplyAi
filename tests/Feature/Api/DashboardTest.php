<?php

namespace Tests\Feature\Api;

use App\Models\Establishment;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_access_dashboard(): void
    {
        $user = User::factory()->create();

        $establishment = Establishment::factory()->create([
            'user_id' => $user->id,
        ]);

        $user->update([
            'current_establishment_id' => $establishment->id,
        ]);

        Sanctum::actingAs($user);

        Review::factory()->count(5)->create([
            'establishment_id' => $establishment->id,
        ]);

        $response = $this->getJson('/api/dashboard');

        $response
            ->assertOk()
            ->assertJsonStructure([
                'data' => [
                    'needs_setup',
                    'establishment',
                    'stats',
                    'top_tags',
                    'recent_reviews',
                ],
            ]);
    }

    public function test_dashboard_requires_authentication(): void
    {
        $response = $this->getJson('/api/dashboard');

        $response->assertUnauthorized();
    }

    public function test_dashboard_returns_needs_setup_when_no_establishment_exists(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $response = $this->getJson('/api/dashboard');

        $response
            ->assertOk()
            ->assertJsonPath('data.needs_setup', true);
    }

    public function test_dashboard_returns_establishment_information(): void
    {
        $user = User::factory()->create();

        $establishment = Establishment::factory()->create([
            'user_id' => $user->id,
        ]);

        $user->update([
            'current_establishment_id' => $establishment->id,
        ]);

        Sanctum::actingAs($user);

        $response = $this->getJson('/api/dashboard');

        $response
            ->assertOk()
            ->assertJsonFragment([
                'id' => $establishment->id,
                'name' => $establishment->name,
            ]);
    }
}