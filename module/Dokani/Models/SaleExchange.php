<?php

namespace Module\Dokani\Models;


use App\Models\Model;
use App\Traits\AutoCreatedUpdated;


class SaleExchange extends Model
{
    use AutoCreatedUpdated;

    protected $table = 'sale_exchanges';





    /*
     |--------------------------------------------------------------------------
     | SALE RETURN EXCHANGE DETAIL (RELATION)
     |--------------------------------------------------------------------------
    */
    public function saleReturnExchangeDetail()
    {
        return $this->hasOne(SaleReturnExchangeDetail::class, 'sale_exchange_id');
    }


    /*
     |--------------------------------------------------------------------------
     | SALE EXCHANGE DETAIL (RELATION)
     |--------------------------------------------------------------------------
    */
    public function saleExchangeDetail()
    {
        return $this->hasMany(SaleExchangeDetails::class);
    }





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
