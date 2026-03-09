<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\MatiereRequest;
use App\Models\MatierePremiere;
use Exception;
use Illuminate\Http\Request;

class MatiereController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([
            'matieres'=> MatierePremiere::all()
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(MatiereRequest $request)
    {
        try{
            $matiere = MatierePremiere::create($request->validated());
            return response()->json([
                'matiere'=>$matiere,
                'success'=>'Nouvelle matière ajoutée avec succès'
            ],201);
        }catch(Exception $e){
            return response()->json([
                'message' => 'Erreur lors de la création d\'une matière',
                'error' => $e->getMessage()
            ],500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(MatierePremiere $matiere)
    {
       return response()->json([
        'matiere'=> MatierePremiere::with('mouvements')->findOrFail($matiere->id)
       ]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(MatiereRequest $request, MatierePremiere $matiere)
    {
        try{
            $matiere ->update($request->validated());
             return response()->json([
                'matiere'=>$matiere,
                'success'=>'Informations matière mise à jour avec succès'
            ],201);
        }catch(Exception $e){
            return response()->json([
                'message' => 'Erreur lors de la mise à jour des informations de la matiere matière',
                'error' => $e->getMessage()
            ],500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MatierePremiere $matiere)
    {
        if($matiere->mouvements()->count() > 0){
            return response()->json([
                'error' => 'Impossible de supprimer la matière car déjà attribuée.'
            ], 400);
        }

        $matiere->delete();

        return response()->json([
            'success'=> 'Matière supprimée avec succès'
        ]);
    }
}
