<?php

namespace Module\Dokani\Models;

use App\Models\Model;
use App\Traits\AutoCreatedUpdated;
use Illuminate\Database\Eloquent\Relations\Relation;


Relation::morphMap([

    'Purchase'      => Purchase::class,
    'Payment'       => Payment::class,

]);


class SupplierLedger extends Model
{
    use AutoCreatedUpdated;

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }


    public function source()
    {
        return $this->morphTo();
    }





}
