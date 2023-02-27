<?php

namespace Module\Dokani\Models;


use App\Models\Model;
use App\Traits\AutoCreatedUpdated;


class SaleReturn extends Model
{
    use AutoCreatedUpdated;

    protected $table = 'sale_returns';





    /*
     |--------------------------------------------------------------------------
     | SALE RETURN DETAIL (RELATION)
     |--------------------------------------------------------------------------
    */
    public function saleReturnDetails()
    {
        return $this->hasMany(SaleReturnDetail::class);
    }



    /*
     |--------------------------------------------------------------------------
     | SALE RETURN EXCHANGE DETAIL (RELATION)
     |--------------------------------------------------------------------------
    */
    public function saleReturnExchangeDetail()
    {
        return $this->hasOne(SaleReturnExchangeDetail::class, 'sale_return_id');
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
