<?php

use Illuminate\Support\Facades\Route;




Route::get('/', \App\Livewire\Dashboard::class)->name('dashboard');
