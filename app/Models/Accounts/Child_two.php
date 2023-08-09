<?php

namespace App\Models\Accounts;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Child_two extends Model
{
    use HasFactory,SoftDeletes;
    public function child_one(){
        return $this->belongsTo(Child_one::class);
    }
}
