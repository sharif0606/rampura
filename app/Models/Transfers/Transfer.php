<?php

namespace App\Models\Transfers;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Settings\Branch;
use App\Models\Settings\Warehouse;


class Transfer extends Model
{
    use HasFactory;

    public function branch(){
        return $this->belongsTo(Branch::class);
    }
    public function warehousef(){
        return $this->belongsTo(Warehouse::class,'warehouse_form','id');
    }
    public function warehouset(){
        return $this->belongsTo(Warehouse::class,'warehouse_to','id');
    }
    
}
