<?php

namespace Module\Dokani\Models;

use App\Models\Model;

class StockTransferDetail extends Model
{
    
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
