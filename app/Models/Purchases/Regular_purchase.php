<?php

namespace App\Models\Purchases;

use App\Models\Expenses\ExpenseOfPurchase;
use App\Models\Settings\Branch;
use App\Models\Suppliers\Supplier;
use App\Models\Settings\Warehouse;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Regular_purchase extends Model
{
    use HasFactory,SoftDeletes;
    public function supplier(){
        return $this->belongsTo(Supplier::class);
    }
    public function branch(){
        return $this->belongsTo(Branch::class);
    }
    public function warehouse(){
        return $this->belongsTo(Warehouse::class,'warehouse_id','id');
    }
    public function expense(){
        return $this->hasMany(ExpenseOfPurchase::class,'regular_purchase_id','id');
    }
}
