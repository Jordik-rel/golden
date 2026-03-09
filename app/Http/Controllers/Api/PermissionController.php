<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use Exception;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
         return response()->json([
            'permissions' => Permission::all()
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'liste_permission'=> ['required','string','regex:/^(?!.*(.)\1{2,})(?=.*\pL)[\pL\s\.\,\?\'\"\-]{2,}$/u']
        ]);

        try{
            $permission = Permission::create($data);
            return response()->json([
                'permission'=> $permission,
                'success'=>'Nouvelle permision ajoutée'
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
    public function show(Permission $permission)
    {
        return response()->json([
            'permission'=> Permission::withcount('role')->findOrFail($permission->id)
        ]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Permission $permission)
    {
        $data = $request->validate([
            'liste_permission'=> ['required','string','regex:/^(?!.*(.)\1{2,})(?=.*\pL)[\pL\s\.\,\?\'\"\-]{2,}$/u']
        ]);

        try{
            $permission->update($data);
            return response()->json([
                'permission'=> $permission,
                'success'=>'Permission modifié avec succès'
            ],201);
        }catch(Exception $e){
            return response()->json([
                'message' => 'Erreur lors de la mise à jour',
                'error' => $e->getMessage()
            ],500);
        }
    }

    // public function link_permission(Request $request)
    // {
    //     $data = $request->validate([
    //         'role'=>['required','exists:roles'],
    //         'permission'=>['required','exists:permissions']
    //     ]);
    // }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Permission $permission)
    {
        if($permission->role()->count() > 0){
             return response()->json([
                'error' => 'Impossible de supprimer la permission car déjà attribuée.'
            ], 400);
        }

        $permission->role()->detach();
        $permission->delete();

        return response()->json([
            'success'=> 'Permission supprimée avec succès'
        ]);
    }
}
