<?php

namespace Module\Dokani\Models;


use App\Models\Model;
use App\Traits\AutoCreatedUpdated;

class ProductDamage extends Model
{
    use AutoCreatedUpdated;

    protected $dates = [
        'date'
    ];

//    public function items()
//    {
//        return $this->hasMany(ProductDamageItem::class, 'product_damage_id', 'id');
//    }

    public function scopeCompanies($query)
    {
        return $query->where('company_id', auth()->user()->company_id);
    }



    public function  getInvoiceIdAttribute()
    {
        return "#D-" . str_pad($this->id, 5, '0', 0);
    }


    public function productDamageDetails()
    {
        return $this->hasMany(ProductDamageDetail::class, 'product_damage_id');
    }


    public function productDamageDetails_when_saleResultExchange()
    {
        return $this->hasOne(ProductDamageDetail::class, 'product_damage_id');
    }
}
