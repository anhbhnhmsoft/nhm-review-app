<?php

namespace App\Filament\Resources\Categories\Schemas;

use App\Utils\Constants\CategoryStatus;
use App\Utils\HelperFunction;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class CategoryInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name')
                    ->label('Tên danh mục'),
                IconEntry::make('show_index_home_page')
                    ->label('Hiển thị ở trang chủ')
                    ->boolean(),
                IconEntry::make('show_header_home_page')
                    ->label('Hiển thị trên trang chủ')
                    ->boolean(),
                TextEntry::make('slug')
                    ->label('Đường dẫn'),
                TextEntry::make('parent.name')
                    ->label('Danh mục cha')
                    ->default('Không có danh mục cha'),
                TextEntry::make('description')
                    ->label('Mô tả'),
                ImageEntry::make('logo')
                    ->label('Hình ảnh')
                    ->visibility('public')
                    ->disk('public'),
                TextEntry::make('status')
                    ->label('Trạng thái')
                    ->badge()
                    ->color(fn($state) => match ($state) {
                        CategoryStatus::ACTIVE->value => 'success',
                        CategoryStatus::INACTIVE->value => 'warning',
                        default => 'default',
                    })->formatStateUsing(fn($state) => $state == CategoryStatus::ACTIVE->value ? 'Hoạt động' : 'Không hoạt động'),
            ]);
    }
}
