<?php
namespace Database\Factories;
use App\Models\Establishment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class EstablishmentFactory extends Factory
{
    protected $model = Establishment::class;
    public function definition(): array {
        return [
            'user_id' => User::factory(),
            'name'    => fake()->randomElement(['Riad', 'Dar', 'Maison']).' '.fake()->lastName(),
            'type'    => fake()->randomElement(['riad', 'restaurant']),
            'tone'    => fake()->randomElement(['friendly', 'formal', 'enthusiastic']),
        ];
    }
}
