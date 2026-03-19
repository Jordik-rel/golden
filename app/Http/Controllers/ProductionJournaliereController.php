<?php

namespace App\Http\Controllers;

use App\Models\ProductionJournaliere;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\TypeProduction;
use App\Http\Requests\ProductionJournaliereRequest;
use Barryvdh\DomPDF\Facade\Pdf;

use function Symfony\Component\Clock\now;

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
        $done_off = [];
        $done_on = [];
        foreach($data['productions'] as $production){
            $day_production_type = ProductionJournaliere::where('user_id', Auth::id())->where('type_production_id',$production['type_production_id'])->whereDate('date_production',$data['date_production'])->count();
            if($day_production_type >= 2){
                // return response()->json([
                //     'error' => 'Vous avez atteint votre cota de rapport pour la journée'
                // ],422);
                $done_off[] = $production;
                continue;
            }

            $daily_production = ProductionJournaliere::create([
                'user_id' => $data['user_id'],
                'date_production'=> $data['date_production'],
                'type_production_id' => $production['type_production_id'],
                'quantite' => $production['quantite']
            ]);

            $done_on[] = $daily_production; 
            
        }

        return response()->json([
            'success' => 'Golden Stock vous remercie pour le travail de ce jour',
            'production'=> $done_on
        ]);
    }

    public function generate_pdf(string $day)
    {
        $productions = ProductionJournaliere::with('type.mouvements.matiere')->where('user_id',Auth::id())->where('date_production',$day)->get();

        $pdf = Pdf::loadView('rapport',[
            'productions'=>$productions,
            'generate_date'=> now()->format('d/m/Y H:i')
        ])->setOptions([
            'defaultFont'   => 'DejaVu Sans',
            'isRemoteEnabled' => true,
            'isHtml5ParserEnabled' => true,
        ]);

        $filename = 'rapport_production_' . now() . '_' . Auth::id() . '.pdf';

        return $pdf->download($filename);
    }
}
