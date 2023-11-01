<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockTransfer extends Model
{
    use HasFactory;

    //relationship with the created user
    public function createdBy() {
        return $this->belongsTo(User::class,'created_by','id');
    }

    //relationship with the parent branch
    public function branch() {
        return $this->belongsTo(User::class,'warehouse_to','id');
    }
}