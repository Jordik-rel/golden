<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Models\User;
use App\Models\TypeProduction;

class ProductionJournaliereRequest extends FormRequest
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
            'user_id' => ['required',Rule::exists(User::class,'id')],
            'date_production' => ['required','date','after_or_equal:' . now()->subDays(2)->toDateString(),'before_or_equal:' . now()->toDateString()],
            // 'productions' => ['required','array'],
            // 'type_production_id' => ['required',Rule::exists(TypeProduction::class,'id')],
            // 'quantite' => ['required','numeric'],
            'productions' => ['required', 'array', 'min:1'],
            'productions.*.type_production_id' => ['required',Rule::exists(TypeProduction::class, 'id')],
            'productions.*.quantite' => ['required', 'numeric', 'min:0'],
        ];
    }

    protected function prepareForValidation()
    {
        return $this->merge([
            'user_id'=> Auth::id(),
        ]);
    }
}
