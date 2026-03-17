<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DetailInventaireController;
use App\Http\Controllers\Api\FournisseurController;
use App\Http\Controllers\Api\InventaireController;
use App\Http\Controllers\Api\MatiereController;
use App\Http\Controllers\Api\MouvementController;
use App\Http\Controllers\Api\PermissionController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\PlanningController;
use App\Http\Controllers\TypeProductionController;
use App\Http\Controllers\ProductionJournaliereController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return User::with('role')->find(Auth::id());
})->middleware('auth:sanctum');

Route::get('/all/users', function (Request $request) {
    return response()->json([
        'users' => User::with('role.permission')->get()
    ]);
})->middleware('auth:sanctum');

Route::prefix('auth')->name('auth.')->group(function(){
    Route::post('login',[AuthController::class, 'login'])->name('login');
    Route::post('register',[AuthController::class, 'register'])->name('register');
    Route::post('logout',[AuthController::class,'logout'])->name('logout')->middleware(['auth:sanctum','verified','status:completed']);
});

Route::middleware(['auth:sanctum'])->prefix('preface/')->group(function(){
    Route::resource('users', AuthController::class)->except(['create', 'edit']);
    Route::put('inventaire/{inventaire}/start',[InventaireController::class,'start']);
    Route::put('inventaire/{inventaire}/end',[InventaireController::class,'end_inventaire']);
    Route::get('quantity/{matiere}',[InventaireController::class, 'calcul_quantite']);
    Route::get('types-by-date',[TypeProductionController::class, 'get_type_by_date']);
    
    Route::resource('role',RoleController::class)->except(['create','edit']);
    Route::resource('permission',PermissionController::class)->except(['create','edit']);
    Route::resource('fournisseur',FournisseurController::class)->except(['create','edit']);
    Route::resource('matiere',MatiereController::class)->except(['create','edit']);
    Route::resource('type',TypeProductionController::class)->except(['create','edit']);
    Route::resource('planning',PlanningController::class)->except(['create','edit']);
    Route::resource('mouvement',MouvementController::class)->except(['create','edit']);
    Route::resource('inventaire',InventaireController::class)->except(['create','edit']);
    Route::resource('inventaire/{inventaire}/details',DetailInventaireController::class)->except(['create','edit']);
    Route::resource('rapport',ProductionJournaliereController::class)->except(['create','edit']);
});


