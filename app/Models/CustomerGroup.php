<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerGroup extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'is_active',
        'created_by'
    ];
    // Define a relationship with the Customers model.
    public function customers() {
        return $this->hasMany(Customer::class,'customer_group_id','id');
    }
}