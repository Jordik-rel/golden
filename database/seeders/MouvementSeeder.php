<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MouvementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('mouvement_stocks')->insert([
            [
                'matiere_premiere_id'=>1,
                'user_id'=>1,
                'type_production_id'=>1,
                'type_mouvement'=> 'sortie',
                'libelle_mouvement'=> 'libelle_mouvement',
                'fournisseur_id'=> 1,
                'quantite'=> 10,
                'created_at'=> '2026-03-08 09:50:15',
                'updated_at'=> '2026-03-08 09:50:15'
            ]
        ]);
    }
}
