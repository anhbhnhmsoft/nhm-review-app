<?php

namespace App\Filament\Resources\Banners\Schemas;

use App\Utils\Constants\StoragePath;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class BannerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        FileUpload::make('image_path')
                            ->label('Ảnh banner')
                            ->columnSpanFull()
                            ->image()
                            ->disk('public')
                            ->helperText('Vui lòng chọn ảnh đại diện cho cửa hàng. Định dạng hợp lệ: JPG, PNG. Dung lượng tối đa 10MB.')
                            ->imageEditor()
                            ->maxSize(10240) // 10MB in kilobytes
                            ->directory(StoragePath::BANNER_PATH->value)
                            ->downloadable()
                            ->previewable()
                            ->required()
                            ->openable()
                            ->validationMessages([
                                'required' => 'Vui lòng chọn ảnh đại diện cho cửa hàng.',
                                'image' => 'Tệp tải lên phải là hình ảnh hợp lệ (JPG, PNG).',
                                'maxSize' => 'Dung lượng ảnh không được vượt quá 10MB.',
                            ]),
                        TextInput::make('alt_banner')
                            ->label('Văn bản thay thế (alt)')
                            ->maxLength(255)
                            ->columnSpanFull()
                            ->placeholder('Mô tả ngắn cho ảnh, tối đa 255 ký tự'),
                        Toggle::make('banner_index')
                            ->label("Hiển thị đầu trang chủ")
                            ->default(false)
                            ->required(),
                        Toggle::make('show')
                            ->label('Hiển thị')
                            ->default(true)
                            ->required(),
                        TextInput::make('link')
                            ->label('Link liên kết')
                            ->prefix('https://'),
                        TextInput::make('sort')
                            ->label('Thứ tự sắp xếp')
                            ->integer()
                            ->helperText('Càng nhỏ, thì địa điểm càng lên đầu')
                            ->default(0),
                    ])
            ]);
    }
}
