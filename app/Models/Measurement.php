<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Measurement extends Model
{
    use HasFactory;

    //relationship with measurement details
    public function orderDetails()
    {
        return $this->hasMany(MeasurementDetail::class,'measurement_id','id');
    }
}