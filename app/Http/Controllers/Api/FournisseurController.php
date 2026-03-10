<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Fournisseur;
use Exception;
use Illuminate\Http\Request;

class FournisseurController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([
            'fournisseurs' => Fournisseur::all()
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'libelle_fournisseur'=>['required','string','regex:/^(?!.*(.)\1{2,})(?=.*\pL)[\pL\s\.\,\?\'\"\-]{2,}$/u'],
            'telephone'=>['required','size:10','string']
        ]);
        try{
            $fournisseur = Fournisseur::create($data);
            return response()->json([
                'fournisseur'=>$fournisseur,
                'success'=>' Nouveau Fournisseur crée avec succès'
            ],201);
        }catch(Exception $e){
            return response()->json([
                'message' => 'Erreur lors de l\'ajout d\'un fournisseur',
                'error' => $e->getMessage()
            ],500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Fournisseur $fournisseur)
    {
        return response()->json([
            'fournisseur'=>Fournisseur::with('mouvements')->withCount('mouvements')->findOrFail($fournisseur->id)
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Fournisseur $fournisseur)
    {
        $data = $request->validate([
            'libelle_fournisseur'=>['required','string','regex:/^(?!.*(.)\1{2,})(?=.*\pL)[\pL\s\.\,\?\'\"\-]{2,}$/u'],
            'telephone'=>['required','size:10','string']
        ]);
        try{
            $fournisseur->update($data);
            return response()->json([
                'fournisseur'=>$fournisseur,
                'success'=>' Informations fournisseur mise à jour avec succès'
            ],201);
        }catch(Exception $e){
            return response()->json([
                'message' => 'Erreur lors de la mise à jour des informations du fournisseur',
                'error' => $e->getMessage()
            ],500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Fournisseur $fournisseur)
    {
        if($fournisseur->mouvements()->count() > 0){
            return response()->json([
                'error' => 'Impossible de supprimer le fournisseur car déjà attribuée.'
            ], 400);
        }

        $fournisseur->delete();

        return response()->json([
            'success'=> 'Fournisseur supprimée avec succès'
        ]);
    }
}
