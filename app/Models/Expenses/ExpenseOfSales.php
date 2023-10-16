<?php

namespace App\Models\Expenses;

use App\Models\Accounts\Child_two;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Sales\Sales;
class ExpenseOfSales extends Model
{
    use HasFactory;
    public function expense(){
        return $this->belongsTo(Child_two::class,'child_two_id','id');
    }
    public function sales(){
        return $this->belongsTo(Sales::class);
    }
}
