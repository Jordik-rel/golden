<?php

namespace App\Http\Requests;

use App\Models\Fournisseur;
use App\Models\MatierePremiere;
use App\Models\TypeProduction;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class MouvementRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'matiere_premiere_id'=>['required',Rule::exists(MatierePremiere::class,'id')],
            'user_id'=>['required',Rule::exists(User::class,'id')],
            'type_production_id'=>['nullable',Rule::exists(TypeProduction::class,'id')],
            'type_mouvement'=>['required','string'],
            'libelle_mouvement'=>['required','string'],
            'fournisseur_id'=>['nullable',Rule::exists(Fournisseur::class,'id')],
            'quantite'=>['required','min:1','numeric']
        ];
    }

    protected function prepareForValidation()
    {
        $matiere = MatierePremiere::find($this->matiere_premiere_id);
        return $this->merge([
            'user_id'=>Auth::id(),
            'libelle_mouvement' => $this->mouvement_selected_message($this->type_mouvement).' de la matière première '.$matiere->libelle_matiere.' par '.Auth::user()->nom.' '.Auth::user()->prenom
        ]);
    }

    protected function mouvement_selected_message(String $mouvemnt)
    {
        if($mouvemnt === 'sortie'){
            return 'Sortie';
        }elseif($mouvemnt === 'entree'){
            return 'Entrée';
        }else{
            return 'Ajustement';
        }
    }
}
