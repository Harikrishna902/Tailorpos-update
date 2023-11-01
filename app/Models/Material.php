<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'price',
        'unit',
        'opening_stock',
        'is_active',
        'created_by'
    ];
    
    //relationship with the purchased details (to calculate stock)
    public function purchase()
    {
        return $this->hasMany(PurchaseDetail::class, 'material_id', 'id');
    }

    //relationship with the stock transfer details (to calculate stock)
    public function transfer()
    {
        return $this->hasMany(StockTransferDetail::class, 'material_id', 'id');
    }

    //relationship with stock adjustment details model (to calculate stock)
    public function adjustment()
    {
        return $this->hasMany(StockAdjustmentDetail::class, 'material_id', 'id');
    }

    //relationship with the sales containing this item
    public function sales()
    {
        return $this->hasMany(InvoiceDetail::class, 'item_id', 'id');
    }

    //relationship with sales details where the sales use this material
    public function salesMaterial()
    {
        return $this->sales()->whereType(2);
    }
}