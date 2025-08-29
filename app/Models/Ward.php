<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ward extends Model
{

    use HasFactory;

    protected $fillable = ['name', 'code', 'division_type', 'district_code'];

    // Mối quan hệ với bảng district
    public function district()
    {
        return $this->belongsTo(District::class, 'district_code', 'code');
    }

    public function stores()
    {
        return $this->hasMany(Store::class, 'ward_code', 'code');
    }
}
