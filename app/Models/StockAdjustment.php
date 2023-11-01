<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockAdjustment extends Model
{
    use HasFactory;
    protected $fillable = [
        'date',
        'total_items',
        'branch_id',
        'created_by'
    ];

    //relationship with the items in stock adjustment details
    public function items()
    {
        return $this->hasMany(StockAdjustmentDetail::class, 'stock_adjustment_id', 'id');
    }

    //relationship with the created user
    public function createdBy()
    {
        return $this->hasOne(User::class, 'id', 'created_by');
    }
}