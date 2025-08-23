<?php

namespace App\Models;

use App\Utils\HelperFunction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Banner extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'id',
        'banner_index',
        'link',
        'image_path',
        'sort',
        'show',
    ];

    protected $casts = [
        'banner_index' => 'boolean',
        'show' => 'boolean',
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
