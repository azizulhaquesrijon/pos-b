<?php

namespace Module\Dokani\Models;

use App\Models\Model;
use App\Traits\AutoCreatedUpdated;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use AutoCreatedUpdated, HasFactory;



    public function  getProductIdAttribute()
    {
        return "#P-" . str_pad($this->id, 5, '0', 0);
    }


    public function stocks()
    {
        return $this->hasOne(ProductStock::class);
    }

    public function storeStock()
    {
        return $this->hasMany(ProductStock::class);
    }


    public function category()
    {
        return $this->belongsTo(Category::class);
    }


    public function unit()
    {
        return $this->belongsTo(Unit::class,'unit_id','id');
    }



    public function brand()
    {
        return $this->belongsTo(Brand::class,'brand_id','id');
    }


    public function sale_details()
    {
        return $this->hasMany(SaleDetail::class);
    }
}
