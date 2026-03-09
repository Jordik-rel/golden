<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $validation = $request->validated();

        $validation['password'] = Hash::make($validation['password']);

        $user = User::create($validation);

        event(new Registered($user));

        Auth::login($user);

        return response()->json([
            'user'=> Auth::user()
        ]);
    }


    public function login(Request $request)
    {
         $credentials = $request->validate([
            'email'=>'required|email',
            'password'=>'required|string'
        ]);
        
        if(Auth::attempt($credentials)){
            $request->session()->regenerate();  
            logger([
                'session_id' => session()->getId(),
                'user'       => Auth::id(),
                'guard'      => Auth::getDefaultDriver(),
            ]);          
            return response()->json(['success'=>true,'user'=>Auth::user()]);
        }
        throw ValidationException::withMessages([
            'error'=>'Email ou Mot de passe incorrect'
        ]);
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return response()->json(['succes'=>'Déconnexion réussi avec succès']);
    }
}
