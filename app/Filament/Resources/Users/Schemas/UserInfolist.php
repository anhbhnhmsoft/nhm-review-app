<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Utils\HelperFunction;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Storage;

class UserInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name')
                ->label('Tên'),
                TextEntry::make('email')
                    ->label('Email'),
                ImageEntry::make('avatar_path')
                    ->label('Ảnh đại diện')
                    ->circular()
                    ->state(function ($record) {
                        if (! empty($record->avatar_path)) {
                            return HelperFunction::generateURLImagePath($record->avatar_path);
                        }
                        return HelperFunction::generateUiAvatarUrl($record->name, $record->email);
                    }),
                TextEntry::make('phone')
                    ->label('Số điện thoại'),
                TextEntry::make('address')
                    ->label('Địa chỉ'),
                TextEntry::make('email_verified_at')
                    ->label('Ngày xác thực email')
                    ->dateTime(),
                TextEntry::make('deleted_at')
                    ->label('Ngày xóa')
                    ->dateTime(),
                TextEntry::make('created_at')
                    ->label('Ngày tạo')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->label('Ngày cập nhật')
                    ->dateTime(),
            ]);
    }
}
