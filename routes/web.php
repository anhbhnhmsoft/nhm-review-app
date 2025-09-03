<?php

use App\Http\Controllers\CommonController;
use App\Http\Controllers\FileController;
use Illuminate\Support\Facades\Route;


Route::get('/', \App\Livewire\Dashboard::class)->name('dashboard');

Route::get('/image/{file_path}', [FileController::class, 'image'])
    ->where('file_path', '.*')
    ->name('public_image');
Route::get('/video/{file_path}', [FileController::class, 'video'])
    ->where('file_path', '.*')
    ->name('public_video');


Route::get('/dia-diem/{slug}', \App\Livewire\Store::class)->name('frontend.store');


Route::prefix('common')->group(function () {
    Route::get('/google-map', [CommonController::class, 'getKeyGoogleMap']);
    Route::get('/province', [CommonController::class, 'getProvinces']);
    Route::get('/district/{code}', [CommonController::class, 'getDistricts']);
    Route::get('/ward/{code}', [CommonController::class, 'getWards']);
});
