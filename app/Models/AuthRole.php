<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AuthRole extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    use SoftDeletes;

    public function setPermissionIds()
    {
        return json_decode($this->permission_ids, true);
    }

    public function permissions()
    {
        $permissionIds = json_decode($this->permission_ids, true)??[];
        return AuthPermission::whereIn('id', $permissionIds)->get();
    }
}
