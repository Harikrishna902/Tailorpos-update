<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;
    protected $fillable = [
        'date',
        'invoice_number',
        'customer_name',
        'customer_phone',
        'customer_address',
        'customer_file_number',
        'customer_id',
        'salesman_id',
        'tax_type',
        'discount',
        'sub_total',
        'tax_percentage',
        'tax_amount',
        'taxable_amount',
        'total',
        'notes',
        'total_quantity',
        'created_by',
        'financial_year_id',
        'branch_id'
    ];

    //created user relationship 
    public function createdBy()
    {
        return $this->hasOne(User::class, 'id', 'created_by');
    }

    //sales man relationship
    public function salesman()
    {
        return $this->hasOne(User::class, 'id', 'salesman_id');
    }
    
    //relationship with the products in the invoice
    public function details() {
        return $this->hasMany(InvoiceDetail::class,'invoice_id','id');
    }

    /* invoice details relation */
    public function invoiceProductDetails() {
        return $this->hasMany(InvoiceDetail::class,'invoice_id','id');
    }
}