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
                TextEntry::make('name_home_page')
                    ->label('Tên danh mục trang chủ'),
                IconEntry::make('show_index_home_page')
                    ->label('Hiển thị ở trang chủ')
                    ->boolean(),
                IconEntry::make('show_header_home_page')
                    ->label('Hiển thị trên trang chủ')
                    ->boolean(),
                TextEntry::make('slug')
                    ->label('Đường dẫn'),
                ImageEntry::make('logo')
                    ->label('Hình ảnh')
                    ->getStateUsing(fn($record) => HelperFunction::generateURLFilePath($record->logo))
                    ->disk('public'),
                TextEntry::make('parent_id')
                    ->label('Danh mục cha')
                    ->numeric()
                    ->default('Không có danh mục cha'),
                TextEntry::make('status')
                    ->label('Trạng thái')
                    ->badge()
                    ->color(fn($state) => match ($state) {
                        CategoryStatus::ACTIVE->value => 'success',
                        CategoryStatus::INACTIVE->value => 'warning',
                        default => 'default',
                    })->formatStateUsing(fn($state) => $state == CategoryStatus::ACTIVE->value ? 'Hoạt động' : 'Không hoạt động'),
                TextEntry::make('deleted_at')
                    ->label('Ngày xóa')
                    ->dateTime(),
                TextEntry::make('created_at')
                    ->label('Ngày tạo')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->dateTime(),
            ]);
    }
}
