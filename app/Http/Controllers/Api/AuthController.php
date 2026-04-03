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


    // public function login(Request $request)
    // {
    //     $credentials = $request->validate([
    //         'email' => 'required|email',
    //         'password' => 'required|string'
    //     ]);

    //     if (Auth::attempt($credentials)) {
    //         $request->session()->regenerate();
    //         // logger([
    //         //     'session_id' => session()->getId(),
    //         //     'user'       => Auth::id(),
    //         //     'guard'      => Auth::getDefaultDriver(),
    //         // ]);
    //         return response()->json(['success' => true, 'user' => Auth::user()]);
    //     }
    //     throw ValidationException::withMessages([
    //         'error' => 'Email ou Mot de passe incorrect'
    //     ]);
    // }

    public function login(Request $request) 
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return response()->json(['success' => true, 'user' => Auth::user()]);
        }

        if (!Auth::attempt($credentials)) {
            throw ValidationException::withMessages([
                'error' => 'Email ou mot de passe incorrect',
            ]);
        }

        $user = Auth::user();

        //Web Angular — session cookie Sanctum
        $request->session()->regenerate();

        //Mobile Flutter — Bearer token Sanctum
        // Supprimer les anciens tokens mobile pour éviter l'accumulation
        $user->tokens()->where('name', 'mobile')->delete();
        $token = $user->createToken('mobile')->plainTextToken;

        return response()->json([
            'success' => true,
            'user'    => $user,
            'token'   => $token,   // Flutter l'utilise, Angular l'ignore
        ]);
    }
    
    public function logout(Request $request) 
    {
        $user = $request->user();

        if ($user) {
            $token = $user->currentAccessToken();

            // Vérification cruciale pour éviter l'erreur 500 sur le Web
            // On ne tente le delete() que si le jeton est une instance stockée en BDD (Mobile)
            if ($token instanceof \Laravel\Sanctum\PersonalAccessToken) {
                $token->delete();
            }

            // Nettoyage de la session (Spécifique au Web/Angular)
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Déconnexion réussie avec succès'
        ]);
    }
}
