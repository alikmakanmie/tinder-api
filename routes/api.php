<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PeopleController;

Route::prefix('people')->group(function () {
    Route::get('/recommended', [PeopleController::class, 'recommended']);
    Route::post('/{id}/like', [PeopleController::class, 'like']);
    Route::post('/{id}/dislike', [PeopleController::class, 'dislike']);
    Route::get('/liked', [PeopleController::class, 'liked']);
});
