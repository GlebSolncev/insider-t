<?php

use App\Http\Controllers\ClubController;
use App\Http\Controllers\LeagueController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'v1'], function(){
    Route::group(['prefix' => 'clubs'], function(){
        Route::get('', [ClubController::class, 'index'])->name('club.index');
    });

    Route::group(['prefix' => 'league'], function() {
        Route::post('', [LeagueController::class, 'store'])->name('league.store');
        Route::post('play', [LeagueController::class, 'play'])->name('league.play');
        Route::post('play-all', [LeagueController::class, 'playAll'])->name('league.play-all');
        Route::post('next-week', [LeagueController::class, 'nextWeek'])->name('league.next-week');

    });
});
