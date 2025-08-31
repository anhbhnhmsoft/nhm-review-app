<?php

namespace App\Filament\Resources\Banners\Tables;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class BannersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image_path')
                    ->label('Ảnh')
                    ->disk('public')
                    ->imageWidth(200)
                    ->imageHeight(80)
                    ->visibility('public'),
                TextColumn::make('link')
                    ->action(function ($record){
                        if (!empty($record->link)){
                            redirect($record->link);
                        }
                    }),
                TextColumn::make('alt_banner')
                    ->label('Alt của banner')
                    ->searchable(),
                TextColumn::make('sort')
                    ->numeric()
                    ->sortable(),
                IconColumn::make('banner_index')
                    ->alignCenter()
                    ->label("Hiển thị đầu trang chủ")
                    ->boolean(),
                IconColumn::make('show')
                    ->alignCenter()
                    ->label('Hiển thị')
                    ->boolean(),
            ])
            ->filters([
                SelectFilter::make('banner_index')
                    ->label('Hiển thị đầu trang chủ')
                    ->options([
                        true => "Có",
                        false => "Không"
                    ]),
                SelectFilter::make('show')
                    ->label('Hiển thị')
                    ->options([
                        true => "Có",
                        false => "Không"
                    ]),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()->visible()
            ]);
    }
}
