<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Utils\Constants\UserRole;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Tên')
                    ->required(),
                TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->required(),
                FileUpload::make('avatar_path')
                    ->label('Ảnh đại diện')
                    ->image()
                    ->imageEditor()
                    ->disk('public')
                    ->directory('avatars')
                    ->visibility('public')
                    ->nullable(),
                Hidden::make('role')
                    ->default(UserRole::USER->value),
                TextInput::make('phone')
                    ->label('Số điện thoại')
                    ->tel(),
                TextInput::make('address')
                    ->label('Địa chỉ'),
                Textarea::make('introduce')
                    ->label('Giới thiệu')
                    ->columnSpanFull(),

                DateTimePicker::make('email_verified_at'),
                TextInput::make('password')
                    ->label('Mật khẩu')
                    ->password()
                    ->required(),
            ]);
    }
}
