<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\InventaireRequest;
use App\Mail\InventaireCreation;
use App\Models\DetailInventaire;
use App\Models\Inventaire;
use App\Models\MatierePremiere;
use App\Models\MouvementStock;
use App\Services\QuantityServices;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class InventaireController extends Controller
{

    protected QuantityServices $quantities;
    public function __construct(QuantityServices $quantities)
    {
        $this->quantities = $quantities;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        return response()->json([
            'inventaires'=> Inventaire::with('details.matiere')->where('user_id',$user->id)->get()
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(InventaireRequest $request)
    {
        $invents = Inventaire::where('etat','en_cours')->get();
        try{
            // if(!is_null($invents)){
            //     return response()->json([
            //         'error' => 'Veuillez terminer votre inventaire avant d\'en lancer un nouveau'
            //     ]);
            // }
            $inventaire = Inventaire::create($request->validated());
            Mail::to(Auth::user()->email)->send(new InventaireCreation($inventaire,Auth::user()));
            return response()->json([
                'inventaire'=>$inventaire,
                'success'=>'Vous venez de lancer la création d\'un inventaire'
            ],201);
        }catch(Exception $e){
            return response()->json([
                'message' => 'Erreur lors de la création d\'un inventaire',
                'error' => $e->getMessage()
            ],500);
        }
    }

    public function start(Inventaire $inventaire)
    {
        $exits = Inventaire::where('etat','en_cours')->exists();
        if($exits){
            return response()->json([
                'error'=> 'Impossible de lancer un inventaire si un autre est en cours. Veuillez bien vouloir faire diligence et finir l\'inventaire en cours'
            ]);
        }

        if(in_array($inventaire->etat, ['en_cours', 'terminé', 'annulé'])){
            return response()->json([
                'error'=> 'L\'inventaire a dèjà débuté ou est terminé impossible de le relancer '
            ]);
        }
        
        $inventaire->etat = 'en_cours';
        $inventaire->save();
        return response()->json([
            'success'=> 'Vous venez de lancer votre inventaire'
        ]);
    }

    public function end_inventaire(Inventaire $inventaire)
    {
        if(in_array($inventaire->etat, ['en_attente', 'terminé', 'annulé'])){
            return response()->json([
                'error'=> 'Impossible de mettre fin à cet inventaire car son état à changer'
            ]);
        } 

        $details = DetailInventaire::where('inventaire_id',$inventaire->id)->get();

        if($inventaire->avec_correction_stock){
            foreach($details as $detail){
                if($detail->ecart != 0){
                    $matiere = MatierePremiere::findOrFail($detail->matiere_premiere_id);
                    $data = [
                        'matiere_premiere_id' => $matiere->id,
                        'user_id'=> Auth::id(),
                        'type_mouvement'=> 'ajustement',
                        'libelle_mouvement' => 'Ajustement de l\'écart pour la matiere '.$matiere->libelle_matiere,
                        'quantite' => $detail->ecart
                    ];
                    $mouvement = new MouvementStock($data);
                    $mouvement->save();

                    $new_stock = $this->quantities->calcul_quantite($matiere);

                    // $matiere->quantite = $mouvement->quantite;
                    // $matiere->save();
                }
            }
        }

        $inventaire->update([
            'etat' => 'terminé',
            'date_fin' => now()
        ]);

        return response()->json([
            'success' => 'Inventaire achevé avec succès',
            'stock'=>$new_stock
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Inventaire $inventaire)
    {
        return response()->json([
            'inventaire' => Inventaire::with('user','details')->where('user_id',Auth::id())->find($inventaire)
        ]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(InventaireRequest $request, Inventaire $inventaire)
    {
        $data = $request->validated();
        try{
            if(in_array($inventaire->etat, ['en_cours', 'terminé', 'annulé'])){
                return response()->json([
                    'error'=> 'Impossible de modifier l\'inventaire car son état à changer'
                ]);
            } 
            if(Carbon::parse($inventaire->date_debut)->isAfter($inventaire->date_fin)){
                return response()->json([
                    'error' => ' Veuillez insérer la date de fin'
                ]);
            }
            $inventaire->update($data);
            return response()->json([
                'succcess'=>'Vous venez de modifier les informations liées à la programmation de votre inventaire'
            ]);
        }catch(Exception $e){
            return response()->json([
                'message' => 'Erreur lors de la modification de votre inventaire',
                'error' => $e->getMessage()
            ],500);
        }
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Inventaire $inventaire)
    {
        try{
            if(Auth::id() != $inventaire->user_id){
                 return response()->json([
                    'error'=> 'Vous ne disposer pas des privilèges pour annuler cet inventaire'
                ]);
            }
    
            if(in_array($inventaire->etat, ['en_cours', 'terminé', 'annulé'])){
                return response()->json([
                    'error'=> 'Vous ne pouvez pas supprimer un inventaire déjà lancer'
                ]);
            }

            $inventaire->update([
                'etat'=>'annulé'
            ]);

            return response()->json([
                'succcess'=>'Vous venez d\'annuler votre inventaire'
            ]);

        }catch(Exception $e){
            return response()->json([
                'message' => 'Erreur lors de l\'annulation de votre inventaire',
                'error' => $e->getMessage()
            ],500);
        }
    }

    
}
