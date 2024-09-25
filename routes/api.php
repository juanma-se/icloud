<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DocumentStatsController;

Route::middleware(['auth:sanctum'])->group(function () {
    //Users
    Route::apiResource('/user', UserController::class);

    //Documents
    Route::get('/documents', [DocumentController::class, 'index']);
    Route::get('/documents/{id}', [DocumentController::class, 'show']);
    Route::post('/documents', [DocumentController::class,'create'])->middleware(['role:administrator|responsible']);
    Route::put('/documents/{id}', [DocumentController::class, 'update'])->middleware(['role:administrator']);
    Route::delete('/documents/{id}', [DocumentController::class, 'destroy'])->middleware(['role:administrator']);
    Route::get('/documents/stats/relevance', [DocumentStatsController::class, 'relevanceStats']);
    Route::get('/documents/stats/monthly-approvals', [DocumentStatsController::class, 'monthlyApprovals']);
    Route::get('/documents/stats/relevance-with-documents', [DocumentStatsController::class, 'relevanceStatsWithDocuments']);

    //Logout
    Route::post('/logout', LogoutController::class);
});

//Unauthenticated endpoints
Route::post('/login', LoginController::class);
Route::post('/register', RegisterController::class);
