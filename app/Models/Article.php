<?php

namespace App\Models;

use App\Utils\HelperFunction;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'category_id',
        'slug',
        'title',
        'content',
        'author',
        'image_path',
        'view_count',
        'seo_title',
        'seo_description',
        'seo_keywords',
        'status',
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = HelperFunction::getTimestampAsId();
            }
        });
    }

    // Mối quan hệ với bảng ArticleCategory (Một bài viết thuộc một danh mục)
    public function category()
    {
        return $this->belongsTo(ArticleCategory::class);
    }
}
