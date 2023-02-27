<?php

namespace Module\Dokani\Models;

use App\Models\Model;

class OrderDetail extends Model
{

    protected $table = 'order_details';
    protected $guarded;

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
