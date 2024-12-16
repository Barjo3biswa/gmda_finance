<?php

namespace App\Helpers;

use App\Models\AuthPermission;
use App\Models\salaryProcessStep;
use App\Models\salaryTemp;
use App\Models\User;
use DateTime;
use Illuminate\Support\Facades\Auth;

class CommonHelper
{
    public static function formatDate()
    {

        return "okkkkk";
    }


    public static function isPermissionExist($permission_slug)
    {
        $permission_id = AuthPermission::where('slug', $permission_slug)->first();
        if (!$permission_id) {
            return false;
        } else {
            $permission_id = $permission_id->id;
        }
        $user_id = Auth::user()->id;
        $user = User::where('id', $user_id)->first();
        $extra_permission = json_decode($user->permission_ids) ?? [];
        if (in_array($permission_id, $extra_permission)) {
            return true;
        }
        foreach ($user->roles() as $role) {
            $permissions = json_decode($role->permission_ids);
            if (in_array($permission_id, $permissions)) {
                return true;
            }
        }
        return false;
    }

    public static function checkIsInOrder($step_order)
    {
        if ($step_order == 1) {
            return true;
        }
        $is_step_completed = salaryProcessStep::where('order', ($step_order - 1))->first();
        if ($is_step_completed->status == 'process') {
            return true;
        }
        return false;
    }

    public static function checkFlag($hd_id, $emp_id)
    {
        $temp_salary = salaryTemp::where('sal_head_id', $hd_id)->where('emp_id', $emp_id)->first();
        // dd($temp_salary);
        if ($temp_salary->status == 'temp') {
            return false;
        } else {
            return true;
        }
    }

}

