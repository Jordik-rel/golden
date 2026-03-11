<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\MouvementRequest;
use App\Models\MatierePremiere;
use App\Models\MouvementStock;
use App\Services\QuantityServices;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MouvementController extends Controller
{

    //  QuantityServices $quantities;
    public function __construct(public QuantityServices $quantities)
    {
        // $this->quantities = $quantities;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([
            'mouvements'=> MouvementStock::with('matiere','user','type','fournisseur')->get()
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(MouvementRequest $request)
    {
        $validation = $request->validated();     
        $matiere = MatierePremiere::where('id',$validation['matiere_premiere_id'])->first();
        // $date_planning = Auth::user()->plannings()->whereDate('jour_travail', Carbon::today())->first();

        // if(!$date_planning->isSameDay(Carbon::now())){
        //     return response()->json([
        //         'errror'=> 'Vous ne pouvez pas éffectuer d\'action à ce jour'
        //     ]);
        // }

        if($validation['type_mouvement'] === 'sortie' && $matiere->quantite < $validation['quantite']){
            return response()->json([
                'error' => 'Stock trop limité pour éffectuer ce genre d\'action'
            ]);
        }

        $mouvement = MouvementStock::create($validation);

        // calcul_quantite($matiere);
        $new_stock = $this->quantities->calcul_quantite($matiere);

        return response()->json([
            'mouvement'=>$mouvement,
            'success'=>'Nouveau mouvement éffectué avec succès',
            'stock'=>$new_stock
        ],201);
        
    }

    /**
     * Display the specified resource.
     */
    public function show(MouvementStock $mouvement)
    {
        return response()->json([
            'mouvement'=> MouvementStock::with('matiere','user','type','fournisseur')->findOrFail($mouvement->id)
        ]);
    }

    public function get_mouvement_by_date(Request $request){
        
    } 

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
