<?php

namespace App\Http\Requests;

use App\Models\Inventaire;
use App\Models\MatierePremiere;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateDetailInventaireRequest extends FormRequest
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

        $detailId = $this->route('detail')?->id;

        return [
            'inventaire_id' => ['required',Rule::exists(Inventaire::class,'id')],
            'user_id' => ['required',Rule::exists(User::class,'id')],
            'matiere_premiere_id' => ['required',Rule::exists(MatierePremiere::class,'id'), Rule::unique('detail_inventaires', 'matiere_premiere_id')->where('inventaire_id', $this->route('inventaire')->id)->ignore($detailId)],
            'stock_theorique' => ['required','numeric'],
            'stock_reel' => ['required','numeric'],
            'ecart' => ['nullable','numeric']
        ];
    }


    protected function prepareForValidation()
    {
        $inventaire = $this->route('inventaire');

        $detail = $this->route('detail');

        $matiere = MatierePremiere::where('id',$detail->matiere_premiere_id)->first();
        

        if ($inventaire->etat !== 'en_cours') {
            abort(403, 'Vous ne pouvez plus modifier de détail à cet inventaire pour le moment');
        }


        return $this->merge([
            'inventaire_id' => $inventaire->id,
            'user_id' => Auth::id(),
            'matiere_premiere_id'=> $matiere?->id,
            'stock_theorique' => $matiere?->quantite,
        ]);
    }
}
