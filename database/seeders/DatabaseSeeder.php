<?php

namespace Database\Seeders;

use App\Models\Person;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use function Symfony\Component\Clock\now;

class DatabaseSeeder extends Seeder {
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void {
        User::factory()->create([
            'phone_number' => '0982700331',
            'username' => 'admin0',
            'password' => Hash::make('Admin@123'),
            'verified_at' => now(),
            'role' => 1,
            'person_id' => Person::factory()->create([
                'first_name' => 'Kareem',
                'last_name' => 'AlHamwi',
                'birthdate' => '2004-05-22',
                'personal_photo' => 'test',
                'id_photo' => 'test',
                'created_at' => now(),
                'updated_at' => now(),
            ])->id,
        ]);

        User::factory()->create([
            'phone_number' => '0981024513',
            'username' => 'admin1',
            'password' => Hash::make('Admin@123'),
            'verified_at' => now(),
            'role' => 1,
            'person_id' => Person::factory()->create([
                'first_name' => 'Kinan',
                'last_name' => 'Mohammad',
                'birthdate' => '2004-10-10',
                'personal_photo' => 'test',
                'id_photo' => 'test',
                'created_at' => now(),
                'updated_at' => now(),
            ])->id,
        ]);

        User::factory(18)->create([
            'password' => Hash::make('Admin@123'),
        ]);
    }
}
