<?php

namespace App\Models;

use App\Utils\HelperFunction;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'store_id', 'file_path', 'file_name', 'file_extension', 'file_size', 'file_type'
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = HelperFunction::getTimestampAsId();
            }
        });
    }


    // Mối quan hệ với bảng Store (Một tệp thuộc một cửa hàng)
    public function store()
    {
        return $this->belongsTo(Store::class);
    }
}
