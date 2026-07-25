<?php
namespace Database\Seeders;
use App\Models\Establishment;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        foreach (self::tags() as $slug => $label) {
            Tag::firstOrCreate(['slug' => $slug], ['label' => $label]);
        }

        $user = User::factory()->create([
            'name'  => 'Gérant Démo',
            'email' => 'demo@riadreply.test',
        ]);

        Establishment::create([
            'user_id' => $user->id,
            'name'    => 'Riad Al Yasmine',
            'type'    => 'riad',
            'tone'    => 'friendly',
        ]);
    }

    public static function tags(): array
    {
        return [
            'cleanliness' => 'Propreté',
            'staff'       => 'Personnel',
            'breakfast'   => 'Petit-déjeuner',
            'food'        => 'Cuisine',
            'noise'       => 'Bruit',
            'location'    => 'Emplacement',
            'room'        => 'Chambre',
            'price'       => 'Rapport qualité/prix',
            'wifi'        => 'WiFi',
            'service'     => 'Service',
            'atmosphere'  => 'Ambiance',
            'decoration'  => 'Décoration',
        ];
    }
}
