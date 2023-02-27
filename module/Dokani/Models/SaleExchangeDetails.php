<?php

namespace Module\Dokani\Models;


use App\Models\Model;
use App\Traits\AutoCreatedUpdated;


class SaleExchangeDetails extends Model
{
    // use AutoCreatedUpdated;

    protected $table = 'sale_exchange_details';
    protected $guarded = [];



    /*
     |--------------------------------------------------------------------------
     | PRODUCT (RELATION)
     |--------------------------------------------------------------------------
    */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }




}
