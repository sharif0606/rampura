<?php

namespace App\Models\Vouchers;

use App\Models\Customers\Customer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalVoucherBkdns extends Model
{
    use HasFactory,SoftDeletes;
    public function customer(){
        return $this->belongsTo(Customer::class);
    }
}
