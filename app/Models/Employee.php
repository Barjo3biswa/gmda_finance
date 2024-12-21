<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    public function advanceRequests()
    {
        return $this->hasMany(AdvanceRequest::class, 'user_id');
    }
    public function designation()
    {
        return $this->belongsTo(AuthDesignation::class, 'designation_id');
    }
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }
    public function payband()
    {
        return $this->belongsTo(AuthPayband::class, 'payband_id');
    }
    public function emplName()
    {
        return $this->belongsTo(Employee::class, 'user_id');
    }
    public function policy(){
        return $this->hasMany(Policy::class,'employee_id','id');
    }
}
