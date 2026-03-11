<?php

namespace App\Http\Controllers;

use App\Models\ProductionJournaliere;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\TypeProduction;
use App\Http\Requests\ProductionJournaliereRequest;

class ProductionJournaliereController extends Controller
{
    public function index()
    {
        return response()->json([
            'productions' =>  ProductionJournaliere::with('user','type')->where('user_id',Auth::id())->get()
        ]);
    }

    public function store(ProductionJournaliereRequest $request)
    {
        $data = $request->validated();
        $day_production_type = ProductionJournaliere::where('user_id', Auth::id())->where('type_production_id',$data['type_production_id'])->whereDate('date_production',$data['date_production'])->count();
        if($day_production_type >= 2){
            return response()->json([
                'error' => 'Vous avez atteint votre cota de rapport pour la journée'
            ],422);
        }

        $production = ProductionJournaliere::create($data);
        return response()->json([
            'success' => 'Golden Stock vous remercie pour le travail de ce jour',
            'production'=> $production
        ]);
    }
}
