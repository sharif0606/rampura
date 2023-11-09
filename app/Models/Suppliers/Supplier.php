<?php

namespace App\Models\Suppliers;

use App\Models\Settings\Location\Country;
use App\Models\Settings\Location\Division;
use App\Models\Settings\Location\District;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\Purchases\Purchase;
use App\Models\Purchases\Beparian_purchase;
use App\Models\Purchases\Regular_purchase;
use App\Models\Settings\Location\Upazila;

class Supplier extends Model
{
    use HasFactory,softDeletes;

    public function district(){
        return $this->belongsTo(District::class);
    }
    public function division(){
        return $this->belongsTo(Division::class);
    }
    public function country(){
        return $this->belongsTo(Country::class);
    }
    public function upazila(){
        return $this->belongsTo(Upazila::class);
    }
    public function purchase(){
        return $this->hasMany(Purchase::class);
    }
    public function beparian_purchase(){
        return $this->hasMany(Beparian_purchase::class);
    }
    public function regular_purchase(){
        return $this->hasMany(Regular_purchase::class);
    }
}
