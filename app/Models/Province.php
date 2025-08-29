<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'code', 'division_type'
    ];

    public function districts()
    {
        return $this->hasMany(District::class, 'province_code', 'code');
    }
    public function stores()
    {
        return $this->hasMany(Store::class, 'province_code', 'code');
    }
}
