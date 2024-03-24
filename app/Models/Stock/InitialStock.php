<?php

namespace App\Models\Stock;

use App\Models\Settings\Branch;
use App\Models\Settings\Warehouse;
use App\Models\Suppliers\Supplier;
use App\Models\Suppliers\SupplierPaymentDetails;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InitialStock extends Model
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
    public function createdBy(){
        return $this->belongsTo(User::class,'created_by','id');
    }
    public function updatedBy(){
        return $this->belongsTo(User::class,'updated_by','id');
    }
    public function purchase_lot(){
        return $this->hasMany(InitialStockDetail::class,'initial_stock_id','id');
    }
    public function stock(){
        return $this->hasMany(Stock::class,'initial_stock_id','id');
    }
}
