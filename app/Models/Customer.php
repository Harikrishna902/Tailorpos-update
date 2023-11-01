<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = ['date', 'file_number', 'first_name', 'second_name', 'family_name', 'phone_number_1', 'phone_number_2', 'address', 'notes', 'created_by', 'opening_balance', 'is_active', 'customer_group_id', 'email'];

    /* User relation */

    // Define a relationship with the CustomerGroup model.
    public function group()
    {
        return $this->belongsTo(CustomerGroup::class, 'customer_group_id', 'id');
    }

    // Define a relationship with multiple invoices associated with this customer.
    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'customer_id', 'id');
    }

    // Define a relationship with payments made by this customer.
    public function payments()
    {
        return $this->hasMany(InvoicePayment::class, 'customer_id', 'id');
    }

    // Define a relationship with sales payments made by this customer.
    public function salesPayments()
    {
        return $this->payments()->whereType(1);
    }

    // Define a relationship with sales return payments made by this customer.
    public function salesreturnpayments()
    {
        return $this->hasMany(SalesReturnPayment::class, 'customer_id', 'id');
    }

    // Define a relationship with sales returns associated with this customer.
    public function salesReturns()
    {
        return $this->hasMany(SalesReturn::class, 'customer_id', 'id');
    }

    // Define a relationship with payment discounts for this customer.
    public function paymentDiscounts()
    {
        return $this->hasMany(CustomerPaymentDiscount::class, 'customer_id', 'id');
    }

    /**
     * Calculate the customer's balance.
     *
     * @return string
     */
    public function getBalance()
    {
        // Calculate various sums and balances based on invoices, payments, and discounts.
        $invoices = $this->invoices->sum('total');
        $payments = $this->payments->sum('paid_amount');
        $salesreturns = $this->salesReturns->sum('total');
        $paymentdiscounts = $this->paymentDiscounts->sum('amount');
        $salesreturnpayments = $this->salesreturnpayments->sum('paid_amount');
        $debits = $this->opening_balance + $invoices + $salesreturnpayments;
        $credits = $payments + $salesreturns + $paymentdiscounts;

        // Determine if the balance is zero, positive, or negative and return a formatted string.
        if ($debits - $credits == 0) {
            return '0.00 Dr.'; 
        }
        if ($debits - $credits > 0) {
            return (number_format($debits - $credits, 2)) . ' Dr.';
        }
        return number_format((($debits - $credits) * -1), 2) . ' Cr.'; 
    }
}