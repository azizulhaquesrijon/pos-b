<?php

namespace Module\Dokani\Models;

use App\Models\Model;

class SaleReturnDetail extends Model
{
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
