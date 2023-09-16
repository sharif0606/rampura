<?php

namespace App\Models\Vouchers;

use App\Models\Customers\Customer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerVoucher extends Model
{
    use HasFactory;
    public function customer(){
        return $this->belongsTo(Customer::class);
    }
}
