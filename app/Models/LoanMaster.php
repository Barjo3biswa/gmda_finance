<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Advance;
use App\Models\User;
use App\Models\Department;
use App\Models\AuthDesignation;
use App\Models\Employee;
use App\Models\LoanTransaction;
use Illuminate\Database\Eloquent\SoftDeletes;

class LoanMaster extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'advance_id',
        'reference_no',
        'user_id',
        'emp_code',
        'department_id',
        'designation_id',
        'loan_type_id',
        'loan_amount',
        'loan_interest_rate',
        'principal_amount',
        'outstanding_principal',
        'no_of_installment',
        'principal_installment',
        'monthly_emi',
        'adj_emi',
        'adj_emi_in',
        'interest_amount',
        'outstanding_interest_amount',
        'no_of_installment_interest',
        'interest_installment',
        'adj_interest_emi',
        'adj_interest_emi_in',
        'fld_deptid',
        'fld_desigid',
        'sal_block_id',
        'sal_block_month',
        'sal_block_yr',
        'updated_by'
    ];

    public function advance()
    {
        return $this->belongsTo(Advance::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function designation()
    {
        return $this->belongsTo(AuthDesignation::class);
    }

    public function scopeFilter($query)
    {
        return $query->when(request("employee_id"), function ($query) {
            return $query->where("employee_id", request("employee_id"));
        })
            ->when(request("type"), function ($query) {
                return $query->where("loan_type_id", request("type"));
            })
            ->when(request("department_id"), function ($query) {
                return $query->whereHas("employees", function ($query) {
                    return $query->where("department_id", request("department_id"));
                });
            });
    }

    public function advanceprocessdata()
    {
        return $this->hasOne(AdvanceProcess::class, 'advance_id', 'id')->where("status", 1);
    }

    public function salhead()
    {
        return $this->belongsTo(SalaryHead::class, 'sal_block_id');
    }

    public function advanceType()
    {
        return $this->belongsTo(AdvanceType::class, 'loan_type_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'user_id', 'user_id');
    }
}
