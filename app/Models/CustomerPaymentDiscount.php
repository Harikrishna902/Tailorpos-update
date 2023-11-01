<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerPaymentDiscount extends Model
{
    use HasFactory;

    //Relationship to the created user
    public function createdBy() {
        return $this->belongsTo(User::class,'created_by','id');
    }
}