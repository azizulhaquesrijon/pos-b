<?php

namespace Module\Dokani\Models;

use App\Models\Model;
use App\Traits\AutoCreatedUpdated;

class Payment extends Model
{
    use AutoCreatedUpdated;



    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }




    public function account()
    {
        return $this->belongsTo(Account::class,'account_id','id');
    }




    public function supplier_ledgers()
    {
        return $this->morphMany(SupplierLedger::class, 'source');
    }
}
