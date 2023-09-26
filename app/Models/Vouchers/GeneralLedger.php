<?php

namespace App\Models\Vouchers;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneralLedger extends Model
{
    use HasFactory;

    public function master_head(){
        return $this->belongsTo('App\Models\Accounts\Master_account','master_account_id','id');
    }
    public function sub_head(){
        return $this->belongsTo('App\Models\Accounts\Sub_head','sub_head_id','id');
    }
    public function chield_one(){
        return $this->belongsTo('App\Models\Accounts\Child_one','child_one_id','id');
    }
    public function chield_two(){
        return $this->belongsTo('App\Models\Accounts\Child_two','child_two_id','id');
    }
}
