<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OnlineOrder extends Model
{
    use HasFactory;

     //relationship with the products in the order
    public function details() {
        return $this->hasMany(OnlineOrderDetail::class,'order_id','id');
    }

    //created branch relationship 
    public function branch()
    {
        return $this->hasOne(User::class, 'id', 'branch_id');
    }

    // Define a relationship with the Customer model.
    public function customer()
    {
        return $this->belongsTo(OnlineCustomer::class, 'customer_id', 'id');
    }
}
