<?php

namespace App\Models;

use App\Utils\HelperFunction;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebsiteInfo extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'slug',
        'title',
        'content',
        'image_path',
        'seo_title',
        'seo_description',
        'seo_keywords',
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
