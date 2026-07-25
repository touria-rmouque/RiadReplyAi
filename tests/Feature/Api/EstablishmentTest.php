<?php

namespace Tests\Feature\Api;

use App\Models\Establishment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class EstablishmentTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_list_his_establishments(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        Establishment::factory()->count(3)->create([
            'user_id' => $user->id,
        ]);

        $response = $this->getJson('/api/establishments');

        $response->assertOk();

        $this->assertCount(
            3,
            $response->json('data')
        );
    }

    public function test_user_can_create_establishment(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $response = $this->postJson('/api/establishments', [
            'name' => 'Riad Atlas',
            'type' => 'riad',
            'tone' => 'friendly',
        ]);

        $response->assertCreated();

        $this->assertDatabaseHas('establishments', [
            'name' => 'Riad Atlas',
            'user_id' => $user->id,
        ]);
    }

    public function test_validation_fails_when_name_is_missing(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $response = $this->postJson('/api/establishments', [
            'type' => 'riad',
            'tone' => 'friendly',
        ]);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors('name');
    }

    public function test_user_can_update_his_establishment(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $establishment = Establishment::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->putJson(
            "/api/establishments/{$establishment->id}",
            [
                'name' => 'Nouveau nom',
                'type' => $establishment->type,
                'tone' => $establishment->tone,
            ]
        );

        $response->assertOk();

        $this->assertDatabaseHas('establishments', [
            'id' => $establishment->id,
            'name' => 'Nouveau nom',
        ]);
    }

    public function test_user_cannot_update_establishment_of_another_user(): void
    {
        $owner = User::factory()->create();

        $other = User::factory()->create();

        Sanctum::actingAs($other);

        $establishment = Establishment::factory()->create([
            'user_id' => $owner->id,
        ]);

        $response = $this->putJson(
            "/api/establishments/{$establishment->id}",
            [
                'name' => 'Hack',
                'type' => $establishment->type,
                'tone' => $establishment->tone,
            ]
        );

        $response->assertForbidden();
    }

    public function test_user_can_archive_establishment(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $establishment = Establishment::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->deleteJson(
            "/api/establishments/{$establishment->id}"
        );

        $response->assertOk();

        $this->assertSoftDeleted($establishment);
    }

    public function test_guest_cannot_access_establishments(): void
    {
        $response = $this->getJson('/api/establishments');

        $response->assertUnauthorized();
    }
}