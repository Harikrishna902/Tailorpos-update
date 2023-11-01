<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OnlineAppointment extends Model
{
    use HasFactory;

    // Define a relationship with the Customer model.
    public function customer()
    {
        return $this->belongsTo(OnlineCustomer::class, 'customer_id', 'id');
    }

    // Define a relationship with the Branch model.
    public function branch()
    {
        return $this->belongsTo(User::class, 'branch_id', 'id');
    }
}
