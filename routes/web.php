<?php

use App\Http\Controllers\ResponseController;
use App\Http\Controllers\ResultController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/upload', [ResponseController::class, 'upladIndex'])->name('upload.index');
Route::post('/upload', [ResponseController::class, 'upload'])->name('upload.image');

Route::get('/result', [ResultController::class, 'resultIndex'])->name('result.index');
