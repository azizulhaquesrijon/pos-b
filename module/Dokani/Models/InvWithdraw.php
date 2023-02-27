<?php

namespace Module\Dokani\Models;

use App\Models\Model;
use App\Traits\AutoCreatedUpdated;

class InvWithdraw extends Model
{

    use AutoCreatedUpdated;



    public function investor(){

        return $this->belongsTo(Investor::class);
    }

    public function account(){

        return $this->belongsTo(Account::class);
    }

}
