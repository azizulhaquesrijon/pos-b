<?php

namespace Module\Dokani\Models;

use App\Models\Model;
use Illuminate\Database\Eloquent\Relations\Relation;


Relation::morphMap([

    'Sale'                      => SaleDetail::class,
    'Purchase'                  => PurchaseDetail::class,
    'Product'                   => Product::class,
    'Stock Transfer To Branch'  => StockTransferDetail::class,
]);

class ProductStockLog extends Model
{


    public function product(){

        return $this->belongsTo(Product::class,'product_id','id');
    }

}
