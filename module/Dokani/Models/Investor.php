<?php

namespace Module\Dokani\Models;

use App\Models\Model;
use App\Traits\AutoCreatedUpdated;

class Investor extends Model
{

    use AutoCreatedUpdated;




    public function investorLedgers()
    {
        return $this->hasMany(InvestorLedger::class);
    }



    public function g_party(){

        return $this->belongsTo(GParty::class,'g_party_id','id');
    }


    public function account(){

        return $this->belongsTo(Account::class,'account_id','id');
    }


}
