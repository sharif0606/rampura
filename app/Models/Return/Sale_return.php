<?php

namespace App\Models\Return;

use App\Models\Customers\Customer;
use App\Models\Customers\CustomerPayment;
use App\Models\Customers\CustomerPaymentDetails;
use App\Models\Expenses\ExpenseOfSales;
use App\Models\Settings\Branch;
use App\Models\Settings\Warehouse;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sale_return extends Model
{
    use HasFactory,SoftDeletes;
    public function customer(){
        return $this->belongsTo(Customer::class);
    }
    public function branch(){
        return $this->belongsTo(Branch::class);
    }
    public function createdBy(){
        return $this->belongsTo(User::class,'created_by','id');
    }
    public function updatedBy(){
        return $this->belongsTo(User::class,'updated_by','id');
    }

    public function warehouse(){
        return $this->belongsTo(Warehouse::class);
    }

    public function sale_lot(){
        return $this->hasMany(Sale_return_detail::class,'sales_return_id','id');
    }

    public function expense(){
        return $this->hasMany(ExpenseOfSales::class,'sales_return_id','id');
    }
    public function payment(){
        return $this->hasMany(CustomerPaymentDetails::class,'sales_id','id');
    }
    public function payment_full(){
        return $this->hasMany(CustomerPayment::class,'sales_id','id');
    }
}
