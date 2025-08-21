<?php

namespace App\Models;

use App\Utils\HelperFunction;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'store_id',
        'user_id',
        'rating',
        'review',
        'is_anonymous'
    ];

    protected function casts(): array
    {
        return [
            'is_anonymous' => 'boolean',
        ];
    }

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = HelperFunction::getTimestampAsId();
            }
        });
    }
    // Mối quan hệ với bảng Store (Một đánh giá thuộc một cửa hàng)
    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    // Mối quan hệ với bảng User (Một đánh giá thuộc một người dùng)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Mối quan hệ với bảng ReviewImage (Một đánh giá có nhiều hình ảnh)
    public function images()
    {
        return $this->hasMany(ReviewImage::class);
    }
}
