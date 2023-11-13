<?php

namespace App\Models\Sales;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BagDetail extends Model
{
    use HasFactory;
    public function sales(){
        return $this->belongsTo(Sales::class,'sales_id','id');
    }
}
