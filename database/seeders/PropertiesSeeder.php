<?php

namespace Database\Seeders;

use App\Models\Property\Property;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PropertiesSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {
        Property::factory(50)->create();
    }
}
