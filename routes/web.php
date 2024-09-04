<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CardGameController;

Route::controller(CardGameController::class)->group(function () {
    Route::get('/cardgame', 'index')->name('cardgame.index');
    Route::post('/cardgame/guess', 'handleGuess')->name('cardgame.guess');
    Route::post('/cardgame/new', 'newGame')->name('cardgame.new');
    Route::post('/cardgame/clear', 'clearSession')->name('cardgame.clear');
});