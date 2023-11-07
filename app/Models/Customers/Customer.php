<?php

namespace App\Models\Customers;

use App\Models\Settings\Location\Country;
use App\Models\Settings\Location\Division;
use App\Models\Settings\Location\District;
use App\Models\Sales\Sales;
use App\Models\Settings\Location\Upazila;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory,SoftDeletes;

    public function district(){
        return $this->belongsTo(District::class);
    }
    public function division(){
        return $this->belongsTo(Division::class);
    }
    public function upazila(){
        return $this->belongsTo(Upazila::class);
    }
    public function country(){
        return $this->belongsTo(Country::class);
    }
    public function sales(){
        return $this->hasMany(Sales::class);
    }
}
