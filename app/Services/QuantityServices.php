<?php

namespace App\Services;

use App\Models\MatierePremiere;
use App\Models\MouvementStock;

class QuantityServices
{
    public function calcul_quantite(MatierePremiere $matiere)
    {
        $mouvements = MouvementStock::where('matiere_premiere_id',$matiere->id)->get(); //whereIn('type_mouvement',['entree','sortie'])
        $out_quantity = 0;
        $in_quantity = 0;
        $ecart_quantity = 0;

        foreach($mouvements as $mouvement){
            if($mouvement->type_mouvement === 'sortie'){
                $out_quantity += $mouvement->quantite; 
            }elseif ($mouvement->type_mouvement === 'entree'){
                $in_quantity += $mouvement->quantite; 
            }else{
                $ecart_quantity += $mouvement->quantite;
            }
        }

        $stock = $in_quantity +$ecart_quantity - $out_quantity ;

        $matiere->quantite = $stock;
        $matiere->save();
        return $matiere;
        // return response()->json([
        //     'sortie' => $out_quantity,
        //     'entree' => $in_quantity,
        //     'ajustement' => $ecart_quantity,
        //     'stock' => $stock,
        //     'matiere' => $matiere
        // ]);
    }
}
