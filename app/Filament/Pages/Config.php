<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use BackedEnum;
use Filament\Support\Facades\FilamentAsset;

class Config extends Page
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationLabel = 'Config';
    protected static ?int $navigationSort = 9999;
    protected string $view = 'filament.pages.config';
    
}
