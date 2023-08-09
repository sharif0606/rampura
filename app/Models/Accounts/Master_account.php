<?php

namespace App\Models\Accounts;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Master_account extends Model
{
    use HasFactory,SoftDeletes;
    public function sub_head(){
        return $this->hasMany(Sub_head::class,'master_head_id','id');
    }

}
