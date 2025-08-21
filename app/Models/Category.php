<?php

namespace App\Models;

use App\Utils\HelperFunction;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'name',
        'slug',
        'logo',
        'parent_id',
        'description',
        'status'
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = HelperFunction::getTimestampAsId();
            }
        });
    }

    // Mối quan hệ cha - con với chính bảng categories (danh mục cha con)
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    // Mối quan hệ với bảng Store (Một danh mục có nhiều cửa hàng)
    public function stores()
    {
        return $this->hasMany(Store::class);
    }
}
