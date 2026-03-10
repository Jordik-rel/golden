<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use function Pest\Laravel\json;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([
            // 'roles' => Role::all()
            'roles' => Role::with('permission')->get()
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'libelle_role' => ['required','string','regex:/^(?!.*(.)\1{2,})(?=.*\pL)[\pL\s\.\,\?\'\"\-]{2,}$/u'],
            'permissions' =>['required','array'],
            'permissions*'=>[Rule::exists(Permission::class,'id')]
        ]);

        try{
            $role = Role::create([
                'libelle_role'=>$data['libelle_role']
            ]);
            $role->permission()->sync($data['permissions']);
            return response()->json([
                'role'=>Role::with('permission')->findOrFail($role->id),
                'success'=>' Nouveau rôle crée avec succès'
            ],201);
        }catch(Exception $e){
            return response()->json([
                'message' => 'Erreur lors de la création',
                'error' => $e->getMessage()
            ],500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        return response()->json([
            'role'=>Role::with('permission')->findOrFail($role->id),
            'user_count'=> Role::withCount('users')->findOrFail($role->id)
        ]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        $data = $request->validate([
            'libelle_role' => ['required','string','regex:/^(?!.*(.)\1{2,})(?=.*\pL)[\pL\s\.\,\?\'\"\-]{2,}$/u'],
            'permissions' =>['required','array'],
            'permissions*'=>[Rule::exists(Permission::class,'id')]
        ]);

        try{
            $role->update([
                'libelle_role'=>$data['libelle_role']
            ]);
            $role->permission()->sync($data['permissions']);
            return response()->json([
                'role'=>Role::with('permission')->findOrFail($role->id),
                'success'=> 'Role modifié avec succès'
            ]);
        }catch(Exception $e){
            return response()->json([
                'error'=> 'Impossible de modfié le role. Erreur : '.$e->getMessage()
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        try{
            if ($role->users()->count() > 0) {
                return response()->json([
                    'error' => 'Impossible de supprimer le rôle car déj attribué.'
                ], 400);
            }
            $role->permission()->detach();
            $role->delete();
            return response()->json([
                'success'=> 'Role supprimé avec succès'
            ]);
        }catch(Exception $e){
            return response()->json([
                'error' =>  'Erreur lors de la suppression'
            ]);
        }
    }
}
