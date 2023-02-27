<?php

namespace Module\Dokani\Models;

use App\Models\Model;

class SaleReturnExchangeDetail extends Model
{
    protected $table = 'sale_return_exchange_details';





    /*
     |--------------------------------------------------------------------------
     | SALE RETURN EXCHANGE (RELATION)
     |--------------------------------------------------------------------------
    */
    public function saleReturnExchange()
    {
        return $this->belongsTo(SaleReturnExchange::class, 'sale_return_exchange_id');
    }





    /*
     |--------------------------------------------------------------------------
     | SALE DETAIL (RELATION)
     |--------------------------------------------------------------------------
    */
    public function saleDetail()
    {
        return $this->belongsTo(SaleDetail::class, 'sale_detail_id');
    }





    /*
     |--------------------------------------------------------------------------
     | SALE RETURN (RELATION)
     |--------------------------------------------------------------------------
    */
    public function saleReturn()
    {
        return $this->belongsTo(SaleReturn::class, 'sale_return_id');
    }





    /*
     |--------------------------------------------------------------------------
     | SALE EXCHANGE (RELATION)
     |--------------------------------------------------------------------------
    */
    public function saleExchange()
    {
        return $this->belongsTo(SaleExchange::class, 'sale_exchange_id');
    }
}
