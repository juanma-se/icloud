<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::apiResource('/user', UserController::class);

    //Answers
    //Route::apiResource('/documents', AnswerController::class);
    Route::post('/logout', LogoutController::class);
});

Route::post('/login', LoginController::class);
Route::post('/register', RegisterController::class);
