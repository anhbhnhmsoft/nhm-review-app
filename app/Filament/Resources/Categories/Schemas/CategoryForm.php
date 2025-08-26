<?php

namespace App\Filament\Resources\Categories\Schemas;

use App\Utils\Constants\CategoryStatus;
use App\Utils\HelperFunction;
use App\Models\Category;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;


class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Tên danh mục')
                    ->live(debounce: 3000)
                    ->afterStateUpdated(function ($state, callable $set) {
                        if (!$state) {
                            $set('slug', '');
                            return;
                        };
                        $baseSlug = Str::slug($state);
                        $slug = $baseSlug . '-' . HelperFunction::getTimestampAsId();
                        $set('slug', $slug);
                    })
                    ->required(),
                TextInput::make('name_home_page')
                    ->label('Tên ở trang chủ'),
                Toggle::make('show_index_home_page')
                    ->label('Hiển thị ở trang chủ')
                    ->live()
                    ->required(),
                Toggle::make('show_header_home_page')
                    ->label('Hiển thị trên header trang chủ')
                    ->visible(fn (callable $get) => $get('show_index_home_page'))
                    ->required(),
                TextInput::make('slug')
                    ->label('Đường dẫn')
                    ->readOnly()
                    ->required()
                    ->unique(ignoreRecord: true),
                FileUpload::make('logo')
                    ->label('Hình ảnh')
                    ->image()
                    ->imageEditor()
                    ->required(),
                Select::make('parent_id')
                    ->label('Danh mục cha')
                    ->options(Category::where('id', '!=', request()->route('record') ?? 0)->pluck('name', 'id'))
                    ->searchable()
                    ->placeholder('Chọn danh mục cha (để trống nếu là danh mục gốc)'),
                Textarea::make('description')
                    ->label('Mô tả')
                    ->columnSpanFull(),
                Select::make('status')
                    ->label('Trạng thái')
                    ->options(CategoryStatus::getOptions())
                    ->required(),
            ]);
    }
}
