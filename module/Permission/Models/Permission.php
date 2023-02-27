<?php

namespace Module\Permission\Models;

use App\Models\Model;

class Permission extends Model
{

    protected $table = 'permissions';


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function users()
    {
        return $this->hasMany(PermissionUser::class);
    }

    public function permissionUser()
    {
        return $this->hasOne(PermissionUser::class);
    }
}
