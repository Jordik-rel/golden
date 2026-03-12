<?php

use App\Mail\RegisterMail;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test', function () {
    $user = User::find(1);
    $password = "password";
    return new RegisterMail($user, $password);
});
