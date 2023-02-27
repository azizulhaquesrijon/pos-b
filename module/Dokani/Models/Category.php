<?php

namespace Module\Dokani\Models;

use App\Models\Model;
use App\Traits\AutoCreatedUpdated;

class Category extends Model
{
    use AutoCreatedUpdated;


    public function products(){
        return $this->hasMany(Product::class);
    }
}
