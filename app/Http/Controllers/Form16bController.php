<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\SalarySummmary;
use App\Models\User;
use Illuminate\Http\Request;

class Form16bController extends Controller
{
    public function index()
    {
        $employees = User::select('id', 'name')->where('salary_flag', 'open')->get();

        return view('form16b.index', compact('employees'));
    }

    public function create(Request $request)
    {
        $employee = User::where('id', $request->employee_id)->first();
        $financialYear = $request->financial_year;

        $salarySummary = SalarySummmary::where('emp_id', $employee->id)->where('financial_year', $financialYear)->count();

        // dd($salarySummary);

        // if ($salarySummary != 12) {
        //     return redirect()->route('form16.index')->with('error', 'Complete Salary Summaries does not exists for this employee and financial year.');
        // }

        $totalINC_HRA = SalarySummmary::select('emp_id', 'INC_HRA', 'financial_year')
            ->where('emp_id', $employee->id)
            ->where('financial_year', $financialYear)
            ->sum('INC_HRA');


        // dd($totalINC_HRA);

        $totalINC_OTHERALLW = SalarySummmary::select('emp_id', 'INC_OTHERALLW', 'financial_year')
            ->where('emp_id', $employee->id)
            ->where('financial_year', $financialYear)
            ->sum('INC_OTHERALLW');

        // dd($totalINC_OTHERALLW);

        $totalDED_PTAX = SalarySummmary::select('emp_id', 'DED_PTAX', 'financial_year')
            ->where('emp_id', $employee->id)
            ->where('financial_year', $financialYear)
            ->sum('DED_PTAX');

        // dd($totalDED_PTAX);

        $totalDED_GPF = SalarySummmary::select('emp_id', 'DED_GPF', 'financial_year')
            ->where('emp_id', $employee->id)
            ->where('financial_year', $financialYear)
            ->sum('DED_GPF');

        // dd($totalDED_GPF);

        $totalDED_EPF = SalarySummmary::select('emp_id', 'DED_EPF', 'financial_year')
            ->where('emp_id', $employee->id)
            ->where('financial_year', $financialYear)
            ->sum('DED_EPF');


        // dd($totalDED_EPF);

        $totalDED_NPS = SalarySummmary::select('emp_id', 'DED_NPS', 'financial_year')
            ->where('emp_id', $employee->id)
            ->where('financial_year', $financialYear)
            ->sum('DED_NPS');

        // dd($totalDED_NPS);

        $totalDED_CPF = SalarySummmary::select('emp_id', 'DED_CPF', 'financial_year')
            ->where('emp_id', $employee->id)
            ->where('financial_year', $financialYear)
            ->sum('DED_CPF');

        // dd($totalDED_CPF);

        // $totalGPF_EPF_CPF = $totalDED_GPF + $totalDED_EPF + $totalDED_CPF;

        $totalGPF_EPF_CPF = $totalDED_GPF + $totalDED_NPS;

        $totalDED_GSLI = SalarySummmary::select('emp_id', 'DED_GSLI', 'financial_year')
            ->where('emp_id', $employee->id)
            ->where('financial_year', $financialYear)
            ->sum('DED_GSLI');

        // dd($totalDED_GSLI);

        // Note* there is a seperate field G.I.S but in old code in gis filed gsli is used

        return view('form16b.create', compact(
            'employee',
            'financialYear',
            'totalINC_HRA',
            'totalINC_OTHERALLW',
            'totalDED_PTAX',
            'totalGPF_EPF_CPF',
            'totalDED_GSLI'
        ));
    }

    public function store(Request $request)
    {
        dd($request->all());
    }
}
