<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MatiereRequest extends FormRequest
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
            'libelle_matiere' => ['required','string','regex:/^(?!.*(.)\1{2,})(?=.*\pL)[\pL\s\.\,\?\'\"\-]{2,}$/u'],
            'unite'=>['required','string','regex:/^(?!.*(.)\1{2,})(?=.*\pL)[\pL\s\.\,\?\'\"\-]{2,}$/u'],
            'seuil_min'=>['required','numeric','min:0','gt:seuil_alerte'],
            'seuil_alerte'=>['required','numeric','min:0'],
            'quantite'=>['required','numeric','min:0']  //,'gt:seuil_min'
        ];
    }

    protected function prepareForValidation()
    {
        return $this->merge([
            'quantite' => 0
        ]);
    }
}
