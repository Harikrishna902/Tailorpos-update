<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesReturnDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'sales_return_id',
        'type',
        'tax_amount',
        'quantity',
        'item_id',
        'item_name',
        'rate',
        'total',
        'unit_type',
        'invoice_detail_id'
    ];
}