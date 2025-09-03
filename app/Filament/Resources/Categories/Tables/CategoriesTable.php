<?php

namespace App\Filament\Resources\Categories\Tables;

use App\Utils\Constants\CategoryStatus;
use App\Utils\HelperFunction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class CategoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('logo')
                    ->visibility('public')
                    ->label('Hình ảnh')
                    ->disk('public'),
                TextColumn::make('name')
                    ->label('Tên danh mục')
                    ->searchable(),
                IconColumn::make('show_index_home_page')
                    ->alignCenter()
                    ->label('Hiển thị trên trang chủ')
                    ->boolean(),
                IconColumn::make('show_header_home_page')
                    ->label('Hiển thị trên đầu trang chủ')
                    ->alignCenter()
                    ->boolean(),
                TextColumn::make('parent.name')
                    ->label('Danh mục cha')
                    ->default('Không có')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('status')
                    ->label('Trạng thái')
                    ->badge()
                    ->color(fn($state) => match ($state) {
                        CategoryStatus::ACTIVE->value => 'success',
                        CategoryStatus::INACTIVE->value => 'warning',
                        default => 'default',
                    })->formatStateUsing(fn($state) => $state == CategoryStatus::ACTIVE->value ? 'Hoạt động' : 'Không hoạt động'),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
