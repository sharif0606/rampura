<?php

namespace App\Models\Vouchers;

use App\Models\Suppliers\Supplier;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierVoucher extends Model
{
    use HasFactory;
    public function supplier(){
        return $this->belongsTo(Supplier::class);
    }
}
