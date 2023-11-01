<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockAdjustmentDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'stock_adjustment_id',
        'material_id',
        'type',
        'unit',
        'quantity',
        'material_name'
    ];
}