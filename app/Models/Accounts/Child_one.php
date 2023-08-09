<?php

namespace App\Models\Accounts;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Child_one extends Model
{
    use HasFactory,SoftDeletes;
    public function sub_head(){
        return $this->belongsTo(Sub_head::class);
    }

    public function child_two(){
        return $this->hasMany(Child_two::class);
    }
}
