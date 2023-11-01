<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;
    protected $fillable = [
        'date',
        'expense_head_id',
        'amount',
        'payment_mode',
        'tax_included',
        'tax_percentage',
        'financial_year_id',
        'created_by',
        'title',
        'note'
    ];

    protected $casts = [
        'date' => 'date'
    ];

    //Relationship towards expense head
    public function head()
    {
        return $this->hasOne(ExpenseHead::class, 'id', 'expense_head_id');
    }

    //Relationship towards the created user
    public function createdBy()
    {
        return $this->hasOne(User::class, 'id', 'created_by');
    }
}