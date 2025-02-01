<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DepartmentSection extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'department_id',
        'code',
        'name',
        'abbreviation'
    ];

    public function depts()
    {
        //$deptIds = json_decode($this->department_id, true)??[];
        $deptIds = $this->department_id;

        if (!is_array($deptIds)) {
            $deptIds = [$deptIds]; // Convert to an array if it's not
        }

        return Department::whereIn('id', $deptIds)->get();
    }
}
