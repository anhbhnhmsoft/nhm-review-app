<?php

namespace App\Filament\Resources\Categories\Tables;

use App\Utils\Constants\CategoryStatus;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class CategoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Tên danh mục')
                    ->searchable(),
                TextColumn::make('name_home_page')
                    ->label('Tên danh mục trang chủ')
                    ->searchable(),
                IconColumn::make('show_header_home_page')
                    ->label('Hiển thị trên trang chủ')
                    ->boolean(),
                IconColumn::make('show_index_home_page')
                    ->label('Hiển thị trên trang chủ')
                    ->boolean(),
                TextColumn::make('slug')
                    ->label('Đường dẫn')
                    ->searchable(),
                TextColumn::make('logo')
                    ->label('Hình ảnh')
                    ->searchable(),
                TextColumn::make('parent_id')
                    ->label('Danh mục cha')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Trạng thái')
                    ->color(fn($state) => match ($state) {
                        CategoryStatus::ACTIVE->value => 'success',
                        CategoryStatus::INACTIVE->value => 'warning',
                        default => 'default',
                    })->formatStateUsing(fn($state) => $state ? 'hoạt động' : 'không hoạt động'),
                TextColumn::make('deleted_at')
                    ->label('Ngày xóa')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->label('Ngày tạo')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Ngày cập nhật')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
