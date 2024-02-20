<?php

namespace App\Models\Return;

use App\Models\Expenses\ExpenseOfPurchase;
use App\Models\Settings\Branch;
use App\Models\Suppliers\Supplier;
use App\Models\Settings\Warehouse;
use App\Models\Stock\Stock;
use App\Models\Suppliers\SupplierPaymentDetails;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase_return extends Model
{
    use HasFactory;
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
        return $this->hasMany(Purchase_return_details::class,'purchase_return_id','id');
    }
    public function expense(){
        return $this->hasMany(ExpenseOfPurchase::class,'purchase_id','id');
    }
    public function stock(){
        return $this->hasMany(Stock::class,'purchase_id','id');
    }
    public function payment(){
        return $this->hasMany(SupplierPaymentDetails::class,'purchase_id','id');
    }
}
