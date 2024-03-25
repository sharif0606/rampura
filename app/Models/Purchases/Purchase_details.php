<?php

namespace App\Models\Purchases;

use App\Models\Products\Product;
use App\Models\Stock\Stock;
use App\Models\Suppliers\Supplier;
use App\Models\Suppliers\SupplierPayment;
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
    public function beparian_purchase(){
        return $this->belongsTo(Beparian_purchase::class,'beparian_purchase_id','id');
    }

    public function stock(){
        return $this->hasOne(Stock::class,'purchase_details_id','id');
    }
    public function purchase(){
        return $this->belongsTo(Purchase::class,'purchase_id','id');
    }
    public function regular_purchase(){
        return $this->belongsTo(Regular_purchase::class,'regular_purchase_id','id');
    }
}
