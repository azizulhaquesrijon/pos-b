<?php

namespace Module\Dokani\Models;

use App\Models\Model;
use App\Models\User;

class ManageSmsApi extends Model
{


    public function user(){

        return $this->belongsTo(User::class,'dokan_id','id');
    }


}
