<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesReturnPayment extends Model
{
    use HasFactory;
    protected $fillable = [
        'date',
        'customer_id',
        'customer_name',
        'invoice_id',
        'paid_amount',
        'payment_mode',
        'note',
        'created_by',
        'financial_year_id',
        'branch_id',
        'payment_type',
        'sales_return_id'
    ];
    //On Modal Events
    protected static function boot()
    {
        parent::boot();
        //When Creating the model generate payment number.
        static::creating(function ($model) {
            $model->voucher_no = generateSPno();
        });
    }

    //relationship with the created user
    public function createdBy()
    {
        return $this->hasOne(User::class, 'id', 'created_by');
    }

    //relationship with the customer
    public function customer()
    {
        return $this->hasOne(Customer::class, 'id', 'customer_id');
    }

    //relationship with the invoice
    public function invoice()
    {
        return $this->hasOne(Invoice::class, 'id', 'invoice_id');
    }
}