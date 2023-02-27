<?php

namespace Module\Dokani\Models;

use App\Models\Model;
use App\Traits\AutoCreatedUpdated;

class PurchaseReturn extends Model
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



    /*
     |--------------------------------------------------------------------------
     |            PURCHASE
     |--------------------------------------------------------------------------
    */
    public function purchase_details()
    {
        return $this->belongsTo(PurchaseDetail::class, 'purchase_detail_id', 'id');
    }


}
