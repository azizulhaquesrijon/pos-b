<?php

namespace Module\Dokani\Models;

use App\Models\Model;
use App\Traits\AutoCreatedUpdated;

class PurchaseOrder extends Model
{
    use AutoCreatedUpdated;

    public function purchsase_order_details()
    {
        return $this->hasMany(PurchaseOrderDetail::class, 'purchase_order_id');
    }



    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}
