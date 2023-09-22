<?php

namespace App\Models\Suppliers;

use App\Models\Suppliers\SupplierPaymentDetails;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierPayment extends Model
{
    use HasFactory;
    public function payment(){
        return $this->hasMany(SupplierPaymentDetails::class,'supplier_payment_id','id');
    }
}
