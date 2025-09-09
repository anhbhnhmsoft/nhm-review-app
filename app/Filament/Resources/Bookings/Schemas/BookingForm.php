<?php

namespace App\Filament\Resources\Bookings\Schemas;

use App\Models\Store;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class BookingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->schema([
            Select::make('store_id')
                ->label('Địa điểm')
                ->options(fn () => Store::query()->orderBy('name')->pluck('name', 'id')->all())
                ->searchable()
                ->preload()
                ->placeholder('Chọn địa điểm')
                ->native(false)
                ->columnSpanFull(),
            TextInput::make('customer_name')
                ->label('Tên khách hàng')
                ->required()
                ->maxLength(255)
                ->placeholder('Nhập tên khách hàng'),
            TextInput::make('customer_phone')
                ->label('Số điện thoại')
                ->required()
                ->maxLength(20)
                ->placeholder('Nhập số điện thoại'),
            TextInput::make('customer_email')
                ->label('Email')
                ->email()
                ->maxLength(255)
                ->placeholder('Nhập email (nếu có)'),
            Textarea::make('note')
                ->label('Ghi chú')
                ->rows(4)
                ->columnSpanFull()
                ->placeholder('Ghi chú thêm từ khách hàng (nếu có)'),
        ]);
    }
}