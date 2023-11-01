<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerMeasurement extends Model
{
    use HasFactory;
    protected $fillable = [
        'customer_id',
        'measurement_id',
        'unit',
        'notes'
    ];
}