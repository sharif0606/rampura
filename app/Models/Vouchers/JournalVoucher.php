<?php

namespace App\Models\Vouchers;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JournalVoucher extends Model
{
    use HasFactory;
    public function generalLedgers() {
        return $this->hasMany(GeneralLedger::class, 'journal_voucher_id', 'id');
    }
}
