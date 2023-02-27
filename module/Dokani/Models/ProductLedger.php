<?php

namespace Module\Dokani\Models;

use App\Models\Model;
use App\Traits\AutoCreatedUpdated;
use Illuminate\Database\Eloquent\Relations\Relation;


Relation::morphMap([
    'Product'                   => Product::class,
    'Sale Details'              => Sale::class,
    'Purchase Details'          => Purchase::class,
    'Stock Transfer To Branch'  => StockTransferDetail::class,

]);


class ProductLedger extends Model
{
    use AutoCreatedUpdated;


    public function product()
    {
        return $this->belongsTo(Product::class);
    }


}
