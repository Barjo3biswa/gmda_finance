<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalarySummmary extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    protected $fillable = [
        'emp_id', 
        'emp_code', 
        'sal_block_id', 
        'month', 
        'year', 
        'salary_details'
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $salaryHeads = \App\Models\SalaryHead::all();
        $dynamicColumns = [];
        
        foreach ($salaryHeads as $head) {
            if ($head->pay_head == 'Deduction') {
                $dynamicColumns[] = 'DED_' . $head->salary_head_code;
            }
            
            if ($head->pay_head == 'Income') {
                $dynamicColumns[] = 'INC_' . $head->salary_head_code;
            }
        }

        $this->fillable = array_merge($this->fillable, $dynamicColumns);
    }
}
