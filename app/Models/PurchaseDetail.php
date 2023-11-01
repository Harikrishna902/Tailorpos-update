<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseDetail extends Model
{
    use HasFactory;

    //relationship with the parent purchase
    public function purchase() {
        return $this->belongsTo(Purchase::class,'purchase_id','id');
    }
}