<?php

namespace App\Models\Settings;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Warehouse extends Model
{
    use HasFactory,SoftDeletes;

    public function branch() {
        return $this->belongsTo(Branch::class,'branch_id','id');
    }
    
    
}
