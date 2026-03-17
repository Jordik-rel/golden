<?php

namespace App\Http\Controllers;

use App\Models\TypeProduction;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;

class TypeProductionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
         return response()->json([
            'types' => TypeProduction::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'libelle_type_production'=>['required','string','regex:/^(?!.*(.)\1{2,})(?=.*\pL)[\pL\s\.\,\?\'\"\-]{2,}$/u']
        ]);
        try{
            $type = TypeProduction::create($data);
            return response()->json([
                'type'=>$type,
                'success'=>' Nouveau type de production crée avec succès'
            ],201);
        }catch(Exception $e){
            return response()->json([
                'message' => 'Erreur lors de l\'ajout d\'un type de production',
                'error' => $e->getMessage()
            ],500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(TypeProduction $type)
    {
        return response()->json([
            'type'=>TypeProduction::with('mouvements','productions')->withCount('mouvements','productions')->findOrFail($type->id)
        ]);
    }

    /**
     * Donner les type de production en fonction de la date mais accompagné des mouvements de type sortie  
     */
    public function get_type_by_date(Request $request)
    {
        $validation = $request->validate([
            'date'=> ['required','date']
        ]);

        $types = TypeProduction::with(['mouvements'=> function ($query){
            $query->where('type_mouvement','sortie');
        }])->whereDate('created_at',Carbon::parse($validation['date'])->format('Y-m-d'))->get();

        return response()->json([
            'types'=>$types
        ]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TypeProduction $type)
    {
        $data = $request->validate([
            'libelle_type_production'=>['required','string','regex:/^(?!.*(.)\1{2,})(?=.*\pL)[\pL\s\.\,\?\'\"\-]{2,}$/u']
        ]);
        try{
            $type->update($data);
            return response()->json([
                'type'=>$type,
                'success'=>' Informations type de production mise à jour avec succès'
            ],201);
        }catch(Exception $e){
            return response()->json([
                'message' => 'Erreur lors de la mise à jour des informations du type de production',
                'error' => $e->getMessage()
            ],500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TypeProduction $type)
    {
        if($type->mouvements()->count() > 0 || $type->productions()->count() > 0){
            return response()->json([
                'error' => 'Impossible de supprimer le type de production car déjà attribuée.'
            ], 400);
        }

        $type->delete();

        return response()->json([
            'success'=> 'Fournisseur supprimée avec succès'
        ]);
    }
}
