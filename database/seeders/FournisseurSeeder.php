<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FournisseurSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('fournisseurs')->insert([
            [
                'libelle_fournisseur' => ' Marce de Rosse',
                'telephone' => '0154678990'
            ],
            [
                'libelle_fournisseur' => ' Gérard',
                'telephone' => '0155678990'
            ],
        ]);
    }
}
