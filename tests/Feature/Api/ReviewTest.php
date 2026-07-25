<?php

namespace Tests\Feature\Api;

use App\Enums\ReviewStatus;
use App\Jobs\AnalyseReviewJob;
use App\Models\Establishment;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ReviewTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_list_reviews(): void
    {
        $user = User::factory()->create();

        $establishment = Establishment::factory()->create([
            'user_id' => $user->id,
        ]);

        $user->update([
            'current_establishment_id' => $establishment->id,
        ]);

        Sanctum::actingAs($user);

        Review::factory()->count(3)->create([
            'establishment_id' => $establishment->id,
        ]);

        $response = $this->getJson('/api/reviews');

        $response
            ->assertOk()
            ->assertJsonCount(3, 'data');
    }

    public function test_user_can_create_review(): void
    {
        Queue::fake();

        $user = User::factory()->create();

        $establishment = Establishment::factory()->create([
            'user_id' => $user->id,
        ]);

        $user->update([
            'current_establishment_id' => $establishment->id,
        ]);

        Sanctum::actingAs($user);

        $response = $this->postJson('/api/reviews', [
            'raw_text' => 'Excellent séjour, personnel très accueillant et chambres impeccables. Nous reviendrons avec plaisir.',
            'rating' => 5,
        ]);

        $response->assertCreated();

        $this->assertDatabaseHas('reviews', [
            'establishment_id' => $establishment->id,
            'rating' => 5,
        ]);

        Queue::assertPushed(AnalyseReviewJob::class);
    }

    public function test_validation_fails_when_review_text_is_missing(): void
    {
        $user = User::factory()->create();

        $establishment = Establishment::factory()->create([
            'user_id' => $user->id,
        ]);

        $user->update([
            'current_establishment_id' => $establishment->id,
        ]);

        Sanctum::actingAs($user);

        $response = $this->postJson('/api/reviews', [
            'rating' => 5,
        ]);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors('raw_text');
    }

    public function test_user_can_show_review(): void
    {
        $user = User::factory()->create();

        $establishment = Establishment::factory()->create([
            'user_id' => $user->id,
        ]);

        Sanctum::actingAs($user);

        $review = Review::factory()->create([
            'establishment_id' => $establishment->id,
        ]);

        $response = $this->getJson("/api/reviews/{$review->id}");

        $response
            ->assertOk()
            ->assertJsonFragment([
                'id' => $review->id,
            ]);
    }

    public function test_user_can_mark_review_as_replied(): void
    {
        $user = User::factory()->create();

        $establishment = Establishment::factory()->create([
            'user_id' => $user->id,
        ]);

        Sanctum::actingAs($user);

        $review = Review::factory()->create([
            'establishment_id' => $establishment->id,
            'status' => ReviewStatus::Pending,
        ]);

        $response = $this->patchJson(
            "/api/reviews/{$review->id}/replied"
        );

        $response->assertOk();

        $this->assertEquals(
            ReviewStatus::Replied,
            $review->fresh()->status
        );
    }

    public function test_user_can_delete_review(): void
    {
        $user = User::factory()->create();

        $establishment = Establishment::factory()->create([
            'user_id' => $user->id,
        ]);

        Sanctum::actingAs($user);

        $review = Review::factory()->create([
            'establishment_id' => $establishment->id,
        ]);

        $response = $this->deleteJson(
            "/api/reviews/{$review->id}"
        );

        $response->assertOk();

        $this->assertDatabaseMissing('reviews', [
            'id' => $review->id,
        ]);
    }

    public function test_guest_cannot_access_reviews(): void
    {
        $response = $this->getJson('/api/reviews');

        $response->assertUnauthorized();
    }

    public function test_user_cannot_view_review_of_another_user(): void
    {
        $owner = User::factory()->create();

        $other = User::factory()->create();

        $establishment = Establishment::factory()->create([
            'user_id' => $owner->id,
        ]);

        $review = Review::factory()->create([
            'establishment_id' => $establishment->id,
        ]);

        Sanctum::actingAs($other);

        $this->getJson("/api/reviews/{$review->id}")
            ->assertForbidden();
    }
}