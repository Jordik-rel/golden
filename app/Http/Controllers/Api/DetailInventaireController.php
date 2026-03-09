<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\DetailInventaireRequest;
use App\Http\Requests\UpdateDetailInventaireRequest;
use App\Models\DetailInventaire;
use App\Models\Inventaire;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DetailInventaireController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([
            'details'=> DetailInventaire::with('matiere.mouvements','inventaire','user')->where('user_id',Auth::id())->get()
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(DetailInventaireRequest $request,Inventaire $inventaire)
    {
        // dd($request->validated());
        $data = $request->validated();
        // dd(Inventaire::where('id',$data['inventaire_id'])->first()->etat);
        try{
            // if(!Carbon::parse($inventaire->date_fin)->equalTo(Carbon::now())){
            //     return response()->json([
            //         'error' => 'Vous ne pouvez plus ajouter de détail à cet inventaire'
            //     ]);
            // }

            // dd($inventaire->etat);
            
            if($inventaire->etat != 'en_cours'){
                return response()->json([
                    'error' => 'Vous ne pouvez pas ajouter de détail à cet inventaire pour le moment'
                ],403);
            }

            $details = new DetailInventaire($request->validated());
            $details->ecart = $details->stock_reel  - $details->stock_theorique;
            $details->save();
            return response()->json([
                'success' => 'Nouveau détail ajouté',
                'detail'=>$details
            ],201);
        }catch(Exception $e){
            return response()->json([
                'message' => 'Erreur lors de l\'ajout d\'un détail à votre inventaire',
                'error' => $e->getMessage()
            ],500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Inventaire $inventaire,DetailInventaire $detail)
    {
        return response()->json([
            'detail' => DetailInventaire::with('matiere.mouvements','inventaire','user')->findOrFail($detail->id)
        ]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDetailInventaireRequest $request,Inventaire $inventaire, DetailInventaire $detail)
    {
        try{
            if(!Carbon::parse($inventaire?->date_fin)->isBefore(Carbon::now())){
                return response()->json([
                    'error' => 'Vous ne pouvez plus modifier le détail associer à cet inventaire'
                ]);
            }
            if(in_array($inventaire->etat, ['en_attente', 'terminé', 'annulé'])){
                return response()->json([
                    'error'=> 'Impossible de modifier les détails de l\'inventaire car son état à changer'
                ]);
            } 
            $data = $request->validated();
            $data['ecart'] = $data['stock_reel'] - $data['stock_theorique'] ;
            $detail->update($data);
            
            return response()->json([
                'success' => 'Les informations de votre détail viennent d\'être modifié',
                'detail'=>$detail
            ],201);
        }catch(Exception $e){
            return response()->json([
                'message' => 'Erreur lors de la modification des détails associer à votre inventaire',
                'error' => $e->getMessage()
            ],500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DetailInventaire $detail,Inventaire $inventaire)
    {
        try{
            if(!Carbon::parse($inventaire->date_fin)->isBefore(Carbon::now())){
                return response()->json([
                    'error' => 'Vous ne pouvez plus modifier le détail associer à cet inventaire'
                ]);
            }
            $detail->delete();
            return response()->json([
                'success' => 'Les informations de votre détail viennent d\'être supprimé',
            ],201);
        }catch(Exception $e){
            return response()->json([
                'message' => 'Erreur lors de la suppression des détails associer à votre inventaire',
                'error' => $e->getMessage()
            ],500);
        }
    }
}
