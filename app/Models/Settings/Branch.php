<?php

namespace App\Models\Settings;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Currency\Currency;

class Branch extends Model
{
    use HasFactory,SoftDeletes;

    public function Currency(){
        return $this->belongsTo(Currency::class,'currency','id');
    }
}

