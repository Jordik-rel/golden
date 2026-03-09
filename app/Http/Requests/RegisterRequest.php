<?php

namespace App\Http\Requests;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\Rule;

class RegisterRequest extends FormRequest
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
            'nom'=>['required','string'],
            'prenom'=>['required','string'],
            'email'=>['required','string','email',Rule::unique(User::class,'email')],
            'password'=>['required','string','min:8',Password::default()],
            'tel'=>['required','string','size:10'],
            'role_id'=>['required',Rule::exists(Role::class,'id')]
        ];
    }
}
