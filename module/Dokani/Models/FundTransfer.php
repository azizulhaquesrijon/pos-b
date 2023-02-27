<?php

namespace Module\Dokani\Models;

use App\Models\Model;
use App\Traits\AutoDokanCreated;

class FundTransfer extends Model
{
    use AutoDokanCreated;


    public function from_account(){

        return $this->belongsTo(Account::class, 'from_account_id','id');
    }

    public function to_account(){

        return $this->belongsTo(Account::class, 'to_account_id','id');
    }
}
