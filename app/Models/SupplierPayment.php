<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierPayment extends Model
{
    use HasFactory;
    
    //relationship with the supplier
    public function supplier() {
        return $this->belongsTo(Supplier::class,'supplier_id','id');
    }

    //relationship with the created user
    public function createdBy() {
        return $this->belongsTo(User::class,'created_by','id');
    }
}