<?php
namespace Database\Factories;
use App\Models\Establishment;
use App\Models\Review;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReviewFactory extends Factory
{
    protected $model = Review::class;
    public function definition(): array {
        $sentiment = fake()->randomElement(['positive', 'neutral', 'negative']);
        return [
            'establishment_id' => Establishment::factory(),
            'raw_text'         => fake()->paragraph(3),
            'response_text'    => fake()->paragraph(2),
            'sentiment'        => $sentiment,
            'language'         => fake()->randomElement(['fr', 'en', 'es', 'ar']),
            'is_flagged'       => $sentiment === 'negative',
            'status'           => 'replied',
            'rating'           => fake()->numberBetween(1, 5),
        ];
    }
}
