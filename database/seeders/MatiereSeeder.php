<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MatiereSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('matiere_premieres')->insert([
            [
                "libelle_matiere"=> "Cacao",
                "unite"=> "kg",
                "seuil_min"=> 200,
                "seuil_alerte"=> 100,
                "quantite"=> 400
            ],
            [
                "libelle_matiere"=> "Soja",
                "unite"=> "kg",
                "seuil_min"=> 500,
                "seuil_alerte"=> 250,
                "quantite"=> 100
            ],
            [
                "libelle_matiere"=> "Mais",
                "unite"=> "kg",
                "seuil_min"=> 450000,
                "seuil_alerte"=> 150000,
                "quantite"=> 800000
            ]
        ]);
    }
}
