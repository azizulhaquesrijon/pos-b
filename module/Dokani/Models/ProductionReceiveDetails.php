<?php

namespace Module\Dokani\Models;

use App\Models\Model;

class ProductionReceiveDetails extends Model
{


    protected $table = 'production_receive_details';

    protected $guarded = [];



    public function production_receive(){
        $this->belongsTo(ProductionReceive::class,);
    }


    public function product(){
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

}
