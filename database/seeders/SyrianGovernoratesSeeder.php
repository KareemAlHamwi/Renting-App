<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class SyrianGovernoratesSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('governorates')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $governorates = [
            'Damascus - دمشق',
            'Rif Dimashq - ريف دمشق',
            'Aleppo - حلب',
            'Homs - حمص',
            'Hama - حماه',
            'Latakia - اللاذقية',
            'Tartus - طرطوس',
            'Idlib - إدلب',
            'Deir ez-Zor - دير الزور',
            'Raqqa - الرقة',
            'Hasakah - الحسكة',
            'Daraa - درعا',
            'As-Suwayda - السويداء',
            'Quneitra - القنيطرة'
        ];

        foreach ($governorates as $governorate) {
            DB::table('governorates')->insert([
                'governorate_name' => $governorate
            ]);
        }
    }
}
