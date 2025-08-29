<?php

namespace App\Models;

use App\Utils\HelperFunction;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Utility extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'name', 'description', 'icon_svg'];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = HelperFunction::getTimestampAsId();
            }
        });
    }

    // Mối quan hệ với bảng store_utility
    public function stores()
    {
        return $this->belongsToMany(Store::class, 'store_utility', 'utility_id', 'store_id');
    }
}
