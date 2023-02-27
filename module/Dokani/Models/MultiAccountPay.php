<?php

namespace Module\Dokani\Models;

use App\Models\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

Relation::morphMap([

    'Sale'              => Sale::class,
    'Purchase'          => Purchase::class,

]);

class MultiAccountPay extends Model
{


    public function source()
    {
        return $this->morphTo();
    }


    public function account(){

        return $this->belongsTo(Account::class,'account_id','id');
    }

}
