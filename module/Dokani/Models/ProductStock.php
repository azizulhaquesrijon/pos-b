<?php

namespace Module\Dokani\Models;

use App\Models\Model;

class ProductStock extends Model
{
    public $table = "product_stocks";

    protected $guarded = [];


    public function product(){

        return $this->belongsTo(Product::class);
    }




}
