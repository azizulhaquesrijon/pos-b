<?php

namespace Module\Permission\Models;

use Illuminate\Database\Eloquent\Model;

class ParentPermission extends Model
{
    protected $guarded = [];


    public function submodule()
    {
        return $this->belongsTo(Submodule::class);
    }

    public function permissions()
    {
        return $this->hasMany(Permission::class)->where('status', 1);
    }
}
