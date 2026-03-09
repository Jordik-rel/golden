<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user_role = Role::create([
            'libelle_role' => 'user'
        ]);

        $admin_role = Role::create([
            'libelle_role' => 'administrateur'
        ]);

        $admin_role->permission()->sync(Permission::all()->pluck('id'));

        $user_role->permission()->sync(Permission::where('id',1)->first()->pluck('id'));

    }
}
