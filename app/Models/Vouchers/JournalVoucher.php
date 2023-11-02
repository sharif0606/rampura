<?php

namespace App\Models\Vouchers;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JournalVoucher extends Model
{
    use HasFactory;
    public function generalLedgers() {
        return $this->hasMany(GeneralLedger::class, 'journal_voucher_id', 'id');
    }

    public function createdBy(){
        return $this->belongsTo(User::class,'created_by','id');
    }
    public function updatedBy(){
        return $this->belongsTo(User::class,'updated_by','id');
    }
}
