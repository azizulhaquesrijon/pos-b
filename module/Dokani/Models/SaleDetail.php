<?php

namespace Module\Dokani\Models;

use App\Models\Model;

class SaleDetail extends Model
{


    public function product()
    {
        return $this->belongsTo(Product::class);
    }



    public function sale()
    {
        return $this->belongsTo(Sale::class,'sale_id','id');
    }


    public function sale_return_details()
    {
        return $this->hasMany(SaleReturnDetail::class,'sale_details_id','id');
    }



    public function sale_return_detail_for_single_product()
    {
        return $this->hasOne(SaleReturnDetail::class,'sale_details_id','id');
    }

    public function sale_return_details_sum_qty()
    {
        return $this->hasMany(SaleReturnDetail::class,'sale_details_id','id')->sum('quantity');
    }

    public function product_stock_log()
    {
        return $this->morphMany(ProductStockLog::class, 'sourceable');
    }

}
