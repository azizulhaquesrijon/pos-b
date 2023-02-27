<?php

namespace Module\Dokani\Models;

use App\Models\Model;
use App\Traits\AutoCreatedUpdated;
use Module\Dokani\Models\Customer;

class Collection extends Model
{
    use AutoCreatedUpdated;



    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }




    public function account()
    {
        return $this->belongsTo(Account::class,'account_id','id');
    }


    public function customer_ledgers()
    {
        return $this->morphMany(CustomerLedger::class, 'source');
    }

}
