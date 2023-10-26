<?php

namespace App\Models\Stock;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Products\Product;
class Stock extends Model
{
    use HasFactory;

    public function warehouses(){
        return $this->belongsTo(Warehouse::class,'warehouse_id','id');
    }
    public function product(){
        return $this->belongsTo(Product::class);
    }

    public function beparian_purchase(){
        return $this->belongsTo(\App\Models\Purchases\Beparian_purchase::class);
    }

    public function purchase(){
        return $this->belongsTo(\App\Models\Purchases\Purchase::class);
    }
    public function regular_purchase(){
        return $this->belongsTo(\App\Models\Purchases\Regular_purchase::class,'regular_purchase_id','id');
    }
    public function sales(){
        return $this->belongsTo(\App\Models\Sales\Sales::class);
    }

}
