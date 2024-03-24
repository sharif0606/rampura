<?php

namespace App\Models\Stock;

use App\Models\Products\Product;
use App\Models\Suppliers\Supplier;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InitialStockDetail extends Model
{
    use HasFactory,SoftDeletes;

    public function product(){
        return $this->belongsTo(Product::class);
    }
    public function supplier(){
        return $this->belongsTo(Supplier::class);
    }
    public function purchase(){
        return $this->belongsTo(InitialStock::class,'initial_stock_id','id');
    }
}
