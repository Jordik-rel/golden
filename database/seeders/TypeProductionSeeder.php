<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TypeProductionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('type_productions')->insert([
            [
                'libelle_type_production' => 'Provende Porc'
            ],
            [
                'libelle_type_production' => 'Provende Poussin'
            ],
            [
                'libelle_type_production' => 'Fertilisan Sol'
            ]
        ]);
    }
}
