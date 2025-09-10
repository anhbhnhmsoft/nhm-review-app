<?php

namespace App\Filament\Resources\Reviews\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ReviewInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextEntry::make('store.name')
            ->label('Địa điểm'),
            TextEntry::make('user.name')
            ->label('Người dùng'),
            IconEntry::make('is_anonymous')
            ->label('Ẩn danh')
            ->boolean(),
            TextEntry::make('rating_location')
            ->label('Điểm vị trí')
            ->formatStateUsing(fn ($state) => $state . ' ☆'),
            TextEntry::make('rating_space')
            ->label('Điểm không gian')
            ->formatStateUsing(fn ($state) => $state . ' ☆'),
            TextEntry::make('rating_quality')
            ->label('Điểm chất lượng')
            ->formatStateUsing(fn ($state) => $state . ' ☆'),
            TextEntry::make('rating_serve')
            ->label('Điểm phục vụ')
            ->formatStateUsing(fn ($state) => $state . ' ☆'),
            TextEntry::make('review')
            ->label('Nội dung')
            ->columnSpanFull(),
            ImageEntry::make('reviewImages.image_path')
                ->disk('public')
                ->visibility('public')
                ->label('Hình ảnh')
                ->columnSpanFull(),
        ]);
    }
}
