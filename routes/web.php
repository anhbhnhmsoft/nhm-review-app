<?php

use App\Http\Controllers\FileController;
use Illuminate\Support\Facades\Route;


Route::get('/', \App\Livewire\Dashboard::class)->name('dashboard');

Route::get('/image/{file_path}', [FileController::class, 'image'])
    ->where('file_path', '.*')
    ->name('public_image');

