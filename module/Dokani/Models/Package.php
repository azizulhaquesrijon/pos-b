<?php

namespace Module\Dokani\Models;

use App\Models\Model;


class Package extends Model
{
    protected $guarded = [];

    public function type(){

        return $this->belongsTo(PackageType::class,'package_type','id');
    }
}
