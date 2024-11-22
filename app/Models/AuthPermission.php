<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AuthPermission extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    use SoftDeletes;


    public function roles($id)
    {
        $test= AuthRole::all()->filter(function($role) use ($id) {
            $per_ids = $role->setPermissionIds();
            return is_array($per_ids) && in_array($id, $per_ids);
        });

        return $test;
    }
}
