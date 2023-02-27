<?php

namespace Module\Dokani\Models;

use App\Models\Model;
use App\Traits\AutoCreatedUpdated;

class PurchaseExchange extends Model
{
    protected $guatded = [];

    /*
     |--------------------------------------------------------------------------
     | PRODUCT (RELATION)
     |--------------------------------------------------------------------------
    */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

}
