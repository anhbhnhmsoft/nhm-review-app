<?php

namespace App\Filament\Resources\Stores\Schemas;

use App\Filament\Forms\Components\LocationPicker;
use App\Models\Category;
use App\Models\District;
use App\Models\Province;
use App\Models\Store;
use App\Models\Utility;
use App\Models\Ward;
use App\Utils\Constants\CategoryStatus;
use App\Utils\Constants\StoragePath;
use App\Utils\Constants\StoreStatus;
use Closure;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Str;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Tabs\Tab;
class StoreForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->schema([
            Tabs::make("tab_create")
                ->tabs([
                    Tab::make('info')
                        ->label('Thông tin chung')
                        ->columns(2)
                        ->schema([
                            FileUpload::make('logo_path')
                                ->label('Ảnh đại diện địa điểm')
                                ->columnSpanFull()
                                ->image()
                                ->storeFiles(false)
                                ->disk('public')
                                ->helperText('Vui lòng chọn ảnh đại diện cho cửa hàng. Định dạng hợp lệ: JPG, PNG. Dung lượng tối đa 10MB.')
                                ->imageEditor()
                                ->maxSize(10240) // 10MB in kilobytes
                                ->directory(StoragePath::STORE_PATH->value)
                                ->downloadable()
                                ->previewable()
                                ->required()
                                ->openable()
                                ->validationMessages([
                                    'required' => 'Vui lòng chọn ảnh đại diện cho cửa hàng.',
                                    'image' => 'Tệp tải lên phải là hình ảnh hợp lệ (JPG, PNG).',
                                    'maxSize' => 'Dung lượng ảnh không được vượt quá 10MB.',
                                ]),
                            TextInput::make('name')
                                ->label('Tên địa điểm')
                                ->trim()
                                ->minLength(10)
                                ->placeholder('Tối thiểu 10 kí tự, tối đa 255 kí tự')
                                ->live(debounce: 500)
                                ->afterStateUpdated(function ($state, callable $set) {
                                    if (!$state) {
                                        $set('slug', '');
                                        return;
                                    };
                                    $set('slug', Str::slug($state));
                                })
                                ->required()
                                ->validationMessages([
                                    'required' => 'Vui lòng nhập tên địa điểm.',
                                    'minLength' => 'Tên địa điểm phải có ít nhất 10 ký tự.',
                                    'maxLength' => 'Tên địa điểm không được vượt quá 255 ký tự.',
                                ]),
                            TextInput::make('slug')
                                ->label('Đường dẫn slug')
                                ->required()
                                ->regex('/^[a-z0-9]+(?:-[a-z0-9]+)*$/')
                                ->helperText('Slug sẽ tự sinh ra khi bạn nhập tên địa điểm, hoặc baạn có thể tự sửa theo mong muốn')
                                ->rules([
                                    fn(Get $get) => function (string $attribute, $value, Closure $fail) use ($get) {
                                        $id = $get('id');
                                        if ($id) {
                                            // Nếu là chỉnh sửa, bỏ qua bản ghi hiện tại trong việc kiểm tra slug trùng lặp
                                            $check = Store::query()
                                                ->where('slug', $value)
                                                ->where('id', '!=', $id) // Loại trừ bản ghi hiện tại
                                                ->exists();
                                        } else {
                                            // Nếu là tạo mới, kiểm tra trùng lặp slug
                                            $check = Store::query()->where('slug', $value)->exists();
                                        }

                                        if ($check) {
                                            $fail('Slug này đã có địa điểm được sử dụng');
                                        }
                                    },
                                ])
                                ->validationMessages([
                                    'required' => 'Vui lòng nhập slug.',
                                    'regex' => 'Slug chỉ được chứa chữ thường, số và dấu gạch ngang, không có ký tự đặc biệt hoặc khoảng trắng.',
                                ]),
                            Select::make('category_id')
                                ->searchable()
                                ->label('Thuộc danh mục')
                                ->required()
                                ->columnSpanFull()
                                ->options(function (Get $get) {
                                    // if edit
                                    if ($get('id')) {
                                        return Category::query()
                                            ->where('status', CategoryStatus::ACTIVE->value)
                                            ->orWhere('id', $get('category_id'))
                                            ->limit(10)
                                            ->pluck('name', 'id')
                                            ->all();
                                    }else{
                                        return Category::query()
                                            ->where('status', CategoryStatus::ACTIVE->value)
                                            ->limit(10)
                                            ->pluck('name', 'id')
                                            ->all();
                                    }
                                })
                                ->getSearchResultsUsing(fn(string $search): array => Category::query()
                                    ->where('name', 'like', "%{$search}%")
                                    ->where('status', CategoryStatus::ACTIVE->value)
                                    ->limit(10)
                                    ->pluck('name', 'id')
                                    ->all())
                                ->loadingMessage('Chờ 1 chút...')
                                ->noSearchResultsMessage('Không tìm thấy danh mục.')
                                ->rules([
                                    fn(Get $get) => function (string $attribute, $value, Closure $fail) use ($get) {
                                        $value = Category::query()
                                            ->where('status', CategoryStatus::ACTIVE->value)
                                            ->where('id', $value)->exists();
                                        if (!$value) {
                                            $fail('Danh mục không đúng');
                                        }
                                    },
                                ])
                                ->validationMessages([
                                    'required' => 'Vui lòng chọn danh mục.',
                                ]),
                            Textarea::make('short_description')
                                ->label('Mô tả ngắn về địa điểm')
                                ->placeholder('Mô tả ngắn về địa điểm này')
                                ->required()
                                ->rows(8)
                                ->columnSpanFull()
                                ->validationMessages([
                                    'required' => 'Vui lòng nhập Mô tả ngắn về địa điểm',
                                ]),
                            RichEditor::make('description')
                                ->label('Chi tiết')
                                ->required()
                                ->columnSpanFull()
                                ->validationMessages([
                                    'required' => 'Vui lòng nhập Mô tả chi tiết',
                                ]),
                            Toggle::make('featured')
                                ->label("Là cửa hàng nổi bật")
                                ->default(false)
                                ->columnSpanFull()
                                ->validationMessages([
                                    'required' => 'Vui lòng tích chọn',
                                ])
                                ->required(),
                            Select::make('status')
                                ->label('Trạng thái')
                                ->required()
                                ->default(StoreStatus::ACTIVE->value)
                                ->options(StoreStatus::getOptions())
                                ->validationMessages([
                                    'required' => 'Vui lòng tích chọn',
                                ]),
                            TextInput::make('view')
                                ->label('Lượt xem')
                                ->nullable()
                                ->trim()
                                ->integer()
                                ->helperText('Chỉnh sửa lượt xem để tăng tương tác')
                                ->default(0),
                            TextInput::make('sorting_order')
                                ->label('Thứ tự sắp xếp')
                                ->integer()
                                ->helperText('Càng nhỏ, thì địa điểm càng lên đầu')
                                ->default(0)
                        ]),
                    Tab::make('more_info')
                        ->label('Thông tin liên hệ')
                        ->columns(2)
                        ->schema([
                            Select::make('store_utility')
                                ->multiple()
                                ->label('Tiện ích')
                                ->options(Utility::all()->pluck('name', 'id'))
                                ->searchable()
                                ->columnSpanFull()
                                ->required()
                                ->validationMessages([
                                    'required' => 'Vui lòng chọn ít nhất 1 tiện ích.',
                                ]),
                            TextInput::make('opening_time')
                                ->label('Giờ mở cửa')
                                ->placeholder('HH:MM')
                                ->required()
                                ->helperText('Nhập giờ mở cửa theo định dạng 24h, ví dụ: 08:00')
                                ->mask('99:99')
                                ->regex('/^(?:[01]\d|2[0-3]):[0-5]\d$/')
                                ->validationMessages([
                                    'required' => 'Vui lòng nhập giờ mở cửa.',
                                    'regex' => 'Giờ mở cửa phải theo định dạng 24h (HH:MM), ví dụ: 08:00 hoặc 23:59.',
                                ]),
                            TextInput::make('closing_time')
                                ->label('Giờ đóng cửa')
                                ->placeholder('HH:MM')
                                ->required()
                                ->helperText('Nhập giờ đóng cửa theo định dạng 24h, ví dụ: 22:00')
                                ->mask('99:99')
                                ->regex('/^(?:[01]\d|2[0-3]):[0-5]\d$/')
                                ->validationMessages([
                                    'required' => 'Vui lòng nhập giờ đóng cửa.',
                                    'regex' => 'Giờ đóng cửa phải theo định dạng 24h (HH:MM), ví dụ: 08:00 hoặc 23:59.',
                                ]),
                            Section::make('contact')
                                ->heading('Thông tin liên hệ')
                                ->columnSpanFull()
                                ->schema([
                                    TextInput::make('email')
                                        ->label('Email')
                                        ->email()
                                        ->trim()
                                        ->suffixIcon(Heroicon::Envelope)
                                        ->validationMessages([
                                            'email' => 'Địa chỉ email không hợp lệ. Vui lòng nhập đúng định dạng email.',
                                        ]),
                                    TextInput::make('phone')
                                        ->label('Số điện thoại liên hệ')
                                        ->suffixIcon(Heroicon::Phone)
                                        ->trim()
                                        ->helperText('Nhập số điện thoại liên hệ theo định dạng, ví dụ: 0912345678 hoặc +84912345678')
                                        ->regex('/^(0|\+84)[0-9]{9}$/')
                                        ->validationMessages([
                                            'regex' => 'Số điện thoại không hợp lệ. Vui lòng nhập đúng định dạng số Việt Nam.',
                                        ]),
                                    TextInput::make('facebook_page')
                                        ->label('Facebook Page')
                                        ->placeholder('Nhập đường dẫn Facebook Page'),
                                    TextInput::make('instagram_page')
                                        ->label('Instagram Page')
                                        ->placeholder('Nhập đường dẫn Instagram Page'),
                                    TextInput::make('tiktok_page')
                                        ->label('TikTok Page')
                                        ->placeholder('Nhập đường dẫn TikTok Page'),
                                    TextInput::make('youtube_page')
                                        ->label('YouTube Page')
                                        ->placeholder('Nhập đường dẫn YouTube Page'),
                                ]),
                            FileUpload::make('store_files')
                                ->label('Ảnh & Video')
                                ->multiple()
                                ->openable()
                                ->downloadable()
                                ->disk('public')
                                ->previewable()
                                ->panelLayout('grid')
                                ->storeFiles(false)
                                ->directory(StoragePath::STORE_PATH->value)
                                ->acceptedFileTypes(['image/*', 'video/*'])
                                ->maxSize(30720) // 30mb
                                ->helperText('Tải lên nhiều ảnh (tối đa 10MB/ảnh) hoặc video (tối đa 30MB/video). Định dạng hợp lệ: JPG, PNG, MP4, v.v.')
                                ->validationMessages([
                                    'maxSize' => 'Dung lượng ảnh không được vượt quá 10MB. Dung lượng video không được vượt quá 30MB.',
                                ])
                                ->columnSpanFull(),
                        ]),
                    Tab::make('location')
                        ->label('Vị trí')
                        ->columns(2)
                        ->schema([
                            Select::make('province_code')
                                ->label('Tỉnh thành')
                                ->options(Province::all()->pluck('name', 'code'))
                                ->searchable()
                                ->columnSpanFull()
                                ->live()
                                ->required()
                                ->validationMessages([
                                    'required' => 'Vui lòng chọn địa chỉ.',
                                ]),
                            Select::make('district_code')
                                ->label('Quận, Huyện')
                                ->options(function (Get $get) {
                                    if ($get('province_code')) {
                                        return District::query()->where('province_code', $get('province_code'))->pluck('name', 'code')->all();
                                    }
                                    return null;
                                })
                                ->columnSpanFull()
                                ->searchable()
                                ->live()
                                ->required()
                                ->validationMessages([
                                    'required' => 'Vui lòng chọn địa chỉ.',
                                ]),
                            Select::make('ward_code')
                                ->label('Phường, Xã')
                                ->searchable()
                                ->columnSpanFull()
                                ->options(function (Get $get) {
                                    if ($get('district_code')) {
                                        return Ward::query()->where('district_code', $get('district_code'))->pluck('name', 'code')->all();
                                    }
                                    return null;
                                })
                                ->live()
                                ->required()
                                ->validationMessages([
                                    'required' => 'Vui lòng chọn địa chỉ.',
                                ]),
                            LocationPicker::make('store_location')
                                ->label('Vị trí chi tiết ')
                                ->columnSpanFull()
                                ->defaultLocation(21.0285, 105.8542)
                                ->zoom(15)
                                ->height(500)
                                ->required()
                                ->validationMessages([
                                    'required' => 'Vui lòng chọn địa chỉ chi tiết.',
                                ]),
                        ]),
                ])
                ->columnSpanFull()

        ]);
    }
}
