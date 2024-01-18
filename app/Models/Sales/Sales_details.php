<?php

namespace App\Models\Sales;

use App\Models\Products\Product;
use App\Models\Customers\Customer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sales_details extends Model
{
    use HasFactory,SoftDeletes;
    public function product(){
        return $this->belongsTo(Product::class);
    }
    public function sales(){
        return $this->belongsTo(Sales::class);
    }
    public function customer(){
        return $this->belongsTo(Customer::class);
    }
    public function bag_detail(){
        return $this->hasMany(BagDetail::class,'sales_details_id','id');
    }
    
    
    // public function purchase(){
    //     return $this->belongsTo(Purchase::class,'purchase_id','id');
    // }
}
