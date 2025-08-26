<?php

namespace App\Filament\Resources\Categories\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class CategoryInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name'),
                TextEntry::make('name_home_page'),
                IconEntry::make('show_header_home_page')
                    ->boolean(),
                IconEntry::make('show_index_home_page')
                    ->boolean(),
                TextEntry::make('slug'),
                TextEntry::make('logo'),
                TextEntry::make('parent_id')
                    ->numeric(),
                TextEntry::make('status')
                    ->numeric(),
                TextEntry::make('deleted_at')
                    ->dateTime(),
                TextEntry::make('created_at')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->dateTime(),
            ]);
    }
}
