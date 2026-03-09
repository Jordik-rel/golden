<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class InventaireRequest extends FormRequest
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
            'libelle_inventaire'=> ['required','string','regex:/^(?!.*(.)\1{2,})(?=.*\pL)[\pL\s\.\,\?\'\"\-]{2,}$/u'],
            'user_id'=>['required',Rule::exists(User::class,'id')],
            'date_debut' =>['required','date','after_or_equal:today'],
            'date_fin' =>['nullable','date','after_or_equal:date_debut'],
            'avec_correction_stock' => ['required','boolean'],
            'etat'=>['required','string','in:en_attente,en_cours,terminé,annulé']
        ];
    }

    protected function prepareForValidation()
    {
        return $this->merge([
            'user_id'=> Auth::id(),
            'etat' => 'en_attente'
        ]);
    }
}
