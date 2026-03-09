<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('permissions')->insert([
            [
                'liste_permission' => 'acceder parametre'
            ],
            [
                'liste_permission' => 'lancer inventaire'
            ],
            [
                'liste_permission' => 'créer matiere'
            ],
            [
                'liste_permission' => 'créer planning'
            ],
            [
                'liste_permission' => 'créer inventaire'
            ],
            [
                'liste_permission' => 'remplir production journaliere'
            ],
        ]);
    }
}
