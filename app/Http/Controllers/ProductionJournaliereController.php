<?php

namespace App\Http\Controllers;

use App\Models\ProductionJournaliere;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\TypeProduction;
use App\Http\Requests\ProductionJournaliereRequest;
use App\Mail\ProductionJournaliere as MailProductionJournaliere;
use App\Mail\ProductionJournaliereMail;
use App\Models\MouvementStock;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

use function Symfony\Component\Clock\now;

class ProductionJournaliereController extends Controller
{
    public function index()
    {
        return response()->json([
            'productions' =>  ProductionJournaliere::with('user','type')->where('user_id',Auth::id())->get()
        ]);
    }

    // public function store(ProductionJournaliereRequest $request)
    // {
    //     $data = $request->validated();
    //     $done_off = [];
    //     $done_on = [];
    //     foreach($data['productions'] as $production){
    //         $day_production_type = ProductionJournaliere::where('user_id', Auth::id())->where('type_production_id',$production['type_production_id'])->whereDate('date_production',$data['date_production'])->count();
    //         if($day_production_type >= 2){
    //             // return response()->json([
    //             //     'error' => 'Vous avez atteint votre cota de rapport pour la journée'
    //             // ],422);
    //             $done_off[] = $production;
    //             continue;
    //         }

    //         $daily_production = ProductionJournaliere::create([
    //             'user_id' => $data['user_id'],
    //             'date_production'=> $data['date_production'],
    //             'type_production_id' => $production['type_production_id'],
    //             'quantite' => $production['quantite']
    //         ]);

    //         $done_on[] = $daily_production; 
            
    //     }

    //     $this->generate_pdf($data['date_production']);

    //     return response()->json([
    //         'success' => 'Golden Stock vous remercie pour le travail de ce jour',
    //         'production'=> $done_on
    //     ]);
    // }

    public function store(ProductionJournaliereRequest $request)
    {
        $data = $request->validated();
        $done_on = [];
        $done_off = [];

        foreach ($data['productions'] as $production) {
            $count = ProductionJournaliere::where('user_id', Auth::id())
                ->where('type_production_id', $production['type_production_id'])
                ->whereDate('date_production', $data['date_production'])
                ->count();

            if ($count >= 2) {
                $done_off[] = $production;
                continue;
            }

            $done_on[] = ProductionJournaliere::create([
                'user_id'            => $data['user_id'],
                'date_production'    => $data['date_production'],
                'type_production_id' => $production['type_production_id'],
                'quantite'           => $production['quantite'],
            ]);
        }

        $filename = $this->generate_pdf($data['date_production']);

       $admins = User::whereHas('role', function ($p) {
            $p->where('libelle_role', 'administrateur');
        })->get();

        $superviseur = trim(
            (Auth::user()->prenom ?? '') . ' ' . (Auth::user()->nom ?? '')
        ) ?: (Auth::user()->name ?? 'Superviseur');

        if (!empty($done_on)) {
            foreach ($admins as $admin) {
                // Mail::to($admin->email)->send(
                //     new ProductionJournaliereMail(
                //         collect($done_on),
                //         $data['date_production'],
                //         $filename,
                //         Auth::user()->nom.' - '.Auth::user()->prenom
                //     )
                // );
                Mail::to(Auth::user()->email)->send(
                    new ProductionJournaliereMail(
                        productionsCollection: collect($done_on),
                        date_production:       $data['date_production'],
                        pdf_filename:          $filename,
                        superviseur:           $superviseur,
                    )
                );
            }
        }

        // return response()->json([
        //     'success'    => 'Golden Stock vous remercie pour le travail de ce jour',
        //     'production' => $done_on,
        //     'pdf_url'    => asset('storage/rapports/' . $filename),
        // ]);
        return response()->json([
            'success'    => 'Golden Stock vous remercie pour le travail de ce jour',
            'production' => $done_on,
            'pdf_url'    => Storage::disk('public')->url('rapports/' . $filename),
        ]);
    }

    // public function generate_pdf(string $day)
    // {
    //     $productions = ProductionJournaliere::with('type.mouvements.matiere')->where('user_id',Auth::id())->where('date_production',$day)->get();

    //     $pdf = $pdf = Pdf::loadView('rapport', [
    //         'productions' => $productions,
    //         'generate_date' => now()->format('d/m/Y H:i'),
    //         'date_production' => $day
    //     ])->setOptions([
    //         'defaultFont'   => 'DejaVu Sans',
    //         'isRemoteEnabled' => true,
    //         'isHtml5ParserEnabled' => true,
    //     ]);

    //     // $filename = 'rapport_production_' . now() . '_' . Auth::id() . '.pdf';

    //     // return $pdf->download($filename);
    //     $pdf->save(storage_path('app/public/rapports/' . $filename));

    //     return $filename;
    // }

    // public function generate_pdf(string $day): string
    // {
    //     $productions = ProductionJournaliere::with('type.mouvements.matiere')
    //         ->where('user_id', Auth::id())
    //         ->where('date_production', $day)
    //         ->get();

    //     $filename = 'rapport_production_' . now()->format('Y-m-d_H-i-s') . '_' . Auth::id() . '.pdf';
    //     $directory = storage_path('app/public/rapports');

    //      if (!file_exists($directory)) {
    //         mkdir($directory, 0755, true);
    //     }

    //     $pdf = Pdf::loadView('rapport', [
    //         'productions'    => $productions,
    //         'generate_date'  => now()->format('d/m/Y H:i'),
    //         'date_production' => $day,
    //     ])->setOptions([
    //         'defaultFont'          => 'DejaVu Sans',
    //         'isRemoteEnabled'      => true,
    //         'isHtml5ParserEnabled' => true,
    //     ]);

    //     $pdf->save(storage_path('app/public/rapports/' . $filename));

    //     return $filename;
    // }

    public function generate_pdf(string $date): string
    {
        // $productions = ProductionJournaliere::with('type.mouvements.matiere')
        //     ->where('user_id', Auth::id())
        //     ->whereDate('date_production', $day)
        //     ->get();

        $productions = ProductionJournaliere::with([
            'type.mouvements' => function ($query) use ($date) {
                $query->whereDate('created_at', $date);
            },
            'type.mouvements.matiere'
        ])
        ->where('user_id', Auth::id())
        ->whereDate('date_production', $date)
        ->get();

        

        // $mouvements = MouvementStock::whereDate('created_at', '2026-04-14')->get();
        // // dd($mouvements->count(), $mouvements->pluck('id'));

        // return response()->json([
        //     'mouvements' => $mouvements,
        //     'productions' => $productions
        // ]);

        $filename = 'rapport_production_' . now()->format('Y-m-d_H-i-s') . '_' . Auth::id() . '.pdf';

        // Crée le dossier si absent, ne fait rien s'il existe déjà
        Storage::disk('public')->makeDirectory('rapports');

        $pdf = Pdf::loadView('rapport', [
            'productions'    => $productions,
            'generate_date'  => now()->format('d/m/Y H:i'),
            'date_production' => $date,
        ])->setOptions([
            'defaultFont'          => 'DejaVu Sans',
            'isRemoteEnabled'      => true,
            'isHtml5ParserEnabled' => true,
        ]);

        // Sauvegarde dans storage/app/public/rapports/
        $pdf->save(Storage::disk('public')->path('rapports/' . $filename));

        return $filename;
    }

}
