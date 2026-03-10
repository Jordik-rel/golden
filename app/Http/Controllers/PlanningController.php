<?php

namespace App\Http\Controllers;

use App\Mail\PlanningMail;
use App\Models\Planning;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;

class PlanningController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([
            'plannings'=> Planning::with('user')->get()
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => ['required',Rule::exists(User::class,'id'),],
            'jour_travail' => ['required', 'string', 'in:Lundi,Mardi,Mercredi,Jeudi,Vendredi,Samedi,Dimanche',Rule::unique(Planning::class,'jour_travail')],
        ]);

        $user = User::findOrFail($data['user_id']);

        // dd($user);

        try{
            $planning = Planning::create($data);
            Mail::to($user->email)->send(new PlanningMail($planning));
            return response()->json([
                'success'=> ' Nouveau planning ajouté pour l\'utilisateur'.$user->nom.' '.$user->prenom,
                'planning'=> $planning
            ]);
        }catch(Exception $e){
            return response()->json([
                'message' => 'Erreur lors de la création d\'un planning',
                'error' => $e->getMessage()
            ],500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Planning $planning)
    {
        return response()->json([
            'planning'=> Planning::with('user')->findOrFail($planning->id)
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Planning $planning)
    {
        $data = $request->validate([
            'user_id' => ['required',Rule::exists(User::class,'id')],
            'jour_travail' => ['required', 'string', 'in:Lundi,Mardi,Mercredi,Jeudi,Vendredi,Samedi,Dimanche'],
        ]);

        $user = User::findOrFail($data['user_id']);

        // $old = Planning::where('jour_travail', $data['jour_travail'])
        //     ->where('id', '!=', $planning->id)
        //     ->update(['jour_travail' => null]);

        // dd($old);

        try{
            $planning->update($data);
            Mail::to($user->email)->send(new PlanningMail($planning));
            return response()->json([
                'planning'=>$planning,
                'success'=>' Informations planning mise à jour avec succès'
            ],201);
        }catch(Exception $e){
            return response()->json([
                'message' => 'Erreur lors de la mise à jour des informations du planning',
                'error' => $e->getMessage()
            ],500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Planning $planning)
    {
        // $user = Auth::user();

        $planning->delete();
    }
}
