<?php

namespace App\Models\Expenses;

use App\Models\Accounts\Child_two;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Purchases\Beparian_purchase;
use App\Models\Purchases\Purchase;
use App\Models\Purchases\Regular_purchase;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExpenseOfPurchase extends Model
{
    use HasFactory,SoftDeletes;
    public function expense(){
        return $this->belongsTo(Child_two::class,'child_two_id','id');
    }
    public function purchase(){
        return $this->belongsTo(Purchase::class,'purchase_id','id');
    }
    public function regular_purchase(){
        return $this->belongsTo(Regular_purchase::class,'regular_purchase_id','id');
    }
    public function beparian_purchase(){
        return $this->belongsTo(Beparian_purchase::class,'beparian_purchase_id','id');
    }
}
