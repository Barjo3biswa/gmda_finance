<?php

namespace App\Models;

//use App\Traits\Scopes\ActiveScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Advance extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */

    use SoftDeletes;
    public static $ACTIVE = 1;
    public static $INACTIVE = 0;
    public static $STOP = 2;
    protected $guarded = ['id'];


    public function employees()
    {
        //return $this->hasOne(Employee::class,'id','employee_id')->withTrashed();

    }
    public function getFullNameAttribute()
    {
        return ($this->title ? $this->title . ' ' : '') . $this->first_name . ($this->middle_name ? " " . $this->middle_name . " " : ($this->last_name ? " " : "")) . $this->last_name;
    }

    public function scopeFilter($query)
    {
        return $query->when(request("employee_id"), function ($query) {
          return $query->where("employee_id", request("employee_id"));
        })
        ->when(request("type"), function ($query) {
            return $query->where("loan_type_id", request("type"));
        })
        ->when(request("department_id"), function($query){
            return $query->whereHas("employees", function($query){
                return $query->where("department_id", request("department_id"));
            });
        });
    }

    //advaancesprocessdatas
    // public function advaancesprocessdatas()
    // {
    //     return $this->hasMany(AdvancesProcessData::class, 'advance_id', 'id')->where("status", 0);
    // }

    public function advanceprocessdata()
    {
        return $this->hasOne(AdvanceProcess::class, 'advance_id', 'id')->where("status", 1);
    }

    public function salhead()
    {
        return $this->belongsTo(SalaryHead::class, 'loan_head_id');
    }
    
    public function remainingAmount()
    {
        return number_format($this->amount - $this->recovered_amount, 2, '.', '');
    }
    /**
     * Get the reminaing_amount
     *
     * @param  string  $value
     * @return string
     */
    public function getRemainingAmountAttribute()
    {
        return number_format($this->amount - $this->recovered_amount, 2, '.', '');
    }

    public function advanceType()
    {
        return $this->belongsTo(AdvanceType::class, 'advance_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class,'user_id','user_id');
    }

}