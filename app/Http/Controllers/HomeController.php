<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\salaryBlock;
use App\Models\salaryHead;
use App\Models\salaryHeadAmountDistribution;
use App\Models\salaryTrans;
use Illuminate\Http\Request;

class HomeController extends Controller
{

    public function index()
    {
        $paycutHeadid = salaryHead::select('id')->where('pay_cut_hd', 1)->first()->id;

        $month = date('m');
        $year = date('Y');

        $salaryBlock = salaryBlock::where('month', $month)->where('year', $year)->first();


        $isFinalized = $salaryBlock->is_finalized;


        $salaryBlockId = $salaryBlock->id;
        // dd($salaryBlocks);

        $totalpaycut  = salaryTrans::select('amount')
            ->where('sal_head_id', $paycutHeadid)
            ->where('block_id', $salaryBlockId)
            ->get()
            ->sum('amount');

        //  3 employees with highest pay cut
        $paycuts = salaryTrans::with('employee')->select('emp_id', 'amount')
            ->where('sal_head_id', $paycutHeadid)
            ->orderBy('amount', 'DESC')
            ->limit(3)
            ->get();



        // dd($totalpaycut);

        $salaryHeadsIncome = salaryHead::whereNotIn('id', [1,2])->where('pay_head', 'Income')->get();

        $salaryHeadsDeductions = salaryHead::whereNotIn('id', [1,2])->where('pay_head', 'Deduction')->get();

        $estimatedIncomeHeadAmounts = $salaryHeadsIncome->map(function ($head) {
                $totalAmount = salaryHeadAmountDistribution::where('sal_head_id', $head->id)
                    ->where('status', 'Active')
                    ->sum('amount');

                return [
                    'head_name' => $head->code,
                    'total_amount' => $totalAmount
                ];
            });

        $esitimatedDeductionHeadAmounts = $salaryHeadsDeductions->map(function ($head) {
                $totalAmount = salaryHeadAmountDistribution::where('sal_head_id', $head->id)
                    ->where('status', 'Active')
                    ->sum('amount');

                return [
                    'head_name' => $head->code,
                    'total_amount' => $totalAmount
                ];
            });


        $actualIncomeHeadAmounts = [];
        $actualDeductionHeadAmounts = [];

        // dd($salaryBlockId);

        if ($isFinalized == 1) {
            $actualIncomeHeadAmounts = $salaryHeadsIncome->map(function ($head) use ($salaryBlockId) {
                    $totalAmount = salaryTrans::where('sal_head_id', $head->id)
                        // ->where('status', 'Active')
                        ->where('block_id', $salaryBlockId)
                        ->sum('amount');

                    return [
                        'head_name' => $head->code,
                        'total_amount' => $totalAmount
                    ];
                });


            $actualDeductionHeadAmounts = $salaryHeadsDeductions->map(function ($head) use ($salaryBlockId) {
                    $totalAmount = salaryTrans::where('sal_head_id', $head->id)
                        // ->where('status', 'Active')
                        ->where('block_id', $salaryBlockId)
                        ->sum('amount');

                    return [
                        'head_name' => $head->code,
                        'total_amount' => $totalAmount
                    ];
                });

        }


        $employees = Employee::all();

        return view('welcome', compact(
            'totalpaycut',
            'paycuts',
            'estimatedIncomeHeadAmounts',
            'esitimatedDeductionHeadAmounts',
            'actualIncomeHeadAmounts',
            'actualDeductionHeadAmounts',
            'employees',
            'isFinalized'));
    }
}
