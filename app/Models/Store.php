<?php

namespace App\Models;

use App\Utils\Constants\StoreStatus;
use App\Utils\HelperFunction;
use Illuminate\Database\Eloquent\Builder;
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
        'district_code',
        'ward_code',
        'address',
        'latitude',
        'longitude',
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


    public function scopeCanShow(Builder $query): Builder
    {
        return $query->whereIn('status', [
            StoreStatus::ACTIVE->value,
            StoreStatus::PENDING->value,
        ]);
    }

    public function scopeNearBy(Builder $query, float $lat, float $lng, float $radiusKm = 5)
    {
        return $query
            ->select('*')
            ->selectRaw(
                '(6371 * acos(cos(radians(?)) * cos(radians(latitude))
                 * cos(radians(longitude) - radians(?))
                 + sin(radians(?)) * sin(radians(latitude)) )) AS distance',
                [$lat, $lng, $lat]
            )
            ->having('distance', '<=', $radiusKm)
            ->orderBy('distance');
    }

    public function getOverallAverageRating()
    {
        $ratings = $this->reviews()
            ->selectRaw('AVG(rating_location) as avg_location,
                         AVG(rating_space) as avg_space,
                         AVG(rating_quality) as avg_quality,
                         AVG(rating_serve) as avg_serve')
            ->first();

        if ($ratings) {
            $totalAverage = (
                    $ratings->avg_location +
                    $ratings->avg_space +
                    $ratings->avg_quality +
                    $ratings->avg_serve
                ) / 4;

            return round($totalAverage, 1);
        }

        return 0;
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

    // Mối quan hệ với bảng Ward (Một cửa hàng thuộc một Quận huyện)
    public function district()
    {
        return $this->belongsTo(District::class, 'district_code', 'code');
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

    // Mối quan hệ với bảng store_utility
    public function utilities()
    {
        return $this->belongsToMany(Utility::class, 'store_utility', 'store_id', 'utility_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_store', 'store_id', 'user_id');
    }
}
