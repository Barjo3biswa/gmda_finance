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

    public static function allMonthArray()
    {
        return [
            1 => "January",
            2 => "February",
            3 => "March",
            4 => "April",
            5 => "May",
            6 => "June",
            7 => "July",
            8 => "August",
            9 => "September",
            10 => "October",
            11 => "November",
            12 => "December",
        ];
    }

    public static function getYearList(): array
    {
        $start_year = 2020;
        $end_year = date('Y');
        $year_list = [];
        for ($i = $start_year; $i <= $end_year; $i++) {
            $year_list[$i] = $i;
        }
        return $year_list;
    }

    public static function allstatus()
    {
        return [
            0 => "Stop Policy",
            2 => "Not yet Start",
            1 => "Active Policy",
            9 => "Close Policy",

        ];
    }

    public static function getMonthName($month)
    {
        $month = (Int) $month;
        try {
            $months = self::allMonthArray();
            return $months[$month];
        } catch (\Throwable $th) {
            throw new \Exception("Invalid month {$month}");
        }
    }




    public static function number_to_words($x)
    {
        $nwords = array(
            "",
            "One",
            "Two",
            "Three",
            "Four",
            "Five",
            "Six",
            "Seven",
            "Eight",
            "Nine",
            "Ten",
            "Eleven",
            "Twelve",
            "Thirteen",
            "Fourteen",
            "Fifteen",
            "Sixteen",
            "Seventeen",
            "Eightteen",
            "Nineteen",
            "Twenty",
            30 => "Thirty",
            40 => "Fourty",
            50 => "Fifty",
            60 => "Sixty",
            70 => "Seventy",
            80 => "Eigthy",
            90 => "Ninety"
        );
        if (!is_numeric($x)) {
            $w = '#';
        } else if (fmod($x, 1) != 0) {
            $w = '#';
        } else {
            if ($x < 0) {
                $w = 'minus ';
                $x = -$x;
            } else {
                $w = '';
            }
            if ($x < 21) {
                $w .= $nwords[$x];
            } else if ($x < 100) {
                $w .= $nwords[10 * floor($x / 10)];
                $r = fmod($x, 10);
                if ($r > 0) {
                    $w .= ' ' . $nwords[$r];
                }
            } else if ($x < 1000) {

                $w .= $nwords[floor($x / 100)] . ' Hundred';
                $r = fmod($x, 100);
                if ($r > 0) {
                    $w .= ' ' . number_to_words($r);
                }
            } else if ($x < 100000) {
                $w .= number_to_words(floor($x / 1000)) . ' Thousand';
                $r = fmod($x, 1000);
                if ($r > 0) {
                    $w .= ' ';
                    if ($r < 100) {
                        $w .= ' ';
                    }
                    $w .= number_to_words($r);
                }
            } else if ($x < 10000000) {
                $w .= number_to_words(floor($x / 100000)) . ' Lakh';
                $r = fmod($x, 100000);
                if ($r > 0) {
                    $w .= ' ';
                    if ($r < 100) {
                        $w .= ' ';
                    }
                    $w .= number_to_words($r);
                }
            } else {
                $w .= number_to_words(floor($x / 1000000)) . ' Million';
                $r = fmod($x, 1000000);
                if ($r > 0) {
                    $w .= ' ';
                    if ($r < 100) {
                        $word .= ' ';
                    }
                    $w .= number_to_words($r);
                }
            }
        }
        return $w;
    }

}

