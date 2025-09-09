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
Route::get('/bai-viet', \App\Livewire\News::class)->name('frontend.news');

Route::get('/bai-viet/{slug}', \App\Livewire\ArticleDetail::class)->name('frontend.article-detail');
Route::get('/tin-tuc/tat-ca', \App\Livewire\ArticleList::class)->name('frontend.articles.news');
Route::get('/bao-chi/tat-ca', \App\Livewire\ArticleList::class)->name('frontend.articles.press');
Route::get('/cam-nang/tat-ca', \App\Livewire\ArticleList::class)->name('frontend.articles.handbook');

Route::prefix('common')->group(function () {
    Route::get('/google-map', [CommonController::class, 'getKeyGoogleMap']);
    Route::get('/province', [CommonController::class, 'getProvinces']);
    Route::get('/district/{code}', [CommonController::class, 'getDistricts']);
    Route::get('/ward/{code}', [CommonController::class, 'getWards']);
});
Route::get('/email/xac-minh/{id}/{hash}', [\App\Http\Controllers\AuthController::class, 'verify'])
    ->middleware(['throttle:6,1'])
    ->name('verification.verify');

Route::get('/{slug}', \App\Livewire\PageStatic::class)
    ->name('frontend.page-static');
