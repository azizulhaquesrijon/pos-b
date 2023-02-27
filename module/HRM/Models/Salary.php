<?php

namespace Module\HRM\Models;

use App\Models\Model;

class Salary extends Model
{

    public function employee(){

        return $this->belongsTo(Employee::class);
    }

}
