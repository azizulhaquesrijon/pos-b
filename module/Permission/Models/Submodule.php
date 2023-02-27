<?php

namespace Module\Permission\Models;

use App\Models\Model;

class Submodule extends Model
{

    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function parent_permissions()
    {
        return $this->hasMany(ParentPermission::class);
    }

    public function permissions()
    {
        return $this->hasMany(Permission::class);
    }
}
