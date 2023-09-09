<?php

namespace App\Models\Purchases;

use App\Models\Products\Product;
use App\Models\Suppliers\Supplier;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase_details extends Model
{
    use HasFactory;
    public function product(){
        return $this->belongsTo(Product::class);
    }
    public function supplier(){
        return $this->belongsTo(Supplier::class);
    }
}
