<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'abbreviation',
    ];

    public function sections()
    {
        $deptIds = json_decode($this->dept_ids, true)??[];
        return DepartmentSection::whereIn('id', $deptIds)->get();
    }

    public function employee() { return $this->belongsTo(Employee::class); } // employee()
}