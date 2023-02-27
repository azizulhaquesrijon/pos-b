<?php

namespace Module\Dokani\Models;

use App\Models\Model;
use App\Traits\AutoCreatedUpdated;

class ProductDamageDetail extends Model
{
    use AutoCreatedUpdated;

    protected $table = 'product_damage_details';


    public function productDamage()
    {
        return $this->belongsTo(ProductDamage::class, 'product_damage_id');
    }





    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }


}
