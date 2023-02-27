<?php

namespace Module\Dokani\Models;

use App\Models\Model;

class StockAdjustmentDetail extends Model
{

    protected $table = 'stock_adjustment_details';
    protected $guarded = [];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
