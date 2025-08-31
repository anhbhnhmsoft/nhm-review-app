<?php

namespace App\Filament\Resources\Articles\Tables;

use App\Utils\Constants\ArticleStatus;
use App\Utils\Constants\ArticleType;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ArticlesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->striped()
            ->emptyStateHeading('Chưa có bài viết nào')
            ->defaultPaginationPageOption(10)
            ->columns([
                ImageColumn::make('image_path')
                    ->label('Ảnh đại diện')
                    ->disk('public')
                    ->imageSize(60)
                    ->visibility('public'),
                TextColumn::make('title')
                    ->label('Tiêu đề')
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
                TextColumn::make('author')
                    ->label('Tác giả')
                    ->limit(30)
                    ->tooltip(function ($column): ?string {
                        $state = $column->getState();
                        if (strlen($state) <= $column->getCharacterLimit()) {
                            return null;
                        }
                        return $state;
                    })
                    ->searchable(),
                TextColumn::make('view')
                    ->label('Lượt xem')
                    ->searchable(),
                TextColumn::make('sort')
                    ->label('Thứ tự')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('type')
                    ->label('Trạng thái')
                    ->badge()
                    ->color(fn($state) => match ($state) {
                        ArticleType::FIXED->value => 'danger',
                        ArticleType::PRESS->value => 'success',
                        ArticleType::NEWS->value, ArticleType::HANDBOOK->value => 'warning',
                        default => 'default',
                    })
                    ->formatStateUsing(fn($state) => ArticleType::tryFrom($state)?->label()),
                TextColumn::make('status')
                    ->numeric()
                    ->formatStateUsing(fn($state) => ArticleStatus::tryFrom($state)?->label()),
            ])
            ->filters([
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()
                    ->visible(function ($record) {
                        return $record->type != ArticleType::FIXED->value;
                    }),

            ]);
    }
}
