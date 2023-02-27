<?php

namespace Module\Dokani\Models;

use App\Models\Model;
use App\Traits\AutoCreatedUpdated;
use Illuminate\Database\Eloquent\Relations\Relation;


Relation::morphMap([

    'Investor'      => Investor::class,
    'Withdraw'      => InvWithdraw::class,

]);

class InvestorLedger extends Model
{

    use AutoCreatedUpdated;



    public function investor()
    {
        return $this->belongsTo(Investor::class,'investor_id','id');
    }


}
