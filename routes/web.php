<?php

use App\Http\Controllers\MainController;
use App\Http\Controllers\PredictionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [MainController::class, 'getStarting']);
Route::get('/reset-all', [MainController::class, 'resetAll']);
Route::get('/standings', [MainController::class, 'getStandings']);
Route::get('/fixtures', [MainController::class, 'getFixtures']);

//predictionController
Route::get('/prediction', [PredictionController::class, 'getPrediction']);

//simulatorController
Route::get('/play-all-weeks', [\App\Http\Controllers\SimulatorController::class, 'playAllWeeks']);
Route::get('/play-week/{weekId}', [\App\Http\Controllers\SimulatorController::class, 'playWeekly']);
