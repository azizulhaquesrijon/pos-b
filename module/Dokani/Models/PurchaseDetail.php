<?php

namespace Module\Dokani\Models;

use App\Models\Model;

class PurchaseDetail extends Model
{

    public function product()
    {
        return $this->belongsTo(Product::class);
    }


    public function stock()
    {
        return $this->hasOne(ProductStock::class, 'lot', 'lot');
    }
}
