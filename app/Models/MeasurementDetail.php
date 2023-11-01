<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeasurementDetail extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'measurement_attributes_id',
        'measurement_id',
    ];

    //relationship with measurement attributes
    public function attribute() {
        return $this->belongsTo(MeasurementAttribute::class,'measurement_attributes_id','id');
    }
}