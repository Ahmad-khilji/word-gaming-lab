<?php

use App\Http\Controllers\Api\WordController;
use App\Http\Controllers\Api\WordleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/fetch/all', [WordController::class, 'fetchdataAll']);
Route::get('/words/all', [WordController::class, 'fetchAll']);
Route::post('/user/attempt', [WordController::class, 'userAttempt']);
Route::get('/game/statistics', [WordController::class, 'getStatistics']);
Route::get('/get/user/statistics', [WordController::class, 'getUserStatistics']);

