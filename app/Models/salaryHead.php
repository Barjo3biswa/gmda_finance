<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class salaryHead extends Model
{
    protected $guarded = ['id'];
    use HasFactory;
    use SoftDeletes;

    public function TempAmount($hd_id, $block_id, $emp_id)
    {
        $block = salaryBlock::find($block_id);
        if ($block->is_finalized) {
            return salaryTrans::where('block_id', $block_id)
                ->where('sal_head_id', $hd_id)->where('emp_id', $emp_id)
                ->sum('amount');
        } else {
            return salaryTemp::where('block_id', $block_id)
                ->where('sal_head_id', $hd_id)->where('emp_id', $emp_id)
                ->sum('amount');
        }

    }

    public function masterAmount($hd_id, $emp_id)
    {
        return salaryHeadAmountDistribution::where('sal_head_id', $hd_id)->where('emp_id', $emp_id)
            ->sum('amount');

    }

    public function totaldays($hd_id, $block_id, $emp_id)
    {
        $block = salaryBlock::find($block_id);
        if ($block->is_finalized) {
            return salaryTrans::where('block_id', $block_id)
                ->where('sal_head_id', $hd_id)->where('emp_id', $emp_id)
                ->first()->working_days;
        } else {
            return salaryTemp::where('block_id', $block_id)
                ->where('sal_head_id', $hd_id)->where('emp_id', $emp_id)
                ->first()->working_days;
        }

    }

    public function TempAmountTotal($hd_id, $block_id)
    {
        $block = salaryBlock::find($block_id);
        if ($block->is_finalized) {
            return salaryTrans::where('block_id', $block_id)
                ->where('sal_head_id', $hd_id)
                ->sum('amount');
        } else {
            return salaryTemp::where('block_id', $block_id)
                ->where('sal_head_id', $hd_id)
                ->sum('amount');
        }
    }
}
