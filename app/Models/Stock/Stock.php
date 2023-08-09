<?php

namespace App\Models\Stock;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    public function warehouses(){
        return $this->belongsTo(Warehouse::class,'warehouse_id','id');
    }

}
