<?php

namespace App\Filament\Resources\Categories\Schemas;

use App\Utils\Constants\CategoryStatus;
use App\Utils\HelperFunction;
use App\Models\Category;
use App\Utils\Constants\StoragePath;
use CodeWithDennis\FilamentSelectTree\SelectTree;
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
                    ->live(debounce: 2000)
                    ->afterStateUpdated(function ($state, callable $set) {
                        if (!$state) {
                            $set('slug', '');
                            return;
                        };
                        $baseSlug = Str::slug($state);
                        $slug = $baseSlug;
                        $set('slug', $slug);
                    })
                    ->required(),
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
                SelectTree::make('parent_id')
                    ->label('Danh mục cha')
                    ->relationship('parent','name','parent_id')
                    ->searchable()
                    ->placeholder('Chọn danh mục cha')
                    ->nullable(),
                FileUpload::make('logo')
                    ->label('Hình ảnh')
                    ->image()
                    ->imageEditor()
                    ->storeFiles(false)
                    ->disk('public')
                    ->directory(StoragePath::CATEGORY_PATH->value)
                    ->visibility('public')
                    ->maxSize(10240)
                    ->helperText('Vui lòng chọn ảnh đại diện cho danh mục. Định dạng hợp lệ: JPG, PNG. Dung lượng tối đa 10MB.')
                    ->downloadable()
                    ->previewable()
                    ->openable()
                    ->required()
                    ->columnSpanFull()
                    ->validationMessages([
                        'required' => 'Vui lòng chọn ảnh đại diện cho danh mục.',
                        'image' => 'Tệp tải lên phải là hình ảnh hợp lệ (JPG, PNG).',
                        'maxSize' => 'Dung lượng ảnh không được vượt quá 10MB.',
                ]),
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
