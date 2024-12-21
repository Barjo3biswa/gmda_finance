<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\salaryHead;
use App\Models\salaryHeadAmountDistribution;
use Illuminate\Http\Request;

class HomeController extends Controller
{

    public function index()
    {
        $paycutHeadid = salaryHead::select('id')->where('code', 'Pay Cut')->first()->id;

        $totalpaycut  = salaryHeadAmountDistribution::select('amount')
            ->where('sal_head_id', $paycutHeadid)
            ->where('status', "Active")
            ->get()
            ->sum('amount');

        $salaryHeadsIncome = salaryHead::whereNotIn('id', [1,2])->where('pay_head', 'Income')->get();

        $salaryHeadsDeductions = salaryHead::whereNotIn('id', [1,2])->where('pay_head', 'Deduction')->get();

        $incomeHeadAmounts = $salaryHeadsIncome->map(function ($head) {
                $totalAmount = salaryHeadAmountDistribution::where('sal_head_id', $head->id)
                    ->where('status', 'Active')
                    ->sum('amount');

                return [
                    'head_name' => $head->code,
                    'total_amount' => $totalAmount
                ];
            });

        $deductionHeadAmounts = $salaryHeadsDeductions->map(function ($head) {
                $totalAmount = salaryHeadAmountDistribution::where('sal_head_id', $head->id)
                    ->where('status', 'Active')
                    ->sum('amount');

                return [
                    'head_name' => $head->code,
                    'total_amount' => $totalAmount
                ];
            });


        //  3 employees with highest pay cut
        $paycuts = salaryHeadAmountDistribution::with('employee')->select('emp_id', 'amount')
            ->where('sal_head_id', $paycutHeadid)
            ->where('status', "Active")
            ->orderBy('amount', 'DESC')
            ->limit(3)
            ->get();


        $employees = Employee::all();

        return view('welcome', compact(
            'totalpaycut',
            'paycuts',
            'incomeHeadAmounts',
            'deductionHeadAmounts',
            'employees'));
    }
}
