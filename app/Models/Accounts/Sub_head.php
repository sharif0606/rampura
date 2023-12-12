<?php

namespace App\Models\Accounts;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sub_head extends Model
{
    use HasFactory;
    public function master_account(){
        return $this->belongsTo(Master_account::class,'master_head_id','id')->where(company());
    }

    public function child_one(){
        return $this->hasMany(Child_one::class)->where(company());
    }
}
