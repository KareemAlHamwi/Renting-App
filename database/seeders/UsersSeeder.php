<?php

namespace Database\Seeders;

use App\Models\User\Person;
use App\Models\User\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use function Symfony\Component\Clock\now;

class UsersSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {
        User::factory()->create([
            'phone_number' => '0982700331',
            'username' => 'admin',
            'password' => Hash::make('Admin@123'),
            'verified_at' => now(),
            'role' => 1,
            'person_id' => Person::factory()->create([
                'first_name' => 'The',
                'last_name' => 'Admin',
                'birthdate' => '2004-05-22',
                'personal_photo' => 'test',
                'id_photo' => 'test',
                'created_at' => now(),
                'updated_at' => now(),
            ])->id,
        ]);

        User::factory(19)->create();
    }
}
