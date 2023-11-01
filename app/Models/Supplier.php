<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'email',
        'phone',
        'tax_number',
        'supplier_address',
        'opening_balance',
        'is_active',
        'created_by'
    ];
}