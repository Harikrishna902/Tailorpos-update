<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesReturn extends Model
{
    use HasFactory;
    protected $fillable = [
        'date',
        'customer_name',
        'customer_phone',
        'customer_address',
        'customer_file_number',
        'customer_id',
        'tax_type',
        'discount',
        'sub_total',
        'tax_percentage',
        'tax_amount',
        'taxable_amount',
        'total',
        'total_quantity',
        'invoice_id',
        'created_by',
        'financial_year_id',
        'branch_id',
        'sales_return_number',
    ];

    //relationship with the created user
    public function createdBy()
    {
        return $this->hasOne(User::class, 'id', 'created_by');
    }

    //relationship with the invoice
    public function invoice()
    {
        return $this->hasOne(Invoice::class, 'id', 'invoice_id');
    }
    
    //relationship with the details of the return
    public function details() {
        return $this->hasMany(SalesReturnDetail::class,'sales_return_id','id');
    }

    //relationship with the payments made.
    public function payments() {
        return $this->hasMany(SalesReturnPayment::class,'sales_return_id','id');
    }
    
    /* invoice details relation */
    public function invoiceProductDetails() {
        return $this->hasMany(SalesReturnDetail::class,'sales_return_id','id');
    }
}