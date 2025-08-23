<?php

namespace App\Models;

use App\Utils\HelperFunction;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Article extends Model
{
    use HasFactory;
    use SoftDeletes;


    protected $fillable = [
        'id',
        'category_id',
        'slug',
        'title',
        'content',
        'author',
        'image_path',
        'view',
        'sort',
        'type',
        'seo_title',
        'seo_description',
        'seo_keywords',
        'status',
    ];

    protected $casts = [
        'view' => 'integer',
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = HelperFunction::getTimestampAsId();
            }
        });
    }
}
