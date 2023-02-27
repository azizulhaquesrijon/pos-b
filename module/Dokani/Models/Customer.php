<?php

namespace Module\Dokani\Models;

use App\Models\Model;
use App\Models\User;
use App\Traits\AutoCreatedUpdated;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Module\Dokani\Controllers\CusAreaController;
use function PHPUnit\Framework\returnValueMap;

class Customer extends Model
{
    use AutoCreatedUpdated, HasFactory;



    public function sales()
    {
        return $this->hasMany(Sale::class);
    }



    public function refer_customer(){

        return $this->belongsTo(Customer::class,'refer_by_customer_id','id');
    }



    public function refer_user(){

        return $this->belongsTo(User::class,'refer_by_user_id','id');
    }


    public function customerLedgers()
    {
        return $this->hasMany(CustomerLedger::class);
    }


    public function customerLedger(){

        return $this->hasOne(CustomerLedger::class,'customer_id','id');
    }




    public function area(){

        return $this->belongsTo(CusArea::class,'cus_area_id','id');
    }




    public function category(){

        return $this->belongsTo(CusCategory::class,'cus_category_id','id');
    }




    public function scopeGetCustomer($query){

        return $query->where('is_customer',1);
    }


    public function scopeGetClient($query){

        return $query->where('is_customer',0);
    }




}
