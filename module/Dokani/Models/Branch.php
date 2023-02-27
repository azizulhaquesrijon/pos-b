<?php

namespace Module\Dokani\Models;

use App\Models\Model;
use App\Models\User;
use App\Traits\AutoCreatedUpdated;
use Module\HRM\Models\Employee;

class Branch extends Model
{
    use AutoCreatedUpdated;

    public function users(){
        return $this->belongsToMany(User::class);
    }



    public function employee(){
        return $this->hasMany(Employee::class);
    }
}
