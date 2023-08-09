<?php

namespace App\Models\Settings;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Settings\Location\Country;
use App\Models\Settings\Location\District;
use App\Models\Settings\Location\Division;
use App\Models\Settings\Location\Thana;
use App\Models\Settings\Location\Upazila;
use App\Models\Currency\Currency;

class Company extends Model
{
    use HasFactory,SoftDeletes;
    public function country(){
        return $this->belongsTo(Country::class);
    }
    public function division(){
        return $this->belongsTo(Division::class);
    }
    public function district(){
        return $this->belongsTo(District::class);
    }
    public function upazila(){
        return $this->belongsTo(Upazila::class);
    }
    public function thana(){
        return $this->belongsTo(Thana::class);
    }
    public function Currency(){
        return $this->belongsTo(Currency::class,'currency','id');
    }
}
