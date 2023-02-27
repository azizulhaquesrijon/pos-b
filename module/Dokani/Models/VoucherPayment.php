<?php

namespace Module\Dokani\Models;

use App\Models\Model;
use App\Traits\AutoCreatedUpdated;

class VoucherPayment extends Model
{
    use AutoCreatedUpdated;


    public function details()
    {
        return $this->hasMany(VoucherPaymentDetail::class);
    }

    public function party()
    {
        return $this->belongsTo(GParty::class, 'party_id');
    }


    public function account(){

        return $this->belongsTo(Account::class,'account_id','id');
    }


    public function cashFlows(){

        return $this->morphMany(CashFlow::class, 'transactionable');
    }

}
