<?php

use App\Http\Controllers\FileController;
use Illuminate\Support\Facades\Route;


Route::get('/', \App\Livewire\Dashboard::class)->name('dashboard');

Route::get('/file/{file_path}', [FileController::class, 'loadfile'])
    ->where('file_path', '.*')
    ->name('loadfile');

