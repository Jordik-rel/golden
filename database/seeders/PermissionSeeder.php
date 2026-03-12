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
                'liste_permission' => 'Gerer matiere'
            ],
            [
                'liste_permission' => 'Gerer fournisseur'
            ],
            [
                'liste_permission' => 'Gerer mouvement'
            ],
            [
                'liste_permission' => 'Gerer production'
            ],
            [
                'liste_permission' => 'Gerer inventaire'
            ],
            [
                'liste_permission' => 'Gerer planning'
            ],
            [
                'liste_permission' => 'Gerer rapport'
            ],
            [
                'liste_permission' => 'Gerer utilisateurs'
            ],
            [
                'liste_permission' => 'Gerer role'
            ],
            [
                'liste_permission' => 'acceder parametre'
            ],
        ]);
    }
}
