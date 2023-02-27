<?php

namespace Module\Dokani\Models;

use App\Models\Model;
use App\Traits\AutoCreatedUpdated;

class PurchaseReturnExchange extends Model
{
    use AutoCreatedUpdated;

    protected $guatded = [];


    
    /*
     |--------------------------------------------------------------------------
     | SALE (RELATION)
     |--------------------------------------------------------------------------
    */
    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }





    /*
     |--------------------------------------------------------------------------
     | CUSTOMER (RELATION)
     |--------------------------------------------------------------------------
    */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }




       /*
     |--------------------------------------------------------------------------
     | SALE (RELATION)
     |--------------------------------------------------------------------------
    */
    public function purchaseReturns()
    {
        return $this->hasMany(PurchaseReturn::class);
    }


         /*
     |--------------------------------------------------------------------------
     | SALE (RELATION)
     |--------------------------------------------------------------------------
    */
    public function purchaseExchanges()
    {
        return $this->hasMany(PurchaseExchange::class);
    }




}
