<?php

namespace App\Models;

use App\Utils\HelperFunction;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Booking extends Model
{
    use HasFactory;
    use SoftDeletes;


    protected $fillable = [
        'id',
        'store_id',
        'customer_name',
        'customer_phone',
        'customer_email',
        'note',
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

}
