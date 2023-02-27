<?php

namespace Module\Dokani\Models;

use App\Models\Model;

class AccountType extends Model
{


    public function account(){

        return $this->hasOne(Account::class,'account_type_id','id');
    }



    public function scopeDokanAccount($query){

        $query->with('account', function ($q){
            $q->where('dokan_id', dokanId());
        });
    }

}
