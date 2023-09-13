<?php

namespace App\Models\Sales;

use App\Models\Products\Product;
use App\Models\Settings\Branch;
use App\Models\Customers\Customer;
use App\Models\Expenses\ExpenseOfSales;
use App\Models\Stock\Stock;
use App\Models\Settings\Warehouse;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sales extends Model
{
    use HasFactory,SoftDeletes;
    public function customer(){
        return $this->belongsTo(Customer::class);
    }
    public function branch(){
        return $this->belongsTo(Branch::class);
    }
    public function warehouse(){
        return $this->belongsTo(Warehouse::class);
    }
    public function expense(){
        return $this->hasMany(ExpenseOfSales::class,'sales_id','id');
    }
}
