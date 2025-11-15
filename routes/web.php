<?php

use App\Http\Controllers\ResponseController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/upload', function () {
    return view('upload');
});
Route::post('/upload', [ResponseController::class, "uplad"]);
