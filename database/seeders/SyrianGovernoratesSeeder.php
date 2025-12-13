<?php

namespace Database\Seeders;

//use Illuminate\Container\Attributes\DB;

use App\Models\Governorate;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
            'Damascus',
            'Rif Dimashq',
            'Aleppo',
            'Homs',
            'Hama',
            'Latakia',
            'Tartus',
            'Idlib',
            'Deir ez-Zor',
            'Raqqa',
            'Hasakah',
            'Daraa',
            'As-Suwayda',
            'Quneitra'
        ];

        foreach ($governorates as $governorate) {
            DB::table('governorates')->insert([
                'GovernoratesName' => $governorate
            ]);
        }
    }
}
