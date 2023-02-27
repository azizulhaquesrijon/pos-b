<?php

namespace Module\Dokani\Models;

use App\Models\Model;
use App\Traits\AutoCreatedUpdated;

class SaleReturnExchangePayment extends Model
{
    use AutoCreatedUpdated;

    protected $table = 'sale_return_exchange_payments';





    /*
     |--------------------------------------------------------------------------
     | SALE (RELATION)
     |--------------------------------------------------------------------------
    */
    public function sale()
    {
        return $this->belongsTo(Sale::class, 'sale_id');
    }





    /*
     |--------------------------------------------------------------------------
     | SALE RETURN EXCHANGE (RELATION)
     |--------------------------------------------------------------------------
    */
    public function saleReturnExchange()
    {
        return $this->belongsTo(SaleReturnExchange::class, 'sale_return_exchange_id');
    }

}
