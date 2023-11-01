<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'invoice_id',
        'type',
        'tax_amount',
        'quantity',
        'item_id',
        'item_name',
        'rate',
        'total',
        'unit_type'
    ];

    //relationship with the salesman
    public function salesman()
    {
        return $this->hasOne(User::class, 'id', 'salesman_id');
    }
}