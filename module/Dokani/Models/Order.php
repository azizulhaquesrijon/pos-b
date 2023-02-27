<?php

namespace Module\Dokani\Models;

use App\Models\Model;
use App\Traits\AutoCreatedUpdated;

class Order extends Model
{
    use AutoCreatedUpdated;

    public function order_details()
    {
        return $this->hasMany(OrderDetail::class);
    }



    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
