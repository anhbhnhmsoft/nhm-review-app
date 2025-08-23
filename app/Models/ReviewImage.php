<?php

namespace App\Models;

use App\Utils\HelperFunction;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReviewImage extends Model
{
    use HasFactory;
    use SoftDeletes;


    protected $fillable = [
        'id',
        'review_id',
        'image_path',
        'image_name',
        'image_extension',
        'image_size',
        'image_type'
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = HelperFunction::getTimestampAsId();
            }
        });
    }

    // Mối quan hệ với bảng Review (Một hình ảnh thuộc một đánh giá)
    public function review()
    {
        return $this->belongsTo(Review::class);
    }
}
