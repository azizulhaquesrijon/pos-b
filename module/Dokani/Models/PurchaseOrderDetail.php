<?php

namespace Module\Dokani\Models;

use App\Models\Model;

class PurchaseOrderDetail extends Model
{

    protected $table = 'purchase_order_details';
    protected $guarded;

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
