<?php

namespace App\Filament\Resources\Reviews\Tables;

use Illuminate\Database\Eloquent\Builder;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class ReviewsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->striped()
            ->emptyStateHeading('Chưa có đánh giá nào')
            ->defaultPaginationPageOption(10)
            ->modifyQueryUsing(function (Builder $query) {
                $storeId = request()->query('store_id');
                if ($storeId) {
                    $query->where('store_id', $storeId);
                }
                return $query;
            })
            ->columns([
                TextColumn::make('store.name')
                    ->label('Địa điểm')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('user.name')
                    ->label('Người dùng')
                    ->searchable()
                    ->sortable(),
                IconColumn::make('is_anonymous')
                    ->label('Ẩn danh')
                    ->boolean(),
                TextColumn::make('rating_location')
                ->label('Vị trí')
                ->sortable()
                ->alignCenter()
                ->formatStateUsing(fn ($state) => $state . ' ☆'),
                TextColumn::make('rating_space')
                ->label('Không gian')
                ->sortable()
                ->alignCenter()
                ->formatStateUsing(fn ($state) => $state . ' ☆'),
                TextColumn::make('rating_quality')
                ->label('Chất lượng')
                ->sortable()
                ->alignCenter()
                ->formatStateUsing(fn ($state) => $state . ' ☆'),
                TextColumn::make('rating_serve')
                ->label('Phục vụ')
                ->sortable()
                ->alignCenter()
                ->formatStateUsing(fn ($state) => $state . ' ☆'),
                TextColumn::make('created_at')
                    ->label('Ngày tạo')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->label('Ngày xóa')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make(),
                DeleteAction::make(),
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


