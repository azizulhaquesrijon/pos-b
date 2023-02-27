<?php

namespace Module\Dokani\Models;

use App\Models\Model;
use App\Traits\AutoCreatedUpdated;

class Supplier extends Model
{
    use AutoCreatedUpdated;


    public function supplierLedgers()
    {
        return $this->hasMany(SupplierLedger::class);
    }


    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }


}
