<?php

namespace App\Models;

use App\Utils\HelperFunction;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Store extends Model
{
    use HasFactory;
    use SoftDeletes;


    protected $fillable = [
        'id',
        'name',
        'slug',
        'category_id',
        'province_code',
        'ward_code',
        'address',
        'latitude',
        'longitude',
        'google_map_place_id',
        'logo_path',
        'short_description',
        'description',
        'phone',
        'email',
        'website',
        'facebook_page',
        'instagram_page',
        'tiktok_page',
        'youtube_page',
        'opening_time',
        'closing_time',
        'status',
        'view',
        'featured',
        'sorting_order',
    ];

    protected $casts = [
        'view' => 'integer',
        'featured' => 'boolean',
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = HelperFunction::getTimestampAsId();
            }
        });
    }

    // Mối quan hệ với bảng Category (Một cửa hàng thuộc một danh mục)
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Mối quan hệ với bảng Province (Một cửa hàng thuộc một tỉnh thành)
    public function province()
    {
        return $this->belongsTo(Province::class, 'province_code', 'code');
    }

    // Mối quan hệ với bảng Ward (Một cửa hàng thuộc một phường xã)
    public function ward()
    {
        return $this->belongsTo(Ward::class, 'ward_code', 'code');
    }

    // Mối quan hệ với bảng StoreFiles (Một cửa hàng có nhiều tệp đính kèm)
    public function storeFiles()
    {
        return $this->hasMany(StoreFile::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    // Mối quan hệ với bảng Reviews (Một cửa hàng có nhiều đánh giá)
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
