<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Mail\RegisterMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{

    public function index()
    {
        return response()->json([
            'users' => User::with('role')->get()
        ]);
    }

    public function register(RegisterRequest $request)
    {
        $validation = $request->validated();
        $password =  $validation['password'];
        $validation['password'] = Hash::make($password);

        $user = User::create($validation);

        Mail::to($user)->send(new RegisterMail($user,$password));

        return response()->json([
            'user' => $user
        ]);
    }


    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string'
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            // logger([
            //     'session_id' => session()->getId(),
            //     'user'       => Auth::id(),
            //     'guard'      => Auth::getDefaultDriver(),
            // ]);
            return response()->json(['success' => true, 'user' => Auth::user()]);
        }
        throw ValidationException::withMessages([
            'error' => 'Email ou Mot de passe incorrect'
        ]);
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return response()->json(['succes' => 'Déconnexion réussi avec succès']);
    }
}
