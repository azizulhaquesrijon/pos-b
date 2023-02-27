<?php

namespace Module\Permission\Models;

use App\Models\Model;

class PermissionUser extends Model
{
    protected $table = 'permission_user';

    public function permission()
    {
        return $this->belongsTo(Permission::class);
    }
}
