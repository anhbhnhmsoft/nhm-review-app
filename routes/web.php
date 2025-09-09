<?php

use App\Http\Controllers\CommonController;
use App\Http\Controllers\FileController;
use Illuminate\Support\Facades\Route;
use App\Utils\Constants\UserRole;

Route::get('/', \App\Livewire\Dashboard::class)->name('dashboard');

Route::get('/image/{file_path}', [FileController::class, 'image'])
    ->where('file_path', '.*')
    ->name('public_image');
Route::get('/video/{file_path}', [FileController::class, 'video'])
    ->where('file_path', '.*')
    ->name('public_video');


Route::get('/dang-nhap', \App\Livewire\Login::class)->name('frontend.login');
Route::get('/dang-ky', \App\Livewire\Register::class)->name('frontend.register');
Route::get('/dang-xuat', [\App\Http\Controllers\AuthController::class, 'logout'])->name('frontend.logout');
Route::get('/dia-diem/{slug}', \App\Livewire\Store::class)->name('frontend.store');
Route::get('/tim-kiem', \App\Livewire\SearchStore::class)->name('frontend.search-store');

Route::prefix('common')->group(function () {
    Route::get('/google-map', [CommonController::class, 'getKeyGoogleMap']);
    Route::get('/province', [CommonController::class, 'getProvinces']);
    Route::get('/district/{code}', [CommonController::class, 'getDistricts']);
    Route::get('/ward/{code}', [CommonController::class, 'getWards']);
});
Route::get('/verify/{id}/{hash}', [\App\Http\Controllers\AuthController::class, 'verify'])->name('verify');
