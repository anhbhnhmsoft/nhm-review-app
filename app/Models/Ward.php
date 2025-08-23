<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ward extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'english_name',
        'administrative_level',
        'decree',
        'province_code',
    ];

    // Mối quan hệ với bảng Province (Mỗi phường xã thuộc một tỉnh thành)
    public function province()
    {
        return $this->belongsTo(Province::class, 'province_code', 'code');
    }

    // Mối quan hệ với bảng Stores (Mỗi phường xã có nhiều cửa hàng)
    public function stores()
    {
        return $this->hasMany(Store::class, 'ward_code', 'code');
    }
}
