<?php

namespace Module\HRM\Models;

use App\Models\Model;
use App\Models\User;

class Employee extends Model
{


    public function user(){

        return $this->hasOne(User::class);
    }

    public function attendance(){

        return $this->hasMany(Attendance::class);
    }

}
