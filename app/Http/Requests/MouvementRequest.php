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
        return $this->merge([
            'user_id'=>Auth::id()
        ]);
    }
}
