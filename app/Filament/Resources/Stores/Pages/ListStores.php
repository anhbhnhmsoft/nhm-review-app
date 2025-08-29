<?php

namespace App\Filament\Resources\Stores\Pages;

use App\Filament\Resources\Stores\StoreResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Icons\Heroicon;

class ListStores extends ListRecords
{
    protected static string $resource = StoreResource::class;

    protected static ?string $title = 'Danh sách địa điểm';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Tạo mới địa điểm')
                ->icon(Heroicon::Plus),
        ];
    }
}
