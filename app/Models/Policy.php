<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\CommonHelper;
use App\Traits\Scopes\ActiveScope;
// use OwenIt\Auditing\Contracts\Auditable;

class Policy extends Model
{
    // use \OwenIt\Auditing\Auditable;
    use ActiveScope;


    protected $guarded = [];
    protected $table = 'policies';
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'monthly_premium' => 'decimal:2',
        'amount' => 'decimal:2',
    ];
    public function employees()
    {
        return $this->belongsTo(Employee::class,'employee_id', "user_id");
    }
    public function getFullNameAttribute()
    {
        return ($this->title ? $this->title . ' ' : '') . $this->first_name . ($this->middle_name ? " " . $this->middle_name . " " : ($this->last_name ? " " : "")) . $this->last_name;
    }
    public function getStatusNameAttribute()
  {
      return CommonHelper::allstatus()[$this->status];
  }
    /**
     * Scope a query to only include filter
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilter($query)
    {
        return $query->when(request("employee_id"), function ($query) {
          return $query->where("employee_id", request("employee_id"));
        })
        ->when(request("department_id"), function($query){
            return $query->whereHas("employees", function($query){
                return $query->where("department_id", request("department_id"));
            });
        });
    }
    public function licprocessdatas()
    {
        return $this->hasMany(LicProcessData::class, 'policy_id', 'id')->where("status", 1);
    }
    public function licprocessdata()
    {
        return $this->hasOne(LicProcessData::class, 'policy_id', 'id')->where("status", 1);
    }

}
