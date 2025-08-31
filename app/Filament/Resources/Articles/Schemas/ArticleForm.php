<?php

namespace App\Filament\Resources\Articles\Schemas;

use App\Models\Article;
use App\Utils\Constants\ArticleStatus;
use App\Utils\Constants\ArticleType;
use App\Utils\Constants\StoragePath;
use Closure;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Str;

class ArticleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('article_content')
                    ->heading("Nội dung bài viết")
                    ->icon(function ($get){
                        if (!empty($get('id')) && $get('type') == ArticleType::FIXED->value){
                            return Heroicon::OutlinedExclamationTriangle;
                        }
                        return null;
                    })
                    ->description(function ($get){
                        if (!empty($get('id')) && $get('type') == ArticleType::FIXED->value){
                            return "Đây là bài viết cố định, bạn không thể chỉnh sửa tiêu đề, slug cũng như trạng thái và loại";
                        }
                        return null;
                    })
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        FileUpload::make('image_path')
                            ->label('Ảnh đại diện bài viết')
                            ->columnSpanFull()
                            ->image()
                            ->disk('public')
                            ->helperText('Định dạng hợp lệ: JPG, PNG. Dung lượng tối đa 10MB.')
                            ->imageEditor()
                            ->maxSize(10240) // 10MB
                            ->directory(StoragePath::ARTICLE_PATH->value)
                            ->downloadable()
                            ->previewable()
                            ->openable()
                            ->validationMessages([
                                'image' => 'Tệp tải lên phải là hình ảnh hợp lệ (JPG, PNG,...).',
                                'maxSize' => 'Dung lượng ảnh không được vượt quá 10MB.',
                            ]),
                        TextInput::make('title')
                            ->label('Tiêu đề')
                            ->trim()
                            ->disabled(function ($get){
                                if (!empty($get('id')) && $get('type') == ArticleType::FIXED->value){
                                    return true;
                                }
                                return false;
                            })
                            ->minLength(10)
                            ->maxLength(255)
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
                                'required' => 'Vui lòng nhập tiêu đề.',
                                'minLength' => 'Tên tiêu đề phải có ít nhất 10 ký tự.',
                                'maxLength' => 'Tên tiêu đề không được vượt quá 255 ký tự.',
                            ]),
                        TextInput::make('slug')
                            ->label('Đường dẫn slug')
                            ->disabled(function ($get){
                                if (!empty($get('id')) && $get('type') == ArticleType::FIXED->value){
                                    return true;
                                }
                                return false;
                            })
                            ->minLength(10)
                            ->maxLength(255)
                            ->trim()
                            ->required()
                            ->regex('/^[a-z0-9]+(?:-[a-z0-9]+)*$/')
                            ->helperText('Slug sẽ tự sinh ra khi bạn nhập tiêu đề, hoặc bạn có thể tự sửa theo mong muốn')
                            ->rules([
                                fn(Get $get) => function (string $attribute, $value, Closure $fail) use ($get) {
                                    $id = $get('id');
                                    if ($id) {
                                        // Nếu là chỉnh sửa, bỏ qua bản ghi hiện tại trong việc kiểm tra slug trùng lặp
                                        $check = Article::query()
                                            ->where('slug', $value)
                                            ->where('id', '!=', $id) // Loại trừ bản ghi hiện tại
                                            ->exists();
                                    } else {
                                        // Nếu là tạo mới, kiểm tra trùng lặp slug
                                        $check = Article::query()->where('slug', $value)->exists();
                                    }
                                    if ($check) {
                                        $fail('Slug này đã có bài viết được sử dụng');
                                    }
                                },
                            ])
                            ->validationMessages([
                                'required' => 'Vui lòng nhập slug.',
                                'regex' => 'Slug chỉ được chứa chữ thường, số và dấu gạch ngang, không có ký tự đặc biệt hoặc khoảng trắng.',
                                'minLength' => 'Phải có ít nhất 10 ký tự.',
                                'maxLength' => 'Không được vượt quá 255 ký tự.',
                            ]),
                        Select::make('type')
                            ->label('Thể loại')
                            ->required()
                            ->hidden(function ($get){
                                if (!empty($get('id')) && $get('type') == ArticleType::FIXED->value){
                                    return true;
                                }
                                return false;
                            })
                            ->default(ArticleType::PRESS->value)
                            ->options(ArticleType::getOptions())
                            ->validationMessages([
                                'required' => 'Vui lòng chọn ô này',
                            ]),
                        Select::make('status')
                            ->label('Trạng thái')
                            ->required()
                            ->hidden(function ($get){
                                if (!empty($get('id')) && $get('type') == ArticleType::FIXED->value){
                                    return true;
                                }
                                return false;
                            })
                            ->default(ArticleStatus::DRAFT->value)
                            ->options(ArticleStatus::getOptions())
                            ->validationMessages([
                                'required' => 'Vui lòng chọn ô này',
                            ]),
                        TextInput::make('author')
                            ->label('Tác giả')
                            ->required()
                            ->trim()
                            ->minLength(4)
                            ->maxLength(255)
                            ->columnSpanFull()
                            ->validationMessages([
                                'required' => 'Ô này là bắt buộc.',
                                'minLength' => 'Phải có ít nhất 4 ký tự.',
                                'maxLength' => 'Không được vượt quá 255 ký tự.',
                            ])
                        ,
                        RichEditor::make('content')
                            ->label('Nội dung bài viết')
                            ->required()
                            ->fileAttachmentsDisk('public')
                            ->fileAttachmentsDirectory(StoragePath::ARTICLE_PATH->value)
                            ->fileAttachmentsVisibility('public')
                            ->columnSpanFull()
                            ->validationMessages([
                                'required' => 'Vui lòng nhập Mô tả chi tiết',
                            ]),

                    ]),

                Section::make('article_seo')
                    ->heading("SEO")
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        TextInput::make('view')
                            ->label('Lượt xem')
                            ->nullable()
                            ->trim()
                            ->integer()
                            ->helperText('Chỉnh sửa lượt xem để tăng tương tác')
                            ->default(0),
                        TextInput::make('sort')
                            ->label('Thứ tự sắp xếp')
                            ->integer()
                            ->helperText('Càng nhỏ, thì địa điểm càng lên đầu')
                            ->default(0),
                      TextInput::make('seo_title')
                          ->label('SEO Title')
                          ->columnSpanFull()
                          ->nullable()
                          ->trim()
                          ->maxLength(60)
                          ->helperText('Tối đa 60 ký tự, hiển thị trong kết quả tìm kiếm.')
                          ->validationMessages([
                              'maxLength' => 'SEO title không được vượt quá 60 ký tự.',
                          ]),
                        TextInput::make('seo_keywords')
                            ->label('SEO Keywords')
                            ->columnSpanFull()
                            ->nullable()
                            ->trim()
                            ->helperText('Các từ khóa phân tách bằng dấu phẩy.')
                            ->maxLength(255)
                            ->validationMessages([
                                'maxLength' => 'SEO keywords không được vượt quá 255 ký tự.',
                            ]),
                      Textarea::make('seo_description')
                          ->label('SEO Description')
                          ->columnSpanFull()
                          ->nullable()
                          ->minLength(50)
                          ->maxLength(160)
                          ->helperText('Tốt nhất từ 50-160 ký tự cho mô tả SEO.')
                          ->validationMessages([
                              'minLength' => 'SEO description nên có ít nhất 50 ký tự.',
                              'maxLength' => 'SEO description không được vượt quá 160 ký tự.',
                          ]),
                    ])

            ]);
    }
}
