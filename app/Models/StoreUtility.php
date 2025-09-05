<?php

namespace App\Models;

use App\Utils\HelperFunction;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreUtility extends Model
{
    use HasFactory;

    protected $table = 'store_utility';
    
    protected $fillable = [
        'id',
        'store_id', 
        'utility_id',
        'created_at',
        'updated_at'
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = HelperFunction::getTimestampAsId();
            }
        });
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function utility()
    {
        return $this->belongsTo(Utility::class);
    }
}
