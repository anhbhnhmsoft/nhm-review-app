<?php

namespace App\Filament\Resources\Stores\Tables;

use App\Models\Category;
use App\Utils\Constants\CategoryStatus;
use App\Utils\Constants\StoreStatus;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use App\Filament\Resources\Reviews\ReviewResource;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
class StoresTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                return $query->with(['category', 'province', 'district', 'ward']);
            })
            ->emptyStateHeading('Chưa có địa điểm nào')
            ->defaultPaginationPageOption(10)
            ->columns([
                ImageColumn::make('logo_path')
                    ->label('Logo')
                    ->disk('public')
                    ->imageSize(60)
                    ->visibility('public'),
                TextColumn::make('name')
                    ->label('Tên')
                    ->limit(30)
                    ->tooltip(function ($column): ?string {
                        $state = $column->getState();
                        if (strlen($state) <= $column->getCharacterLimit()) {
                            return null;
                        }
                        return $state;
                    })
                    ->searchable(),
                TextColumn::make('slug')
                    ->limit(30)
                    ->tooltip(function ($column): ?string {
                        $state = $column->getState();
                        if (strlen($state) <= $column->getCharacterLimit()) {
                            return null;
                        }
                        return $state;
                    })
                    ->searchable(),
                TextColumn::make('category.name')
                    ->label('Danh mục'),
                TextColumn::make('address')
                    ->label('Địa chỉ')
                    ->description(function ($record): string {
                        return "{$record->ward->name}, {$record->district->name}, {$record->province->name}";
                    }),
                TextColumn::make('status')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Trạng thái')
                    ->badge()
                    ->color(fn($state) => match ($state) {
                        StoreStatus::ACTIVE->value => 'success',
                        StoreStatus::PENDING->value => 'warning',
                        StoreStatus::INACTIVE->value => 'danger',
                        default => 'default',
                    })->formatStateUsing(fn($state) => StoreStatus::getLabel($state)),
                TextColumn::make('view')
                    ->searchable(),
                IconColumn::make('featured')
                    ->label('Cửa hàng nổi bật')
                    ->boolean(),
                TextColumn::make('sorting_order')
                    ->label('Thứ tự sắp xếp')
                    ->numeric()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Trạng thái')
                    ->options(StoreStatus::getOptions()),
                SelectFilter::make('featured')
                    ->label('Cửa hàng nổi bật')
                    ->options([
                        true => "Có",
                        false => "Không"
                    ]),
                SelectFilter::make('category_id')
                    ->label('Thuộc danh mục')
                    ->options(Category::query()
                        ->where('status', CategoryStatus::ACTIVE->value)
                        ->limit(10)
                        ->pluck('name', 'id')
                        ->all())
                    ->searchable()
                    ->multiple()
                    ->getSearchResultsUsing(fn(string $search): array => Category::query()
                        ->where('name', 'like', "%{$search}%")
                        ->where('status', CategoryStatus::ACTIVE->value)
                        ->limit(10)
                        ->pluck('name', 'id')
                        ->all())

            ])
            ->recordActions([
                EditAction::make(),
                Action::make('reviews')
                    ->label('Đánh giá')
                    ->icon('heroicon-m-star')
                    ->color('warning')
                    ->url(fn($record) => ReviewResource::getUrl('index', ['store_id' => $record->id]))
                    ->openUrlInNewTab(false),
                DeleteAction::make()->visible(),
            ]);
    }
}
