<?php

namespace Module\Dokani\Models;

use App\Models\Model;
use App\Traits\AutoCreatedUpdated;
use Illuminate\Database\Eloquent\Relations\Relation;

Relation::morphMap([

    'Sale'              => Sale::class,
    'Collection'        => Collection::class,
    'Refer Sale'        => Sale::class,
    'Refer Balance Transfer'        => Customer::class,
    'Sale Return Exchange'        => SaleReturnExchange::class,

]);

class CustomerLedger extends Model
{
    use AutoCreatedUpdated;

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }




    public function source()
    {
        return $this->morphTo();
    }


}
