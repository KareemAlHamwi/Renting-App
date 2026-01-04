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

        for ($i = 0; $i < 300; $i++) {
            Property::query()->create([
                'title'            => $faker->sentence(3),
                'description'      => $faker->paragraphs(2, true),
                'address'          => $faker->streetAddress,
                'rent'             => $faker->numberBetween(20, 200),
                'overall_reviews'  => $faker->randomFloat(2, 0, 5),
                'reviewers_number' => $faker->numberBetween(0, 150),
                'verified_at'      => $faker->boolean(80) ? now()->subDays($faker->numberBetween(0, 365)) : null,
                'governorate_id'   => $faker->randomElement($govIds),
                'user_id'          => $faker->randomElement($userIds),
            ]);
        }
    }
}
