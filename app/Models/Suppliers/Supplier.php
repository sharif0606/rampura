<?php

namespace App\Models\Suppliers;

use App\Models\Settings\Location\Country;
use App\Models\Settings\Location\Division;
use App\Models\Settings\Location\District;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
}
