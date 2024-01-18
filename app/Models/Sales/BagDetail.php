<?php

namespace App\Models\Sales;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BagDetail extends Model
{
    use HasFactory,SoftDeletes;
    public function sales(){
        return $this->belongsTo(Sales::class,'sales_id','id');
    }
}
