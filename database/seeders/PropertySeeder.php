<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Property\Property;
use App\Models\Property\Governorate;
use App\Models\User\User;
use Faker\Factory as Faker;

class PropertySeeder extends Seeder {
    public function run(): void {
        $faker = Faker::create();

        $userIds = User::query()->pluck('id')->all();
        $govIds  = Governorate::query()->pluck('id')->all();

        // If dependencies are missing, skip to avoid FK errors.
        if (empty($userIds) || empty($govIds)) {
            $this->command?->warn('PropertySeeder skipped: seed users and governorates first.');
            return;
        }

        for ($i = 0; $i < 35; $i++) {
            $randomDate = $faker->boolean(80) ? now()->subDays($faker->numberBetween(0, 365)) : null;
            Property::query()->create([
                'title' => $faker->randomElement([
                    'Traditional Damascene Courtyard House in the Old City',
                    'Historic Aleppo Stone House with Ottoman Architecture',
                    'Cozy Hama House Overlooking the Orontes River',
                    'Syrian Coastal Villa in Latakia with Mediterranean Views',
                    'Spacious Palmyra-style House with Arched Doorways',
                    'Modern Syrian Apartment in Damascus with Traditional Touches',
                    'Authentic Bosra Stone House Near Roman Theater',
                    'Rural Idlib Country Home with Olive Groves',
                    'Luxury Damascus Suburb Villa with Fountain Garden',
                    'Compact Tartus Seaside Residence with Blue Shutters'
                ]),

                'description' => $faker->randomElement([
                    'This authentic Damascene house features a beautiful central courtyard with a traditional fountain, surrounded by arched colonnades. The interior boasts intricate geometric tilework (zellige), carved wooden ceilings, and stained glass windows. Located in the historic Bab Touma district, it offers easy access to the Umayyad Mosque and lively souks. The house has been carefully restored to preserve its historic character while adding modern amenities.',

                    'Built from distinctive Aleppo yellow stone, this historic home showcases classic Ottoman architecture with high vaulted ceilings, decorative masonry, and a large liwan reception area. The property includes a shaded rooftop terrace perfect for evening gatherings with views of the citadel. Original features like the stone fireplace and hand-carved wooden doors have been meticulously maintained.',

                    'Nestled along the banks of the Orontes River, this charming Hama residence is known for its traditional waterwheel-inspired design. The house features cool stone interiors that provide natural insulation from summer heat, with spacious rooms arranged around a small interior garden. Enjoy peaceful afternoons listening to the gentle sound of nearby norias while sipping mint tea on the balcony.',

                    'Perched on the Mediterranean coast, this Latakia villa combines Syrian architectural elements with coastal living. Whitewashed walls, blue-trimmed windows, and a rooftop terrace catch sea breezes. The property includes a small citrus garden and outdoor seating area perfect for enjoying fresh seafood while watching sunset over the sea.',

                    'Inspired by ancient Palmyrene architecture, this spacious home features dramatic arched doorways, stone columns, and expansive rooms. The design incorporates passive cooling techniques used in desert climates, with thick stone walls and strategically placed windows. A central atrium provides natural light throughout while maintaining privacy.',

                    'Located in modern Damascus, this apartment blends contemporary comforts with Syrian design traditions. Features include a majlis-style living area with floor cushions, mashrabiya screen details, and local artwork. The updated kitchen features modern appliances alongside traditional Syrian tile accents. Perfect for those wanting urban convenience with cultural character.',

                    'Carved from black basalt, this unique Bosra home showcases the region\'s distinctive volcanic stone construction. The house stays naturally cool in summer and warm in winter. Located steps from the UNESCO World Heritage Roman theater, it offers a truly historic living experience. Interior arches and vaulted ceilings create a majestic atmosphere.',

                    'Surrounded by olive and pistachio orchards, this Idlib country home offers peaceful rural living. The simple stone structure features a large central room for family gatherings, an outdoor bread oven (tannour), and a shaded veranda perfect for enjoying countryside views. Ideal for those seeking connection to Syria\'s agricultural traditions.',

                    'This elegant villa in Damascus\'s western suburbs features a stunning courtyard garden with fruit trees, rose bushes, and a central fountain. Marble floors, crystal chandeliers, and ornate plasterwork reflect Syrian craftsmanship at its finest. The property includes separate reception areas for men and women, following traditional social customs.',

                    'A charming blue-shuttered home in coastal Tartus, featuring a compact but efficient layout typical of seaside Syrian architecture. The bright interior maximizes natural light with large windows facing the sea. Built with local sandstone, the house includes a small courtyard for drying fishing nets and outdoor cooking in the summer months.'
                ]),
                'address'          => $faker->streetAddress,
                'rent'             => $faker->numberBetween(20, 200),
                'overall_reviews'  => $faker->randomFloat(2, 0, 5),
                'reviewers_number' => $faker->numberBetween(0, 150),
                'verified_at'      => $randomDate,
                'published_at'      => $randomDate,
                'governorate_id'   => $faker->randomElement($govIds),
                'user_id'          => $faker->randomElement($userIds),
            ]);
        }
    }
}
